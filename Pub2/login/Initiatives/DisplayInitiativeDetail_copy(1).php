<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
	
	require_once($rootlocation."login/libfunctions.php");
	include_once($rootlocation."login/mysqlClass.php");
	include_once($rootlocation."login/rFormClass.php");
	include_once($_SERVER['DOCUMENT_ROOT']."/dbdata.php");
	include_once($rootlocation.'logFileClass.php');
	include_once($rootlocation.'PresenterClass.php');
	require_once($rootlocation."login/VisitorDataClass.php");
	include_once($rootlocation."login/Initiatives/initiative.php");
	include($rootlocation."login/Initiatives/vote.php");
	include($rootlocation.'login/TableClass.php');


	
	$lf = new logFile();
	
	
	/* Connect to the MySQL server and select database. */
	$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
	$mysqldb->connect();
	$mysqldb->select();

		
	$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);
	$lf->logMessage("DisplayInitiative.php: root : ".$rootlocation."\n");	
	
	$lf->logMessage("DisplayInitiative.php: opening database\n");
		
	// get my user name from the user database using the id associated with this session
	$myid = $sp->getUserID();
	$mysqldb->query("SELECT rowID, name, vid FROM user WHERE rowID='$myid'");
	$row = $mysqldb->fetchObject();
	$myname = $row->name;
	$vid = $row->vid;
	//echo "<p>*********voter id  ".$vid."</p>";
	
	$if = new iForm("votingtable.htm", $lf, $mysqldb);
	
	$if->setWebsite($nameid);
	$if->setFormName('BMA Initiatives');
	$if->setEditCallBackPgm($homelocationpart.$homelocationsub.'login/Initiatives/DisplayInitiativeDetail.php');
	$if->setSuccessCallBackPgm($homelandingurlfull[$utype['resident']]);
	
	if (! ($iid = $if->iSubmitButton('submitbutton') ) ) {
		
		
		$lf->logMessage("DisplayInitiativeDetail.php: Rendering Table.\n");
		
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
		$dT->addRow( $dT->rCQ('Initiative management is a "remedial" process. In other words, while an initiative is open for voting, '.
		'you may vote on the initiative as many times as your opinion changes. Your opinion may change as a result of '.
		'discussions, or as the result of a new additional proposal, or for other reasons. This is why it is important '.
		'to include a comment with your vote. Your votes, and your comments are an important way to influence the outcome of initiatives '.
		'and proposals in a positive manner. If multiple proposals are available, be sure to reference a single proposal you support, '.
		'in your comment. <br>Once an initiative is closed, you will see the outcome as "approved", "rejected", or "uncertainq". Thirty '.
		'percent of the population must vote to achieve an "approved" or "rejected" voting status (this is the "quorum" definition in '.
		'the bylaws). If you see "uncertainq", it means that a quorum wasn\'t established during the voting period. Initiatives can be '.
		'reopened after they expire, but reopening an initiative must be approved by the board, and reopened by a board member.') );	
		
		$sp->addBodyText($dT->renderTableText());
		//$sp->addBodyText("<p>testing</p>");
	
		$sp->addBodyText( $if->returnInitiativeDetailTable($vid, 0, $if) );
		
		$sp->renderPage();
	
	} else {
	
		$lf->logMessage("DisplayInitiativeDetail.php: Received input.\n");

		$votecomment = $if->igetTextBox('votecomment',$iid, "");
		//echo "<p>*******DisplayInitiativeDetail votecomment ".$votecomment."</p>";		
		$vote['yes'] = $if->icheckBoxValue("yesvote", $iid);
		$vote['no'] = $if->icheckBoxValue("novote", $iid);
		if( $vote['yes'] && $vote['no']) {
			$if->isetSimpleErrorText("voteerror", $iid, "check only one box either yes or no");
			$if->setCheckbox("yesvote", $iid, 0);
			$if->setCheckbox("novote", $iid, 0);
			$sp->addBodyText($if->returnInitiativeDetailTable($vid, $iid, $if));	
			$sp->renderPage();
			exit(0);
		} else if( !$vote['yes'] && !$vote['no'] ) {
			$if->setSimpleErrorText("voteerror","we didn't get your vote please select yes or no");
			$if->setCheckbox("yesvote",0);
			$if->setCheckbox("novote",0);
			$sp->addBodyText($if->returnInitiativeDetailTable($vid, $iid, $if));	
			$sp->renderPage();
			exit(0);
		} else {
			// see if we're allowed to vote
			// cast our ballet
			$v = new Vote($lf);
			
			//$iid = $if->igetHiddenValue("iid");
			$iid = $if->getSubmit("submitbutton");
			//echo "<p>***********my voter id is ".$vid."</p>";

			echo "<p>***********submit value iid is ".$iid."</p>";
			try {	
				$v->castMyVote($vid, $iid, $vote, $votecomment);
			} catch (Exception $e) {
				//echo "<p>********got exception </p>";
				$if->setSimpleErrorText('voteerror', $e->getMessage());
				$sp->addBodyText($if->returnInitiativeDetailTable($if));	
				$sp->renderPage();
				exit(0);			
			}			
			
			exit(0);
		}

	
	}

?>