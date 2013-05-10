<?php
class Form {
	protected $dtext;
	protected $website;
	protected $faction;
	protected $sfaction;
	protected $hiddenval;
	protected $formname;
	protected $htmlpage;
	protected $checkboxscript;
	protected $textbox;
	protected $submit;
	protected $stext;
	protected $etext;
	protected $htmllistwhatissetmsg;
	protected $textarea;
	protected $checkbox;
	protected $ihiddenval;

	public function __construct($htmlpage) {
		$this->htmlpage = $htmlpage;
		//$this->htmlpage = "testpage.htm";
		
	}

	public function isetHiddenValue($id, $value) {
		$this->ihiddenval[$id] ="<input type=hidden name=$id value=$value>";
	}

	public function igetHiddenValue($name) {
		if ($_POST[$name] == NULL) {
			$this->isetHiddenValue($name,"");
			return(NULL);
		} else {
			$this->isetHiddenValue($name,$_POST[$name]);
			return($_POST[$name]);
		}
	}

	public function idisplayHiddenValue($name) {
		return($this->ihiddenval[$name]);
	}	

		
	public function setHiddenValue($id, $value) {
		$this->hiddenval .="<input type=hidden name=$id value=$value>";
		//$this->u_type = $value;
	}
	
	public function displayHiddenValues() {
		return($this->hiddenval);
	}
		
	public function setText($name, $text) {
		if( is_array($text) ) {	
			foreach($text as $textkey => $textvalue) {
				$this->dtext[$textkey] = $textvalue;
			}
		} else {
			$this->dtext[$name] = $text;
		}
	}

	public function displayText($name) {
		return($this->dtext[$name]);
	}

	public function displayDate($name) {
		return( date('r', $this->dtext[$name]) ); 
	}
	
	public function setHyperlink($id, $target, $disp) {
		if( $disp === 'new') {
			$this->hyperlink[$id] = '<a target="_blank" href="'.$target.'">'.$id.'</a>';
		} else {
			$this->hyperlink[$id] = '<a href="'.$target.'">'.$id.'</a>';
		}
	}
	
	public function displayHyperlink($id) {
		return($this->hyperlink[$id]);
	}


	public function setWebsite($ws) {
		$this->website = $ws;
		//echo "<p> website: $this->website </p>";
	}
	
	public function setFormName($fn) {
		$this->formname=$fn;
	}
	
	public function setEditCallBackPgm($fa) {
		$this->faction = $fa;
	}
	
	public function setSuccessCallBackPgm($sfa) {
		$this->sfaction = $sfa;
	}

	
	
	public function setCheckbox($name, $sense) {
		if( $sense == 1) {
			$this->checkbox[$name] = '<input type="checkbox" name="'.$name.'" value=1 checked>';
		} else {
			$this->checkbox[$name] = '<input type="checkbox" name="'.$name.'" value=0>';
		}
	}
	
	
	public function getCheckBox($name) {
		if (!isset($_POST[$name])) {
			//echo "**************".$name." is zero";
			$this->setCheckbox($name, 0);
			$retval = FALSE;
		} else {
			//echo "**************".$name." is one";
			$this->setCheckbox($name, 1);
			$retval = TRUE;
		}
		return($retval);
	}


	
	public function checkboxValue($name) {
		if (!isset($_POST[$name])) {
			//echo "**************".$name." is zero";
			$this->setCheckbox($name, 0);
			$retval = FALSE;
		} else {
			//echo "**************".$name." is one";
			$this->setCheckbox($name, 1);
			$retval = TRUE;
		}
		return($retval);
	}

	// this function called directly from the html form	
	public function displayCheckbox($name) {
		return($this->checkbox[$name]);
	}
	
