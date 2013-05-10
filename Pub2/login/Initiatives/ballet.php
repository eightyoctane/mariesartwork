<?php
class ballet {
protected $mysqldb;
protected $querydone;

	public function __construct($lf) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."dbdata.php");
		include_once($rootlocation."login/mysqlClass.php");
		$this->mysqldb = new mysql($system,$dbuser,$dbpwd,$database);		
		$this->mysqldb->connect();
		$this->mysqldb->select();	
	
		$this->lf = $lf;
		//$this->lf->logMessage("ballet.php: Instantiating ballet\n");
		
		$this->querydone = 0;
	}

	
	public function createVoterTable($tablename) {	
		//voterid
		//vote (none, yes, no)
		//votecomment
		//datevoted
			
		$query = "CREATE TABLE IF NOT EXISTS $tablename (
					voterid SMALLINT(5) unsigned,
					vote VARCHAR(10),
					votecomment VARCHAR(200),
					datevoted INT(10) unsigned
					)";

		//$this->lf->logMessage($query."\n");
		//echo "<p>********query****** :</p><p>".$query."</p>";
		
		try {	
			$this->mysqldb->query($query);	
		} catch (Exception $e) {
			$msg = "Vote/createVoterTable: Table creation failed. ".$e->getMessage();
			$this->lf->logMessage($msg."\n");
			//die(echo "<p>Create table failed. ".$e->getMessage()."</p>");
		}
	
	}
	
	public function getBalletInfo($vtablename, $voterid, &$votet) {
		//echo "<p>*******in getBalletInfo vtablename: ".$vtablename."</p>";
		if( $this->aQualifiedVoter($vtablename, $voterid) ) {
			//echo "<p>**********getBallet voter qualified</p>";
			$this->mysqldb->query("select * from $vtablename where voterid='$voterid'");
			$votet['myvote'] = $this->mysqldb->getItem('vote');
			$votet['yes'] = 0;
			if( $votet['myvote'] === 'yes' ) $votet['yes'] = 1;
			$votet['no'] = 0;
			if( $votet['myvote'] === 'no' ) $votet['no'] = 1;
			$votet['comment'] = $this->mysqldb->getItem('votecomment');
			$votet['datevoted'] = $this->mysqldb->getItem('datevoted');
			return(TRUE);
		} else {
			//echo "<p>*********getBallet voter not qualified</p>";
			return(FALSE);
		}
	}
	
	
	public function castVote($vtablename, $voterid, $vote, $votecomment) {
	
		//echo "<p>*********** in castVote vtablename ".$vtablename."</p>";
	
		if(!$this->aQualifiedVoter($vtablename, $voterid)) {
			//echo "<p>**********not qualified</p>";
			$msg = "Sorry, you're not on the voting list for this item";		
			return;
		} else {
			//echo "<p>*********qualified vote is ".$vote."</p>";
			$this->mysqldb->query("select vote from $vtablename where voterid='$voterid'");
			$lastvote = $this->mysqldb->getItem('vote');
			$msg = "You voted ".$vote." (last vote was ".$lastvote.")";
			$now = time();
			$this->mysqldb->query("update $vtablename SET vote = '$vote', votecomment = '$votecomment', datevoted =$now WHERE voterid = '$voterid'");
		}
		throw new Exception($msg);
	}
	
	public function aQualifiedVoter($vtablename, $voterid) {
	
		//echo "<p>**************	in aQualifiedVoter vtablename ".$vtablename." voterid ".$voterid." </p>";
		
		$this->mysqldb->query("select voterid from $vtablename");
		//echo "<p>*****aQualified****completed query</p>";
		$qual=FALSE;
		while( ($row = $this->mysqldb->fetchObject()) ) {
			//echo "<p>******* a qual voterid from db: ".$row->voterid."<p>";
			if( $voterid == $row->voterid ) {
			  $qual = TRUE;
			  //echo "<p>****aQual voterid: ".$voterid." rowvoterid: ".$row->voterid." qual: ".$qual."</p>";
			}
		}
		//if($qual) echo "<p>*********voter is qualified</p>";
		return($qual);
	}	
	
	public function tallyVotingResults($vtablename, &$retarr) {

		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation."login/VisitorDataClass.php");
		$vd = new VisitorData($this->lf);
		
		//$s = "select * from $vtablename";
		$s = "select voterid, vote, votecomment, datevoted from $vtablename";	
	
		//print("<p>tallyVotingResults *******vtablename: ".$vtablename." query: ".$s."</p>");


		$sql = $this->mysqldb->query($s);
				
		//print("<p>tallyVotingResults *******collected **** rows: ".$this->mysqldb->numRows()."</p>");
				
		$yes = 0;
		$no = 0;
		$none = 0;
		$total = 0;
		//while($row = $this->mysqldb->fetchObject()) {
		
		$rowcount = 0;
		while($row = $this->mysqldb->fetchArray()) {
			//print "<p>row: ".$rowcount." voterid: ".$row['voterid']."</p>";
			$total = $total + 1;
			switch ($row['vote']) {
				case "none":
					$none = $none +1;
					break;
				case "yes":
					$yes = $yes + 1;
					break;
				case "no":
					$no = $no +1;
			}
			if ($row['votecomment'] == NULL) $row['votecomment'] = "none";
			$retarr['comments'] .= $vd->getUserName($row['voterid'])."|".$row['vote']."|".$row['votecomment']."|";			
		}
		$retarr['votesyes'] = $yes;
		$retarr['votesno'] = $no;
		$retarr['votestotal'] = $yes + $no;
		$retarr['votesnone'] = $none;
		$retarr['possible'] = $total;
		
				//Tally
		// A board vote has to be unanimous
		// A resident vote requires a quorum 30% min had to vote
		$votestotal = $yes + $sno + $none;
		if ( $vpercentage = (($yes + $no)/$votestotal)*10 < 30 ) {
			//echo "<p>********tallyVotingResults percentage voted ".$vpercentage."</p>";
			$retarr['vresult'] = "uncertainq";
		} else if ( $votesyes > $votesno ) {
			$retarr['vresult'] = "approved";
		} else if ( $votesyes == $votesno ) {
			$retarr['vresult'] = "tie";
		} else {
			$retarr['vresult'] = "rejected";
		}
			
	}
	
	public function nextVoterResults($vtablename, &$name) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		require_once($rootlocation."login/VisitorDataClass.php");
		//echo "<p>*******nextVoterResults</p>";
		$vd = new VisitorData($this->lf);
		if ( !$this->querydone ) {
			//echo "<p>****nextVoterResults doing query</p>";
			$sql = $this->mysqldb->query("select voterid, vote, votecomment, datevoted from $vtablename");
			$this->querydone = 1;
			if( !($row = $this->mysqldb->fetchObject()) ) {
				//echo "<p>****nextVoterResults no more data after query</p>";
				$this->querydone=0;
				return(0);
			}
		} else {
			if( !($row = $this->mysqldb->fetchObject()) ) {
				//echo "<p>****nextVoterResults no more data after fetchonly</p>";
				$this->querydone=0;
				return(0);
			}
		}
		$name = $vd->getUserName($row->voterid);
		if ($row->votecomment == NULL) $row->votecomment = "none";
		return($row);
		
	}
}
?>
