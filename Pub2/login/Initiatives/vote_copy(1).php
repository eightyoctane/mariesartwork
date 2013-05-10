<?php
class Vote {
	protected $voteid;
	protected $lf;
	protected $mysqldb;

	public function __construct($lf, $mysqldb) {
		$this->lf = $lf;
		$this->mysqldb = $mysqldb;
		$this->lf->logMessage("Vote constructor: Instantiating Vote\n");
	}
	
	public function addBallet($dateopened, $iorpid, $votetype, $vtablenamep, $duration, $residentsdecide) {
	
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
		
		vstatus
		vtablename
		vdesc
		iorpid
		votetype
		datetimeopened
		duration		
*/
		$sql=$this->mysqldb->query("insert into vote(".
					"vstatus,iorpid,votetype,datetimeopened,duration)".
					"values('open','$iorpid','$votetype','$dateopened','$duration')");

		// Use the new id to insert the voting table name
		
		$voteinstance = $this->getVoteID();
		
		$vtablename = $vtablenamep."-".$voteinstance;
		
		
		$sql=$this->mysqldb->query("insert into vote(vtablename)values('$vtablename')");
		
		// Now we have the name of the voter table which we need to create
		
		// Finally, we need to create the new voter tabe and populate it with
		// the voters appropriate for this vote
/*		
		$this->createVoterTable($vtablename);
		$this->populateVoterTable($vtablename, $residentsdecide);
*/	

		return($voteinstance);
	}
	
		
	public function getVoteID($iorpid) {
		$this->mysqldb->query("SELECT voteid, iorpid FROM vote WHERE iorpid='$iorpid'");
		$row = $this->mysqldb->fetchObject();
		return($row->voteid);
	}


	
/*
	public function getVoterList($criteria) {
	
		// criteria = 1 return all residents
		// criteria = 0 return just board members	

		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		
		switch($criteria) {
			case 0:
				// get board members only
				$query = "SELECT rowID, u_type, u_priv FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv>".$upriv['commchair']." AND u_priv<".$upriv['webadmin'];
				break;
			case 1:
				// get all residents
				$query = "SELECT rowID, u_type, u_priv FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv<".$upriv['webadmin'];		
				break;	
		}

		//$query = "SELECT u_type, name, status FROM user";
		$this->mysqldb->query($query);
	
		$unamekey = 0;
		while($row = $this->mysqldb->fetchObject()) {
			$uname[$unamekey] = array($unamekey, $row->rowID);
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}
		return($uname);	
	}

	
	public function castMyVote($voterid, $iorpid, $vote) {
	
		// A number of things have to happen here
		// Locate the voter table using the iorpid
		// Insure that the ballet is still open
		// Insure that the voter is legitimate
	
		$message = "castMyVote: I havent been written yet";
		$this->lf->logMessage($message."\n");
		echo "<p>".$message."</p>";
	
	}
	
	public function createVoterTable($tablename) {	

		//voterid
		//vote (none, yes, no)
		//votecomment
		//datevoted
	
		
		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					voterid SMALLINT(5) unsigned,
					vote SMALLINT(5) unsigned,
					votecomment VARCHAR(200) not null,
					datevoted INT(10) unsigned
					)";

		$query = "bogus";
		$this->lf->logMessage($query"\n");
		
		try {							
			 $this->mysqldb->testquery($query);	
		} catch (Exception e) {	
			die(echo "Create table failed. ".$e->getMessage()."</p>");
		}
	
		$result = $mysqldb->getResult();

		if(! $result) {
			$msg = "voter instance table creation failed";
		} else {
			$msg = "voter instance table creation succeeded";
		}
		$this->lf->logMessage($msg."\n");
	
	}
	
	public function populateVoterTable($tablename, $residentsdecide) {
	
		$vlist = getVoterList($residentsdecide);

		foreach ($vlist as $userid) {
			$query = "insert into $tablename(".
			"voterid, vote, votecomment)".
			"values('$userid', 'none', '', 0)";
			$this->mysqldb->query($query);
		} 
	}
*/
}
?>