		// this function called directly from the html form	
	public function displayTextBox($name) {
		return($this->textbox[$name]['html']);
	}
	
	
	public function getTextBox($name,$error) {
		if ($_POST[$name] == NULL) {
			$this->setTextBox($this->textbox[$name]['columns'],$name, "", $error);
			///$this->lf->logMessage("no name entry\n");
			return(NULL);
		} else {
			$this->setTextBox($this->textbox[$name]['columns'],$name, $_POST[$name], " ");
			//$this->lf->logMessage("getTextBox: entry name: ".$name."   value: ".$_POST[$name]."\n");
			return($_POST[$name]);
		}
	}
	

	public function setTextBox($columns, $name, $value, $errortext) {
		$this->textbox[$name] = array(	"columns" => $columns,
										"html" => '<input type="text" name="'.$name.'" size="'.$columns.'" value="'.$value.'">');
		$this->setSimpleErrorText($name, $errortext);
	}

	public function setSimpleText($name, $value) {
		$this->stext[$name] = '<font size="4" color="#FF0000"><span style="background-color: #FFFF00">'.$value.'</span></font>';
	}
	
	public function setSimpleErrorText($name, $value) {
		$this->etext[$name] = '<font size="2" color="#FF0000"><span style="background-color: #FFFF00">'.$value.'</span></font>';
	}

	// this function called directly from the html form	
	public function displaySimpleErrorText($name) {
		return ($this->etext[$name]);
	}

		// this function called directly from the html form	
	public function displaySimpleText($name) {
		return $this->stext[$name];
	}

	public function setTextArea($rows, $columns, $name, $value, $errortext) {
		$this->textarea[$name] = array(	"rows" => $rows,
										"columns" => $columns,
										"html" => '<textarea rows="'.$rows.'" name="'.$name.'" cols="'.$columns.'">'.$value.'</textarea>'
									);
		$this->setSimpleErrorText($name, $errortext);
	}

	// this function called directly from the html form	
	public function displayTextArea($name) {
		return($this->textarea[$name]['html']);
	}
	
	public function getTextArea($name,$error) {
		if ($_POST[$name] == NULL) {
			$this->setTextArea($this->textarea[$name]['rows'],$this->textarea[$name]['columns'],$name, "", $error);
			///$this->lf->logMessage("no name entry\n");
			return(NULL);
		} else {
			$this->setTextArea($this->textarea[$name]['rows'],$this->textarea[$name]['columns'],$name, $_POST[$name], " ");
			//$this->lf->logMessage("getTextArea: entry name: ".$name."   value: ".$_POST[$name]."\n");
			return($_POST[$name]);
		}
	}

	// this function called directly from the html form	
	public function displayListTextBox($retname) {
		return($this->htmllist[$retname]);
	}
	
	public function setNameListTextBox($retname, $boxarray, $indx) {
		if( $indx < 0 ) $indx = 0;
		$this->htmllistwhatissetmsg[$retname] = $indx;
		$this->iUpdateBoxSelection($retname,$boxarray,$boxarray,$indx,&$this->htmllist[$retname],&$this->htmllistwhatissetmsg[$retname]);
	}

	public function getNameListSelectedIndex($retname) {
		return($this->htmllistwhatissetmsg[$retname] = $_POST[$retname]);
	}

	protected function iUpdateBoxSelection($retname, $typepairs, $tmsgpairs, $type_received, &$box_html, &$tmsg) {
		$tmsg = $tmsgpairs[$type_received];
		$box_html = $this->iReturnBoxHtml(3, $retname, $typepairs, $type_received);
	}

	protected function iReturnBoxHtml($bsize, $returnname, $arrstring, $selectindex) {
		$size = sizeof($arrstring);
		
		$box_h = "<select size=\"".$bsize."\" name=\"".$returnname."\">";
	
		
		for($i=0; $i<$size; $i++) {
			
			if($i == ($selectindex)) {
				$selected = "selected ";
			} else {
				$selected = NULL;
			}
		$box_h .= 
		"<option ".$selected." value=\"".$arrstring[$i][0]."\">".$arrstring[$i][1]."</option>";
		}
		return($box_h);
	}





