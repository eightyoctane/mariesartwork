<?php
class Vote {
	protected $voteid;
	protected $lf;
	protected $mysqldb;
	protected $ballet;

	public function __construct($lf) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."dbdata.php");
		include_once($rootlocation."login/mysqlClass.php");
		include_once($rootlocation."login/Initiatives/ballet.php");
		$this->mysqldb = new mysql($system,$dbuser,$dbpwd,$database);		
		$this->mysqldb->connect();
		$this->mysqldb->select();	
	
		$this->lf = $lf;
		//$this->lf->logMessage("Vote constructor: Instantiating Vote\n");
		
		$this->ballet = new ballet($lf);
		
	}
	
	public function addVoteInstance($dateopened, $iorpid, $votetype, $vtablenamep, $duration, $residentsdecide) {

/*
		
		This function has to update the database twice, first time gives us a voteid, then
		we update a second time to insert the vtablename which includes the voteid.
		
			1. voteid - is a unique id produced automatically when we create a new entry
			2. vstatus - is the status of the vote, open, closed
			3. vtablenamep - is the name of the voterlist table ("voter+initiative|proposal+descriptivename+voteid")
			4. vdesc - name of the initiative or proposal varchar(40)
			5. iorpid - initiative or proposal id smallint(5) unsigned not null
			6. votetype - initiative or proposal varchar(20)
			
		Insert the basic entry
		voteid (created automatically)
		vstatus
		vtablename
		vdesc
		iorpid
		votetype
		datetimeopened
		duration		
*/
		//first make sure we don't have a duplicate entry
		$this->mysqldb->query("select iorpid from vote where votetype='$votetype' and iorpid='$iorpid'");
		if( $numrows = $this->mysqldb->numRows() > 0) {
			//echo "<p> ************ addVoteInstance - we have a duplicate entry </p>";
			//we have a duplicate entry
			return(0);
		}

		//ok this is a valid new entry, so continue
		$s = "insert into vote(".
					"vstatus,iorpid,votetype,datetimeopened,duration)".
					"values('open','$iorpid','$votetype','$dateopened','$duration')";
		
		//echo "<p> ************ addVoteInstance - sql statement ".$s."</p>";
		
		$sql=$this->mysqldb->query($s);

		

		// Use the new id that was created automatically during the insert, to build the ballet table name
		
		$voteinstance = $this->getVoteID($iorpid);
		
		$ballettablename = $vtablenamep.$voteinstance;
		//echo "<p> ***********ballettablename>>>> ".$ballettablename."</p>";		
		
		$sql=$this->mysqldb->query("update vote set vtablename='$ballettablename' where iorpid='$iorpid'");
		$sql=$this->mysqldb->query("update vote set voteid='$voteinstance' where iorpid='$iorpid'");

		$this->lf->logMessage("vote/addVoteInstance: ballettablename> ".$ballettablename."\n");

		
		// Now we have the name of the voter table which we need to create
		
		// Finally, we need to create the new voter table and populate it with
		// the voters appropriate for this vote
		
		$this->ballet->createVoterTable($ballettablename);
		
		// Load up the ballet with the votors that are allowed to vote
		$this->populateVoterTable($ballettablename, $residentsdecide);	

		return($voteinstance);
	}
	
	public function getVoteInfo($iorpid) {
		$this->mysqldb->query("SELECT * FROM vote WHERE iorpid='$iorpid'");
		return($this->mysqldb->fetchObject());
	}		
	
		
	public function getVoteID($iorpid) {
		$this->mysqldb->query("SELECT voteid, iorpid FROM vote WHERE iorpid='$iorpid'");
		$row = $this->mysqldb->fetchObject();
		//echo "<p>***************in vote returning vote instance id as ".$row->voteid."</p>";
		return($row->voteid);
	}
	
		
	public function castMyVote($voterid, $iorpid, $v, $votecomment) {
	
		// A number of things have to happen here
		// Locate the voter table using the iorpid
		// Insure that the voter is legitimate
		// Deal with the fact that the voter has already voted

		$this->mysqldb->query("select vtablename from vote where iorpid='$iorpid'");
		if($v[yes]) {
			$vote = "yes";
		} else {
			$vote= "no";
		}
		
		$vtablename = $this->mysqldb->getItem('vtablename');
		
		//echo "<p>******in castMyVote****votecomment  ".$votecomment."</p>";
		
		$this->ballet->castVote($vtablename, $voterid, $vote, $votecomment);	
	}

	public function closeVoting($voteid) {
		
		//print("<p>closeVoting *******close voting in vote table**********voteid: ".$voteid."</p>");
	
		// First insure that no more votes can be cast
		$sql=$this->mysqldb->query("update vote set vstatus='closed' where voteid='$voteid'");

		//print("<p>closeVoting *******collect info from voting table **********</p>");

		
		// Locate the ballet and collect the results
		$sql=$this->mysqldb->query("select vtablename from vote where voteid='$voteid'");
		$vtablename = $this->mysqldb->getItem('vtablename');
		
		//print("<p>closeVoting *******voting instance table ********* ".$vtablename."</p>");

		
		$this->ballet->tallyVotingResults($vtablename, &$balletresults);

		
		print("<p>closeVoting *******voting results**********voteid: ".$voteid."</p>");
		
		print_r($balletresults);
		$votesyes = $balletesults['votesyes'];
		$votesno = $balletesults['votesno'];
		$votesnone = $balletresults['votesnone'];
		$comments = $balletresults['comments'];
		$vresult = $balletresults['vresult'];
		$sql=$this->mysqldb->query("update vote set votesyes='$votesyes' where voteid='$voteid'");
		$sql=$this->mysqldb->query("update vote set votesno='$votesno' where voteid='$voteid'");
		$sql=$this->mysqldb->query("update vote set votesnone='$votesnone' where voteid='$voteid'");
		$sql=$this->mysqldb->query("update vote set comments='$comments' where voteid='$voteid'");
		$sql=$this->mysqldb->query("update vote set vresult='$vresult' where voteid='$voteid'");			
		
		$sql=$this->mysqldb->query("drop table $vtablename");
		//echo "<p>********closeVoting status: ".$vresult."</p>";
		return ($vresult);
	}
	
	public function getVoterList($criteria) {
	
		// criteria = 1 return all residents
		// criteria = 0 return just board members	

		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		
		switch($criteria) {
			case 0:
				// get board members only
				$query = "SELECT rowID, vid, u_type, u_priv FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv>".$upriv['commchair']." AND u_priv<".$upriv['webadmin']." ORDER BY vid";
				break;
			case 1:
				// get all residents
				$query = "SELECT rowID, vid, u_type, u_priv FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv<".$upriv['webadmin']." ORDER by vid";		
				break;	
		}

		//$query = "SELECT u_type, name, status FROM user";
		$this->mysqldb->query($query);
	
		$unamekey = 0;
		while($row = $this->mysqldb->fetchObject()) {
			if($uname[$unamekey-1] != $row->vid) $uname[$unamekey] = $row->vid;
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}
		return($uname);	
	}

	
	public function populateVoterTable($tablename, $residentsdecide) {
	
		$vlist = $this->getVoterList($residentsdecide);
		$datetime = time();
		foreach ($vlist as $userid) {
			
			$query = "insert into $tablename (voterid, vote, votecomment, datevoted)values('$userid', 'none', '', '$datetime')";

			//$query = "insert into $tablename (voterid, vote, votecomment)values('$userid', 'none', ' ', 0)";
			try {	
				$this->mysqldb->query($query);	
			} catch (Exception $e) {
				$msg = "Vote/populateVoterTable: Table population failed. ".$e->getMessage();
				$this->lf->logMessage($msg."\n");
				//die(echo "<p>Create table failed. ".$e->getMessage()."</p>");
			}
		} 
	}
}
?>
