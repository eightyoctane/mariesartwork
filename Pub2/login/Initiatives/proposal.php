<?php
class Proposal {

	protected $lf;
	protected $mysqldb;
	protected $votingtable;
	protected $v;
	protected $querydone;
	protected $daysremaining;

	public function __construct($lf) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."dbdata.php");
		include_once($rootlocation."login/mysqlClass.php");
		$this->mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
		require_once($rootlocation."login/Initiatives/vote.php");
		
		$this->mysqldb->connect();
		$this->mysqldb->select();	
	
		$this->lf = $lf;
		
		$this->lf->logMessage("Proposal constructor starting\n");
		
		//$this->v = new Vote($this->lf);
		
		$this->updatePStatus();	
		
		$this->querydone = 0;
	}
	
	public function getDaysRemaining($datetimeopened, $duration) {
			$today = time();
			$durations = $duration*60*60*24;
			//echo "<p>*****durations: ".$durations."</p>";
			//echo "<p>*****time and time opened ** ".$today."   ".$row->datetimeopened."</p>";
			$sece = time() - $datetimeopened;
			
			//echo "<p>******seconds elapsed ".$sece."</p>";
			
			$secremaining = $durations - $sece;
			$daysremaining = $secremaining/(60*60*24);
			//echo "<p>****daysremaining ".$this->daysremaining."</p>";
		return($daysremaining);
	}
	
	public function updatePStatus() {
/*
		This function runs through the proposal table, checking
		entries that are "open" status, to see if they should be "rejected",
		or "approved". We know this because we check to see if the voting
		time has expired
*/
		//echo "<p>***************in Initiative constructor review status of open initiative</p>";		

		$query = "SELECT pname, pid, voteid, datetimeopened, submittedbynm, duration, pstatus FROM proposals WHERE pstatus='open'";
	
		$this->mysqldb->query($query);
		
		if( ($numrows=$this->mysqldb->numRows()) == 0) {
			//echo "<p>**********updatepStatus no proposals</p>";
			return(0);
		
		}
/*		
		while ($numrows--) {
			$row = $this->mysqldb->fetchObject();		
			if($this->getDaysremaining($row->datetimeopened, $row->duration) <  0 ) {			
				//close out voting on this initative (tally votes and report results) 
				//echo "<p> ********** in Initiative constructor voting has expired for initiative: ".$row->iname."</p>";
				
				$pstatus = $this->v->closeVoting($row->voteid);
				$voteid = $row->voteid;
				//echo "<p>**********initiative updateIStatus: ".$istatus."</p>";
				$query = "update proposals set pstatus='$pstatus' where voteid='$voteid'";
				$this->mysqldb->query($query);
			}

		}
*/
		
	}
	
	public function openProposal($iid, &$valuearray) {
		
/*
		&$valuearray
		pname - initiative descriptive string
		descname - single word unique identifier
		shortdesc - 
		duration > duration of the current vote in days
		submittedbyid
		submittedbynm
*/	
/*
		Log the record sans the voteID (because we haven't created the vote entry yet
		This insertion will create a pid (proposal id) for us
		The call to getVoteID just returns a zero
		pname				pname
		shortdesc			shortdescription
		submittedbynm		submittedbynm
		submittedbyid		submittedbyid
		voteid				<<cant be loaded yet>>
		pstatus				"open"
		duration			vdeadline
		descname			descname
		datetimeopened		dateopen		
*/	
				
		// check for duplicate proposal entries in the database, if so, redisplay the input screen and show 
		// them the problem
		if( $this->mysqldb->duplicateEntryString($valuearray['descname'], "descname", "proposals") ) {
			//duplicate identifier entry
			$msg = "openProposal: Proposal entry insertion failed, duplicate proposal.\n";
			$this->lf->logMessage($msg);
			return (1);
		}		
		//echo "<p>******** openProposal attempting to install new proposal entry into database</p>";
		//print_r($valuearray);
		
		$this->mysqldb->query("insert into proposals(".
					"pname, iid, shortdesc, submittedbynm, submittedbyid, ".
					"voteid, pstatus, duration, descname, datetimeopened, filename) ".
					"values(".
					"'$valuearray[pname]', $iid, '$valuearray[shortdesc]', ".
					"'$valuearray[submittedbynm]', '$valuearray[submittedbyid]', ".
					"'$valuearray[voteid]', '$valuearray[pstatus]', '$valuearray[duration]', ".
					"'$valuearray[descname]', '$valuearray[dateopen]', '$valuearray[filename]')");

		$descname = $valuearray['descname'];
		$this->mysqldb->query("select pid from proposals where descname='$descname'");
		$row = $this->mysqldb->fetchObject();
		$pid = $row->pid;
/*
		$resd="residents";
		$vtablenamep = "proposal".$descname.$resd."vote";

		$this->lf->logMessage("openProposal: voting table root: ".$vtablenamep."\n");
		//echo "<p>((((( voting table root is ".$vtablenamep."</p>";	

	
	
		// Create new entry for vote instance and a new voter table to record votes from this ballet
		
		// Add a new ballet
			//public function addVoteInstance($dateopened, $iorpid, $votetype, $vtablenamep, $duration, $residentsdecide) {

		switch( ($voteinstanceid = $this->v->addVoteInstance( $valuearray['dateopen'], $iid, "proposal", $vtablenamep, 
								$valuearray['vdeadline'], $valuearray['residentsdecide']) ) ) {
			//we got a duplicate voting instance id
			case 0:
				//duplicate voter instance return to caller so they can display the message on the form
				return(2);
			default:
				//got a nonzero value which is good, so continue on
		}
		
		//echo "<p>**********************in openInitiative, the new vote instance is:".$voteinstanceid."</p>";

		// update our initiative with the new voting instance id (used later to access voting information)						
		$this->mysqldb->query("update initiatives set voteid='$voteinstanceid' where iid='$iid'");
						
								
		// Cast the first vote if the submitter is a legitamate voter
		$v = new Vote($this->lf);
		$vote['yes']=1;
		$v->castMyVote($valuearray['submittedbyid'], $iid, $vote['yes'], "I originated the initiative");

*/

	}
	
	public function nextProposal($iid=0) {
		
		$query = "SELECT * FROM proposals where iid='$iid' ORDER BY datetimeopened";
	
		if( !$iid) {
			$query = "SELECT * FROM proposals ORDER BY datetimeopened";
		}	

		if ( !$this->querydone ) {

			$this->mysqldb->query($query);
			$this->querydone = 1;
			
			if( !($row = $this->mysqldb->fetchObject()) ) {
				//echo "<p>****nextProposal no more data after query</p>";
				$this->querydone=0;
				return(0);
			}
				
		} else {
			if( !($row = $this->mysqldb->fetchObject()) ) {
				//echo "<p>****nextProposal no more data after fetchonly</p>";
				$this->querydone=0;
				return(0);
			}
		}
		return($row);
	}
	
	public function getProposal($pid) {
			$query = "SELECT * FROM proposals WHERE pid=$pid";
			$this->mysqldb->query($query);
			if($numrows = $this->mysqldb->numRows() == 0) return(0);
			return($this->mysqldb->fetchArray());
	}
	
}
?>