	public function renderFormText() {
		ob_start();
		include($this->htmlpage);
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}

//*******************************************************
	

	
	public function UpdateBoxSelection($lf, $retname, $typepairs, $tmsgpairs, $type_received, &$box_html, &$tmsg) {
	
	/*
		$size = sizeof($tmsgpairs);
		$lf->logMessage("UpdateBox: sizeof tmsgpairs> ".sizeof($tmsgpairs)."message rec: ".$type_received."\n");
		for($i=0; $i<$size; $i++) {
			$lf->logMessage("UpdateBoxSelection.php: message> ".$tmsgpairs[$i]."\n");
		}
	*/
		$tmsg = $tmsgpairs[$type_received];
	
		$lf->logMessage("UpdateBox: ".$tmsg."\n");
		
		$box_html = $this->ReturnBoxHtml($retname, $typepairs, $type_received);
		
		$lf->logMessage("UpdateBox html: ".$box_html."\n");
	
	}

	private function ReturnBoxHtml($returnname, $arrstring, $selectindex) {
		$size = sizeof($arrstring);
		
		$box_h = "<select size=\"".$size."\" name=\"".$returnname."\">";
	
		
		for($i=0; $i<$size; $i++) {
			
			if($i == ($selectindex)) {
				$selected = "selected ";
			} else {
				$selected = NULL;
			}
		$box_h .= 
		"<option ".$selected." value=\"".$arrstring[$i][0]."\">".$arrstring[$i][1]."</option>";
		}
		return($box_h);
	}
		

//*********************************************************************************************	

}

class rForm extends Form {
	protected $vidfield;
	protected $numvotes;
	protected $u_type;
	protected $upriv;
	protected $ascheckboxhtml;

	public function __construct($htmlpage) {
		parent::__construct($htmlpage);
	}


	public function setVIDField($value) {
		$this->vidfield = $value;
	}
	
	public function displayVoterID() {
		return($this->vidfield);
	}



//**************************************************************

	public function CheckNameValidity($name, &$msg) {
		// Is name set?
		if ($name == NULL) {
			$msg = "Name required. FIRST LAST or FIRST MI LAST. Please try again.";
			return(0);
		}
		// Is name alphanumeric?
		$narray = split("[ ]", $name);
		$parts = count($narray);
		if ( (!is_array($narray)) || ( $parts  < 2 )) {
			$msg = "Minimum FIRST LAST or FIRST MI LAST required. Please try again.";
			return(0);
			
		} else if ( $parts > 3 ) {
			$msg = "Maximum FIRST MI LAST required. Please try again.";
			return(0);
		
		} else {
			// We have names :-)
			for ($index=0; $index < ($parts); $index++) {
				$val = $narray[$index];
				//echo "next name: $val<br />";
				if ( !eregi("^[a-zA-Z]{1,40}$", $val) ) {
					$msg = "Name must be alpanumeric, FIRST LAST or FIRST MI LAST. Please try again.";
					return(0);
				}
			}
		}
		return(1);
	}
	
	public function CheckEmailValidity($name, &$msg) {
		if ( $name == NULL) {
			$msg = "Email address required. Please try again.";
			return(0);
		}
		// Good! We have an email address but we should do some mild checking
		// just to see if it has any chance of being valid
				
		if ( (! eregi("@",$_POST['email'] )) || (! eregi("\.",$_POST['email']) )) { 
			$msg = "Invalid email address. Please try again.";
			return(0);
		}
		return(1);	
	}
	
	



//*****************************************************************


// These render functions are associated with a specific html form containing
// the variables to be rendered	
	
		/* echo "no input detected." */
	//                    1     2       3       4      5    6   7    8      9
	// 10       11       12      13    14    15        16
	public function renderRform($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, $utypemsg, $uprivmsg) {
		
			
		//echo "<p> website: $this->website </p>";
//        10      11       12    13     14
		//echo "In renderRform, name=$n, username=$u, email=$e, password=$pwd, email=$e, apt=$aapt, st=$ast<br />";
		//echo "faction: $this->faction";
		// We do it this way so we can make small changes to the
		// form in MSFrontpage	
		include_once($this->htmlpage);
		//echo(include($this->htmlpage));
	}
	
