<?php
class Initiative {

	protected $lf;
	protected $mysqldb;
	protected $votingtable;
	protected $v;

	public function __construct($lf, $mysqldb) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		require_once($rootlocation."login/Initiatives/vote.php");
	
	
		$this->lf = $lf;
		
		$this->lf->logMessage("Initiative constructor starting\n");

		$this->mysqldb = $mysqldb;
		
		$this->v = new Vote($this->lf, $this->mysqldb);
		
		$this->updateIStatus();	

	}
	
	public function updateIStatus() {
/*
		This function runs through the initiative database, checking
		entries that are "open" status, to see if they should be "rejected",
		or "approved". We know this because we check to see if the voting
		time has expired
*/
		echo "<p>***************in Initiative constructor review status of open initiative</p>";		

		$query = "SELECT iname, iid, voteid, datetimeopened, submittedbynm, istatus FROM initiatives WHERE istatus='open'";
	
		$this->mysqldb->query($query);
		
		if( ($numrows=$this->mysqldb->numRows()) == 0) {
			echo "<p>**********updateiStatus no initiatives</p>";
			return(0);
		
		}
		
		while ($numrows--) {
			$row = $this->mysqldb->fetchObject();

			echo "<p>***************in Initiative constructor review status of open initiative ".$row->iname."</p>";		
	
			$today = time();
			$duration = $row->duration;
			echo "<p>*****duration: ".$duration."</p>";
			echo "<p>*****time and time opened ** ".$today."   ".$row->datetimeopened."</p>";
			$dayselapsed = (time() - $row->datetimeopened)/(60*60*24);
			$daysremaining = $duration - $dayselapsed;
			echo "<p>****dayselapsed ".$dayselapsed."</p>";
			echo "<p>****daysremaining ".$daysremaining."</p>";
			
			
			
			if($daysremaining <  0 ) {			
				//close out voting on this initative (tally votes and report results) 
				echo "<p> ********** in Initiative constructor voting has expired for initiative: ".$row->iname."</p>";
				
				$istatus = $this->v->closeVoting($row->voteid);
				$voteid = $row->voteid;
				//echo "<p>**********initiative updateIStatus: ".$istatus."</p>";
				$query = "update initiatives set istatus='$istatus' where voteid='$voteid'";
				$this->mysqldb->query($query);
			}

		}
	}
	
	public function openInitiative(&$valuearray) {
		
/*
		&$valuearray
		iname - initiative descriptive string
		descname - single word unique identifier
		shortdescription - 
		abstract
		proposalreq - minimum requirements each proposal should have
		vdeadline > duration of the current vote in days
		mechairman > 1 or 0 (1 is I want to be chairman)
		votetype > "board" or "residents"
		submittedbyid
		submittedbynm
		schairman - chairman suggested by the initiative initiator
		suggchairman - initiative initiator suggests a chairman
		residentsdecide - residents vote on the issue
*/	
/*
		Log the record sans the voteID (because we haven't created the vote entry yet
		This insertion will create an iid (initiative id) for us
		The call to getVoteID just returns a zero
		iname				iname
		shortdesc			shortdescription
		abstract			abstract
		submittedbynm		submittedbynm
		submittedbyid		submittedbyid
		volunteerc			mechairman
		chairrec			schairname
		resdecide			residentsdecide
		voteid				<<cant be loaded yet>>
		istatus				"open"
		duration			vdeadline
		treopened			not applicable yet
		whoreopened			not applicable yet
		descname			descname
		proposalreq			proposalreq
		datetimeopened		dateopen
		datetimereopened	not applicable yet
		
*/	
				
		// check for duplicate initiative entries in the database, if so, redisplay the input screen and show 
		// them the problem
		if( $this->mysqldb->duplicateEntryString($valuearray['descname'], "descname", "initiatives") ) {
			//duplicate identifier entry
			$msg = "openInitiative: Initiative entry insertion failed, duplicate initiative.\n";
			$this->lf->logMessage($msg);
			return (1);
		}		

/*
		//print_r($valuearray);
		//$q = "insert into initiatives(iname,descname)values(\'$valuearray['iname']\', \'$valuearray['descname']\')";
		

		//$this->mysqldb->query("insert into initiatives(iname,descname)values('$valuearray['iname']', '$valuearray['descname']')");
		//$this->mysqldb->query("insert into initiatives(iname,descname)values($valuearray['iname'], $valuearray['descname'])");
		//only way that works
		$this->mysqldb->query("insert into initiatives(iname,descname)values('$valuearray[iname]', '$valuearray[descname]')");

		//$this->mysqldb->query("insert into initiatives(iname,descname)values('$iname', '$descname')");
*/
		$this->mysqldb->query("insert into initiatives(".
					"iname, shortdesc, abstract, submittedbynm, submittedbyid, proposalreq, ".
					"volunteerc, chairrec, resdecide, voteid, istatus, duration, descname, datetimeopened) ".
					"values(".
					"'$valuearray[iname]', '$valuearray[shortdescription]', ".
					"'$valuearray[abstract]', '$valuearray[submittedbynm]', '$valuearray[submittedbyid]', ".
					"'$valuearray[proposalreq]', '$valuearray[mechairman]', '$valuearray[schairname]', ".
					"'$valuearray[residentsdecide]', '$valuearray[voteid]', '$valuearray[istatus]', '$valuearray[vdeadline]', ".
					"'$valuearray[descname]', '$valuearray[dateopen]')");

		$descname = $valuearray['descname'];
		$this->mysqldb->query("select iid from initiatives where descname='$descname'");
		$row = $this->mysqldb->fetchObject();
		$iid = $row->iid;
		
		$resd="board";
		if( $valuearray['residentsdecide'] ) $resd="residents";
		$vtablenamep = "initiative".$descname.$resd."vote";

		$this->lf->logMessage("openInitiative: voting table root: ".$vtablenamep."\n");
		//echo "<p>((((( voting table root is ".$vtablenamep."</p>";	

	
	
		// Create new entry for vote instance and a new voter table to record votes from this ballet
		
		// Add a new ballet
			//public function addVoteInstance($dateopened, $iorpid, $votetype, $vtablenamep, $duration, $residentsdecide) {

		switch( ($voteinstanceid = $this->v->addVoteInstance( $valuearray['dateopen'], $iid, "initiative", $vtablenamep, 
								$valuearray['vdeadline'], $valuearray['residentsdecide']) ) ) {
			//we got a duplicate voting instance id
			case 0:
				//duplicate voter instance return to caller so they can display the message on the form
				return(2);
			default:
				//got a nonzero value which is good, so continue on
		}
		
		echo "<p>**********************in openInitiative, the new vote instance is:".$voteinstanceid."</p>";

		// update our initiative with the new voting instance id (used later to access voting information)						
		$this->mysqldb->query("update initiatives set voteid='$voteinstanceid' where iid='$iid'");
						
								
/*	
		
		// Cast the first vote if the submitter is a legitamate voter
		//$v->castMyVote($valuearray['submittedbyid'], $iid, "yes");

*/

	}
	
}
?>