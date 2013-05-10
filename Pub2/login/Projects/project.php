<?php
class Project {

	protected $lf;
	protected $mysqldb;
	protected $votingtable;
	protected $v;
	protected $querydone;
	protected $cquerydone;
	protected $daysremaining;

	public function __construct($lf) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."dbdata.php");
		include_once($rootlocation."login/mysqlClass.php");
		$this->mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
		
		$this->mysqldb->connect();
		$this->mysqldb->select();	
	
		$this->lf = $lf;
		
		//$this->lf->logMessage("Project constructor starting\n");
		
		$this->querydone = 0;

	}
	
	
	public function updateProject(&$valuearray) {
	
		//echo "<p>********** updateInitiative starting</p>";

		$query = "UPDATE projects SET ".
				"pjname = '$valuearray[iname]', ".
				"shortdesc = '$valuearray[shortdesc]', ".
				"abstract = '$valuearray[abstract]' ".
				"WHERE iid = '$valuearray[iid]'";
		
		$this->mysqldb->query($query);

	}
	
	public function createProject(&$valuearray) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

						
		// check for duplicate project entries in the database, if so, redisplay the input screen and show 
		// them the problem
		if( $this->mysqldb->duplicateEntryString($valuearray['descname'], "descname", "projects") ) {
			//duplicate identifier entry
			$msg = "openProject: Project entry insertion failed, duplicate project.\n";
			$this->lf->logMessage($msg);
			return (1);
		}		

		$this->mysqldb->query("insert into projects(".
					"pjname, shortdesc, abstract, submittedbynm, submittedbyid, ".
					"istatus, descname, datetimeopened, filesdir, auth) ".
					"values(".
					"'$valuearray[pjname]', '$valuearray[shortdesc]', ".
					"'$valuearray[abstract]', '$valuearray[submittedbynm]', '$valuearray[submittedbyid]', ".
					"'$valuearray[istatus]', ".
					"'$valuearray[descname]', '$valuearray[dateopen]', '$valuearray[filesdir]', '$valuearray[auth]')");

		$descname = $valuearray['descname'];
		$this->mysqldb->query("select pjid from projects where descname='$descname'");
		$row = $this->mysqldb->fetchObject();
		$valuearray['pjid'] = $pjid = $row->pjid;
		
	
		// Create new entry for comment instance and a new comment table to record comments for this project
		
		$ctablename = "projectcomments".$pjid;
		//echo "<p>********** createProject updating projects table with ctable name</p>";
		$this->mysqldb->query("update projects set ctablename='".$ctablename."' where pjid=".$pjid);
		
		// Create the project comments table
		$query = "CREATE TABLE IF NOT EXISTS $ctablename (
				id SMALLINT(5) unsigned not null primary key auto_increment,
				pjid SMALLINT(5) unsigned not null,
				comment VARCHAR(6000) not null,
				submittedtime INT(10) unsigned,
				submittedbynm VARCHAR(40) not null,
				submittedbyid SMALLINT(5) not null
				)";
						
		$this->mysqldb->query($query);

		$result = $this->mysqldb->getResult();
		if(! $result) {
			$msg = "comment table creation failed";
		} else {
			$msg = "comment table creation succeeded";
		}			
		$this->lf->logMessage($msg."\n");
		$now = date('r', time() );

		$valuearray[myusername] = $valuearray[submittedbynm];
		$valuearray[myuserid] = $valuearray[submittedbyid];
		$valuearray[comment] = "<p>New project, project name: ".$valuearray[pjname]."</p>";
		$this->addComment($pjid,&$valuearray);
		
		// check and see if the filesdir is already there, if not, create it
		if( !file_exists($rootlocation.$projects.$valuearray['filesdir']) ) {
		//if( !chdir($rootlocation.$projects.$valuearray['filesdir']) ) {
	
			//echo "<p>*****creating dir: ".$rootlocation.$projects.$valuearray['filesdir']."</p>";
			if(!mkdir($rootlocation.$projects.$valuearray['filesdir'], 0755)) {
				return(1);
			}
		}
		return(0);
	}
	
	public function getCTableName($prjid) {
		//echo "<p>*****getCTableName prjid: ".$prjid."</p>";
		$query = "SELECT ctablename FROM projects WHERE pjid=".$prjid;
		$this->mysqldb->query($query);
		$row = $this->mysqldb->fetchObject();
		return($row->ctablename);
	}	
	

	public function addComment($pjid, &$valuearray) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		//echo "<p>****addComment******pjid: ".$pjid." ctablename: ".$ctablename."</p>";			

		$ctablename = $this->getCTableName($pjid);
		$opentime = time();
				
		$this->mysqldb->query("insert into ".$ctablename." (".
			"pjid, comment, submittedtime, submittedbynm, submittedbyid) ".
			"values(".
			"'$pjid', '$valuearray[comment]', '$opentime', '$valuearray[myusername]', '$valuearray[myuserid]')");		
	}

	public function deleteComment($prjid, $cid) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
		include_once($rootlocation."login/mysqlClass.php");
		$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);		
		$mysqldb->connect();
		$mysqldb->select();			

		//echo "<p>********deleting comment id: ".$cid."</p>";
		$ctablename = $this->getCTableName($prjid);	
		$query = "DELETE FROM ".$ctablename." WHERE id=".$cid;
		//echo "<p>*********query: ".$query."</p>";
		$mysqldb->query($query);	
		//echo "<p>*********after deleteComment</p>";
		return;
	}


	
	public function nextComment($prjid) {
		if ( !$this->cquerydone ) {
			//echo "<p>****nextComment**RESETTING QUERY****prjid: ".$prjid." ctablename: ".$ctablename."</p>";			
			$ctablename = $this->getCTableName($prjid);
			$query = "SELECT * FROM ".$ctablename." ORDER BY submittedtime";
			$this->mysqldb->query($query);
			$this->cquerydone = 1;
			if( !($row = $this->mysqldb->fetchObject()) ) {
				$this->cquerydone=0;
				return(0);
			}
		} else {
			if( !($row = $this->mysqldb->fetchObject()) ) {
				$this->cquerydone=0;
				return(0);
			}
		}
		return($row);	
	}
	
	public function getComment($prjid, $cid) {
	
	}
	
	public function nextProject() {
				
		if ( !$this->querydone ) {
			$query = "SELECT * FROM projects ORDER BY datetimeopened";
			$this->mysqldb->query($query);
			$this->querydone = 1;
			if( !($row = $this->mysqldb->fetchObject()) ) {
				$this->querydone=0;
				return(0);
			}
		} else {
			if( !($row = $this->mysqldb->fetchObject()) ) {
				$this->querydone=0;
				return(0);
			}
		}
		return($row);
	}
	
	public function getProject($pjid) {
		$query = "SELECT * FROM projects WHERE pjid=$pjid";
		//echo "<p>***********query: ".$query."</p>";
		$this->mysqldb->query($query);
		if( ($numrows = $this->mysqldb->numRows()) == 0) return(0);
		return($this->mysqldb->fetchArray());
	}
	
	public function isAuthorized($userid, $pjid) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."login/VisitorDataClass.php");
		
		$vd = new VisitorData($this->lf);
			
		//webadmin user is always authorized
		if( $vd->myUserPrivFromID($userid) == $upriv['webadmin'] ) {
			//echo "<p>********isAuthorized: userid (webadmin): ".$userid." is authorized</p>";
			return (TRUE);
		}

		$row = $this->getProject($pjid);
		//echo "<p>***** isAuth</p>";
		$auth = $row['auth'];
		//print_r($auth);
		$auth = unserialize($auth);
		if( !is_array($auth) ) echo "<p>*****auth not an array</p>";
/*
		$idx=0;
		while( $auth[$idx] ) {
			echo "<p> id: ".$auth[$idx]['id']." name: ".$auth[$idx]['name']." sense: ".$auth[$idx]['sense']."</p>";
			$idx++;
		}		
*/
		$index = 0;
		$notauthorized = 1;
		//echo "<p>******isAuthorized my userid: ".$userid."</p>";
		while ( $auth[$index]['id'] ) {
			//echo "<p>*****isAuthorized checking id: ".$auth[$index]['id']."</p>";
			if( $auth[$index]['id']	== $userid ) {
				if( $auth[$index]['sense'] ) {
					//echo "<p>********isAuthorized: userid: ".$userid." is authorized</p>";
					return(TRUE);
				}
			}
			$index++;		
		}
		return(FALSE);
	}
	
}
?>