	// this function renders forms for accountEdit where the additional datafile
	// is not required
	public function renderRformText($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, 
		$utypemsg, $uprivmsg) {
		ob_start();
		include($this->htmlpage);
		//$form = file_get_contents($this->htmlpage);	
		//echo $form;
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}
	
		// this function renders forms for ListAndSelectUsers and the webadmin has special fields
		// like account status
		// that need updating, where the additional datafile is not required 
	public function renderRformTextW($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, 
		$utypemsg, $uprivmsg) {
		ob_start();
		include($this->htmlpage);
		//$form = file_get_contents($this->htmlpage);	
		//echo $form;
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}

	// this function adds $vd which renders a datafile. This has to be used
	// with an html file that has this field
	public function renderRformTextD($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, 
		$utypemsg, $uprivmsg, $vd) {
		ob_start();
		include($this->htmlpage);
		//$form = file_get_contents($this->htmlpage);	
		//echo $form;
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}


}

class iForm extends Form {
	protected $submittedby;
	protected $htmllist;
	protected $inameemsg;
	protected $descnameemsg;
	protected $suggestchairman;
	protected $lf;
	protected $mysqldb;
	protected $iid;
	

	public function __construct($htmlpage,$lf,$mysqldb) {
		parent::__construct($htmlpage);
		$this->lf = $lf;
		
		$this->lf->logMessage("iForm constructor\n");

		$this->mysqldb = $mysqldb;
	}

	public function iSubmitButton($name) {
		//returns the iid of the first submitbutton pressed
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation."login/Initiatives/initiative.php");

		$in = new initiative($this->lf);
		// run down the list of initiatives we have open right now
		// this may be different from the time the user submitted, if
		// an initiative timed out before we got here - but that's ok
		// if the iniative closed, he can't vote on it anyway
		while( ($row = $in->nextInitiative('open'))  ) {
			$sbuttonname = $name.'-'.$row->iid;
			//echo "<p>**********iSubmitButton trying name: ".$sbuttonname."</p>";
			if( isset($_POST['$sbuttonname']) ) {
				echo "<p>*******iSubmitButton got ".$sbuttonname." </p>";
				return($row['iid']);
			}
		}
		//echo "<p>********iSubmit fell through returning FALSE</p>";
		return(FALSE);
	}

	public function idisplaySubmit($name) {
		$html = $this->submit['$name'];
		$this->lf->logMessage("idisplaySubmit: index: ".$name." html string: ".$html."\n");
		return( $this->submit[$name] );
	}
		
	public function isetSubmit($name, $sname, $value) {
		
		// $name is an iid value
		$encodedname=$sname."-".$name;
		$this->submit[$name] = '<input type="submit" value="'.$value.'" name="'.$encodedname.'">';
		$this->lf->logMessage("isetSubmit: index: ".$name." html string: ".$this->submit[$name]."\n");
	}
	
	
	public function setMemberNameSelectionBox($retname, $selectionindex) {
		// get the appropriate possible chairman member names from the database
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

	
		$query = "SELECT u_type, u_priv, name, status FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv<".$upriv['webadmin'];
		//$query = "SELECT u_type, name, status FROM user";
		$this->mysqldb->query($query);

		$uname[0] = array(0, "must be set");
		$unamekey = 1;
		while($row = $this->mysqldb->fetchObject()) {
			$uname[$unamekey] = array($unamekey, $row->name);
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}

		$this->setNameListTextBox($retname, $uname, $selectionindex-1);
	}
	
	
	public function getChairmanName($retname) {
		// get the appropriate possible chairman member names from the database

		$path = $_SERVER['DOCUMENT_ROOT']."/homedatapub2.php";
		//$this->lf->logMessage("sessionPageBrowser: constructor Starting path: ".$path."\n");	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

	
		$query = "SELECT u_type, u_priv, name, status FROM user WHERE u_type=".$utype['resident']." AND status='active' AND u_priv<".$upriv['webadmin'];
		//$query = "SELECT u_type, name, status FROM user";
		$this->mysqldb->query($query);
	
		$unamekey = 0;
		while($row = $this->mysqldb->fetchObject()) {
			$uname[$unamekey] = array($unamekey, $row->name);
			$unameonly[$unamekey] = $row->name;
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}
		$selectionindex = $this->getNameListSelectedIndex($retname);
		$selectionindex = $selectionindex - 1;
		$this->setNameListTextBox($retname, $uname, $selectionindex);
		return($unameonly[$selectionindex]);	
	}


	public function setInitiativeNameSelectionBox($retname, $selectionindex) {
		// get the appropriate possible open initiative names from the database
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation."login/Initiatives/initiative.php");

		echo "<p>*********setInitiativeIDFromSelectionBox**************</p>";


		$in = new Initiative($this->lf);
		try {	
			$row = $in->getOpenInitiatives();	
		} catch (Exception $e) {
			$uname[0] = array(0, $e->getMessage());
			$this->setNameListTextBox($retname, $uname, $selectionindex-1);
		}

		$uname[0] = array(0, "must be set");
		$unamekey = 1;
		while($row = $this->mysqldb->fetchObject()) {
			$uname[$unamekey] = array($unamekey, $row->name);
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}

		$this->setNameListTextBox($retname, $uname, $selectionindex-1);
	}

	public function getInitiativeIDFromSelectionBox() {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation."login/Initiatives/initiative.php");
		
		// here is the index provided by the user	
		$index = $this->getNameListSelectedIndex('initiative');
		
		// first get the indexed list from the database
		$in = new Initiative($this->lf);
		$row = $in->getOpenInitiatives();
		echo "<p>*********getInitiativeIDFromSelectionBox**************</p>";
		print_r($row);
		$iidkey=0;
		echo "<p>****** getInitiat... iid = ".$row[$index]."</p>";
		return($row[$index]);
	}
	

	public function inputComplete(&$retarr) {
		$refresh = FALSE;
			
		if (!($retarr['iname'] = $this->getTextArea('iname', "must be set"))) {
			$refresh = TRUE;
		} else {
			// NOTE need addnl processing here to insure legal name
			$retarr['iname'] = $this->getTextArea('iname', " ");
		}
		
		if (!($retarr['descname'] = $this->getTextArea('descname', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['descname'] = $this->getTextArea('descname', " ");
		}
		
		if (!($retarr['shortdescription'] = $this->getTextArea('shortdescription', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['shortdescription'] = $this->getTextArea('shortdescription', " ");
		}
	
		if (!($retarr['abstract'] = $this->getTextArea('abstract', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['abstract'] = $this->getTextArea('abstract', " ");
		}
		
		if (!($retarr['proposalreq'] = $this->getTextArea('proposalreq', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['proposalreq'] = $this->getTextArea('proposalreq', " ");
		}
		
		$retarr['residentsdecide'] = $this->checkBoxValue('residentsdecide'); 
		
		$retarr['suggchairman'] = $this->checkBoxValue('suggchairman'); 

		$retarr['doproposal'] = $this->checkBoxValue('doproposal'); 

		$retarr['mechairman'] = $this->checkBoxValue('mechairman'); 
						
		if (!($retarr['vdeadline'] = $this->getTextArea('vdeadline', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['vdeadline'] = $this->getTextArea('vdeadline', " ");
		}
		

		if (!$retarr['mechairman']) {
			if ($retarr['suggchairman']) {
				if ($this->getNameListSelectedIndex("chairindex") == 0 ) {
					$this->lf->logMessage("iForm/inputComplete: mechairman unset, suggchairman set, but chairindex is 0\n");					$this->setMemberNameSelectionBox("chairindex", 0);
					$this->setSimpleErrorText("chairindex", "must be set");
					$refresh = TRUE;
				} else {
					$retarr['schairname'] = $this->getChairmanName("chairindex");				}	
			} else {
			 	$retarr['schairname'] = "none";
			}

		} else {
			$retarr['mechairman'] = TRUE;
			$this->setMemberNameSelectionBox("chairindex", 0);		
			$retarr['suggchairman'] = FALSE;
			$retarr['schairname'] = "none";
		}

		$retval = TRUE;
		if($refresh) $retval = FALSE;
		return($retval);
	}


	public function populateDetailPage($iid) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/initiative.php");
		
		//echo "<p>**********populateDetailPage</p>";

		$in = new Initiative($this->lf);
		$this->setText('bogus', $in->getInitiative($iid));
		
		//echo "<p>************displayText iname **** ".$this->displayText('abstract')."</p>";
		//print_r($this->text);
		//echo "<p>************this->text ".$this->dtext['abstract']."</p>";
/*
		shortdesc
		abstract
		submittedbynm
		volunteerc
		chairrec
		resdecide
		istatus
		duration
		treopened
		whoreopened
		descname
		proposalreq
		datetimeopened
		datetimereopened
*/

		if( $this->dtext['resdecide'] ) {
			$this->setText('resdecidemsg', "Residents decide the outcome of this vote");
		} else {
			$this->setText('resdecidemsg', "Board members decide the outcome of this vote");
		}

		return($this->renderFormText());

	}
	
	public function putProperButton() {
		$button = $this->iid;
		$this->lf->logMessage("putProperButton: ".$button."\n");
		return ($button);	
	}
	
	public function returnInitiativeDetailTable($if) {
		
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/initiative.php");
		
		// instantiating initiative automatically closes any open initiatives where voting has expired
		$in = new Initiative($this->lf);
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
				
		$dT->addRow($dT->rCQ('Initiative Title').$dT->rCQ('Descriptive Name').$dT->rCQ('Submittal Date').$dT->rCQ('Submitter Name').$dT->rCQ('Status').$dT->rCQ('Proposals').
		$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
		
		$dT->resetCellText();
		
		$numinitiatives = 0;
		while($row = $in->nextInitiative('all')) {

			$numinitiatives++;	
			// We're actually rendering a form which was populated elsewhere (that population depended
			// on a context	
			
			// we also have to render a hidden field containing the initiative id, 
			// so that we can later vote on it.
			//echo "<p>****** iid ".$row->iid."</p>";
			//$if->isetHiddenValue("iid", $row->iid);
			
			$this->iid = $row->iid;
			$this->isetSubmit($row->iid, "submitbutton", "Vote");
			
			$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');
			
			if($row->istatus === "open") {
				$vformtext = $if->renderFormText();
				$this->lf->logMessage("returnInitiativeDetailTable: the vote submit table: ".$vformtext."\n");
			} else {
				$vformtext = "not applicable";
			}
			
			// Next embed our form inside a larger table and then return the html for the whole thing			
			
			$disp_row = array(	"iname" => $dT->rCQ($this->displayHyperlink($row->iname)), "descname" => $dT->rCQ($row->descname),
								"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
								"submittedbynm" => $dT->rCQ($row->submittedbynm), 
								"istatus" => $dT->rCQ($row->istatus), 
								"duration" => $dT->rCQ($row->duration));
									
			$truncatedate = $num = number_format($in->getDaysRemaining($row->datetimeopened, $row->duration), 2, '.', '');
			$dT->addRow($disp_row[iname].$disp_row[descname].$disp_row[datetimeopened].$disp_row[submittedbynm]
			.$disp_row[istatus].$dT->rCQ('proposals').$dT->rCQ($truncatedate).$dT->rCQ($vformtext));
		
		
		
		}
		
		//if(numinitiatives == 0) return("<p>There are no open initiatives at this time</p>");
		
		return($dT->renderTableText());
		
	}
	
	public function outputValues(&$valuearray) {
	
		$keyarray = array_keys(&$valuearray);
		$i=0;
		foreach($valuearray as $value) {
			echo "<p>".$keyarray[$i].'=>'.$value."</p>";
			$i++;
		}
		
	}
	
	public function testFields() {
		
		if (!($iname = $this->getTextArea('iname', "must be set"))) {
			echo "<p>initiative name not set</p>";
		} else {
			echo "<p>initiative name: ".$iname."</p>";
		}
		
		if (!($descname = $this->getTextArea('descname', "must be set"))) {
			echo "<p>descriptive name not set</p>";
		} else {
			echo "<p>descriptive name: ".$descname."</p>";
		}
		
		if (!($shortdescription = $this->getTextArea('shortdescription', "must be set"))) {
			echo "<p>short description not set</p>";
		} else {
			echo "<p>short description: ".$shortdescription."</p>";
		}
		
		if (!($abstract = $this->getTextArea('abstract', "must be set"))) {
			echo "<p>abstract not set</p>";
		} else {
			echo "<p>abstract: ".$abstract."</p>";
		}
		
		if (!($vdeadline = $this->getTextArea('vdeadline', "must be set"))) {
			echo "<p>vdeadline not set</p>";
		} else {
			echo "<p>vdeadline: ".$vdeadline."</p>";
		}

		
		if ($this->checkBoxValue('mechairman')) {
			echo "<p>mechairman set</p>";
		} else {
			echo "<p>mechairman not set</p>";
		}
		
		if ($this->checkBoxValue('doproposal')) {
			echo "<p>doproposal set</p>";
		} else {
			echo "<p>doproposal not set</p>";
		}

		if ($this->checkBoxValue('residentsdecide')) {
			echo "<p>residentsdecide set</p>";
		} else {
			echo "<p>residentsdecide not set</p>";
		}
		
		if ($this->checkBoxValue('suggchairman')) {
			echo "<p>suggchairman set</p>";

			if (!($schairname = $this->getTextArea('schairname', "must be set"))) {
				echo "<p>schairname not set</p>";
			} else {
				echo "<p>suggested chairman index: ".$schairname."</p>";
			}

		} else {
			echo "suggchairman not set</p>";
		}
	}
}

	
class pForm extends Form {
	protected $lf;
	protected $mysqldb;


	//proposal form

	public function __construct($htmlpage,$lf,$mysqldb) {
		parent::__construct($htmlpage);
		$this->lf = $lf;
		
		$this->lf->logMessage("pForm constructor\n");

		$this->mysqldb = $mysqldb;
	}

	
		public function inputComplete(&$retarr) {
		$refresh = FALSE;
			
		if (!($retarr['iname'] = $this->getTextArea('pname', "must be set"))) {
			$refresh = TRUE;
		} else {
			// NOTE need addnl processing here to insure legal name
			$retarr['pname'] = $this->getTextArea('pname', " ");
		}
		
		if (!($retarr['descname'] = $this->getTextArea('descname', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['descname'] = $this->getTextArea('descname', " ");
		}
		
		if (!($retarr['shortdesc'] = $this->getTextArea('shortdesc', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['shortdesc'] = $this->getTextArea('shortdesc', " ");
		}
							
		if (!($retarr['duration'] = $this->getTextArea('duration', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['duration'] = $this->getTextArea('duration', " ");
		}
		

		
		$retval = TRUE;
		if($refresh) $retval = FALSE;
		return($retval);
	}


	public function populateDetailPage($pid) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/proposal.php");
		
		//echo "<p>**********populateDetailPage</p>";

		$proposal = new Proposal($iid, $this->lf);
		$this->setText('bogus', $proposal->getProposal($pid));
		
		//echo "<p>************displayText iname **** ".$this->displayText('abstract')."</p>";
		//print_r($this->text);
		//echo "<p>************this->text ".$this->dtext['abstract']."</p>";
/*
		shortdesc
		abstract
		submittedbynm
		pstatus
		duration
		descname
		datetimeopened
*/


		return($this->renderFormText());

	}
	
	
		public function returnProposalDetailTable($if) {
		
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/initiative.php");
		
		// instantiating initiative automatically closes any open initiatives where voting has expired
		$in = new Proposal($this->lf);
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
				
		$dT->addRow($dT->rCQ('Proposal Title').$dT->rCQ('Descriptive Name').$dT->rCQ('Submittal Date').$dT->rCQ('Submitter Name').$dT->rCQ('Status').$dT->rCQ('Proposals').
		$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
		
		$dT->resetCellText();
		
		$numinitiatives = 0;
		while($row = $in->nextProposal()) {

			$numinitiatives++;	
			// We're actually rendering a form which was populated elsewhere (that population depended
			// on a context	
			
			// we also have to render a hidden field containing the initiative id, 
			// so that we can later vote on it.
			//echo "<p>****** iid ".$row->iid."</p>";
			$if->isetHiddenValue("iid", $row->iid);
			
			$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');
			
			if($row->istatus === "open") {
				$vformtext = $if->renderFormText();
			} else {
				$vformtext = "not applicable";
			}
			
			// Next embed our form inside a larger table and then return the html for the whole thing			
			
			$disp_row = array(	"iname" => $dT->rCQ($this->displayHyperlink($row->iname)), "descname" => $dT->rCQ($row->descname),
								"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
								"submittedbynm" => $dT->rCQ($row->submittedbynm), 
								"istatus" => $dT->rCQ($row->istatus), 
								"duration" => $dT->rCQ($row->duration));
									
			$truncatedate = $num = number_format($in->getDaysRemaining($row->datetimeopened, $row->duration), 2, '.', '');
			$dT->addRow($disp_row[iname].$disp_row[descname].$disp_row[datetimeopened].$disp_row[submittedbynm]
			.$disp_row[istatus].$dT->rCQ('proposals').$dT->rCQ($truncatedate).$dT->rCQ($vformtext));
		
		
		
		}
		
		//if(numinitiatives == 0) return("<p>There are no open initiatives at this time</p>");
		
		return($dT->renderTableText());
		
	}

	
	public function testFields($lf) {
	
		if (!isset($_POST['cname'])) {
			echo "<p>committee name not set</p>";
		} else {
			echo "<p>committee name: ".$_POST['cname']."</p>";
		}
		
		
		if (!isset($_POST['cchair'])) {
			echo "<p>committee chairman not set</p>";
		} else {
			echo "<p>committee chairman: ".$_POST['cchair']."</p>";
		}
		
	}
}

class bForm extends Form {

//Enter and manage the members of the board

	public function __construct($htmlpage) {
		parent::__construct($htmlpage);
	}
	
	public function testFields($lf) {
	
		if (!isset($_POST['president'])) {
			echo "<p>initiative name not set</p>";
		} else {
			echo "<p>president: ".$_POST['president']."</p>";
		}
		
		
		if (!isset($_POST['vpresident'])) {
			echo "<p>vice president not set</p>";
		} else {
			echo "<p>vice president: ".$_POST['vpresident']."</p>";
		}
		
		if (!isset($_POST['secretary'])) {
			echo "<p>secretary not set</p>";
		} else {
			echo "<p>secretary: ".$_POST['secretary']."</p>";
		}
				
		if (!isset($_POST['treasurer'])) {
			echo "<p>treasurer not set</p>";
		} else {
			echo "<p>treasurer: ".$_POST['treasurer']."</p>";
		}
		

	}





	public function renderRformText() {
		ob_start();
		include($this->htmlpage);
		//$form = file_get_contents($this->htmlpage);	
		//echo $form;
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}




}


class cForm extends Form {

	// enter and manage committee members

	public function __construct($htmlpage) {
		parent::__construct($htmlpage);
	}
	
	public function testFields($lf) {
	
		if (!isset($_POST['cname'])) {
			echo "<p>committee name not set</p>";
		} else {
			echo "<p>committee name: ".$_POST['cname']."</p>";
		}
		
		
		if (!isset($_POST['cchair'])) {
			echo "<p>committee chairman not set</p>";
		} else {
			echo "<p>committee chairman: ".$_POST['cchair']."</p>";
		}
		
	}

	public function renderRformText() {
		ob_start();
		include($this->htmlpage);
		//$form = file_get_contents($this->htmlpage);	
		//echo $form;
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}
}

?>
