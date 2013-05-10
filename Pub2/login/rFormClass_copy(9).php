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
	
	public function SubmitButton($name) {
		//echo "<p>**********Form/SubmitButton trying name: ".$name."</p>";
		if( isset($_POST['$name']) ) {
			//echo "<p>*******Form/SubmitButton got ".$name." </p>";
			return(TRUE);
		}
		//echo "<p>********FormSubmit fell through returning FALSE</p>";
		return(FALSE);
	}

	public function displaySubmit($name) {
		//$this->lf->logMessage("Form/displaySubmit: index: ".$name." html string: ".$this->submit[$name]."\n");
		return( $this->submit[$name] );
	}
		
	public function setSubmit($sname, $value) {		
		$this->submit[$sname] = '<input type="submit" value="'.$value.'" name="'.$sname.'">';
		//$this->lf->logMessage("Form/setSubmit: index: ".$sname." html string: ".$this->submit[$sname]."\n");
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
	}
	
	public function displayHiddenValues() {
		return($this->hiddenval);
	}


	public function getHiddenValue($name) {
		if ($_POST[$name] == NULL) {
			$this->setHiddenValue($name,"");
			return(NULL);
		} else {
			$this->setHiddenValue($name,$_POST[$name]);
			return($_POST[$name]);
		}
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
	
	
	public function getTextBox($col, $name,$error) {
		if ($_POST[$name] == NULL) {
			//$this->setTextBox($this->textbox[$name]['columns'],$name, "", $error);
			$this->setTextBox($col ,$name, "", $error);

			///$this->lf->logMessage("no name entry\n");
			return(NULL);
		} else {
			//$this->setTextBox($this->textbox[$name]['columns'],$name, $_POST[$name], " ");
			$this->setTextBox($col,$name, $_POST[$name], " ");

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
		//echo "<p>****setSimpleErrorText: ".$this->etext[$name]."</p>";
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
	
	public function getTextArea($rows, $cols, $name,$error) {
		if ($_POST[$name] == NULL) {
			//$this->setTextArea($this->textarea[$name]['rows'],$this->textarea[$name]['columns'],$name, "", $error);
			$this->setTextArea($rows,$cols,$name, "", $error);
			
			///$this->lf->logMessage("no name entry\n");
			return(NULL);
		} else {
			//$this->setTextArea($this->textarea[$name]['rows'],$this->textarea[$name]['columns'],$name, $_POST[$name], " ");
			$this->setTextArea($rows, $cols, $name, $_POST[$name], " ");

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
		
		//$this->lf->logMessage("iForm constructor\n");

		$this->mysqldb = $mysqldb;
	}
	
		
	
	public function isetCheckbox($name, $id, $sense) {
		parent::setCheckbox($this->iencodename($name, $id), $sense);
	}
	
	
	public function igetCheckBox($name, $id) {
		$name = $this->iencodename($name, $id);
		if (!isset($_POST[$name])) {
			//echo "**************".$name." is zero";
			parent::setCheckbox($name, 0);
			$retval = FALSE;
		} else {
			//echo "**************".$name." is one";
			parent::setCheckbox($name, 1);
			$retval = TRUE;
		}
		return($retval);
	}

	public function iencodename($name, $id) {
		return($name."-".$id);
	}

	
	public function icheckboxValue($name, $id) {
		$name = $this->iencodename($name, $id);
		if (!isset($_POST[$name])) {
			//echo "**************".$name." is zero";
			parent::setCheckbox($name, 0);
			$retval = FALSE;
		} else {
			//echo "**************".$name." is one";
			parent::setCheckbox($name, 1);
			$retval = TRUE;
		}
		return($retval);
	}

	// this function called directly from the html form	
	public function idisplayCheckbox($name, $id) {
		return( parent::displayCheckbox( $this->iencodename($name, $id) ) );
	}
	
		// this function called directly from the html form	
	public function idisplayTextBox($name, $id) {
		return( parent::displayTextBox( $this->iencodename($name, $id) ) );
	}
	
	
	public function igetTextBox($name, $id, $error) {
		return( parent::getTextBox( $this->iencodename($name, $id), $error) );
	}
	

	public function isetTextBox($columns, $name, $id, $value, $errortext) {
		parent::setTextBox($columns, $this->iencodename($name, $id), $value, $errortext);
	}

	public function isetSimpleText($name, $id, $value) {
		parent::setSimpleText($this->iencodename($name, $id), $value);
	}
	
	public function isetSimpleErrorText($name, $id, $value) {
		parent::setSimpleErrorText($this->iencodename($name, $id), $value);
	}

	// this function called directly from the html form	
	public function idisplaySimpleErrorText($name, $id) {
		return( parent::displaySimpleErrorText( $this->iencodename($name, $id) ) );
	}

		// this function called directly from the html form	
	public function idisplaySimpleText($name, $id) {
		return( parent::displaySimpleText( $this->iencodename($name, $id) ) );
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
			$sbuttonname = $this->iencodename($name, $row->iid);
			$this->lf->logMessage("iSubmitButton trying name: ".$sbuttonname."\n");
			//echo "<p>**********iSubmitButton trying name: ".$sbuttonname."</p>";
			if( isset($_POST[$sbuttonname]) ) {
				//$this->lf->logMessage("iSubmitButton got button name: ".$sbuttonname."\n");
				//echo "<p>*******iSubmitButton got ".$sbuttonname." </p>";
				return($row->iid);
			}
		}
		//echo "<p>********iSubmit fell through returning FALSE</p>";
		return(FALSE);
	}

	public function idisplaySubmit($name, $id) {
		$indx = $this->iencodename($name, $id);
		return(parent::displaySubmit($indx));
		//$html = $this->submit['$name'];
		//$this->lf->logMessage("iForm/idisplaySubmit: index: ".$name." html string: ".$html."\n");
		//return( $this->submit[$name] );
	}
		
	public function isetSubmit($sname, $name, $value) {
		
		// $name is an iid value
		$encodedname = $this->iencodename($sname, $name);
		parent::setSubmit($encodedname, $value);
		//$this->submit[$name] = '<input type="submit" value="'.$value.'" name="'.$encodedname.'">';
		//$this->lf->logMessage("iForm/setSubmit: index: ".$encodedname." html string: ".$this->submit[$encodedname]."\n");
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
			$emailonly[$unamekey] = $row->email;
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}
		$selectionindex = $this->getNameListSelectedIndex($retname);
		$selectionindex = $selectionindex - 1;
		$this->setNameListTextBox($retname, $uname, $selectionindex);
		$userdata['name'] = $unameonly[$selectionindex];
		$userdata['email'] = $emailonly[$selectionindex];
		return($userdata);	
	}


	public function setInitiativeNameSelectionBox($retname, $selectionindex) {
		// get the appropriate possible open initiative names from the database
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation."login/Initiatives/initiative.php");

		//echo "<p>*********setInitiativeIDFromSelectionBox**************</p>";


		$in = new Initiative($this->lf);
		$uname[0] = array(0, "must be set");
		$unamekey = 1;
		while( ($row = $in->nextInitiative('open') ) ) {
			//echo "<p> set name selection box: ".$row->iname."</p>";
			$uname[$unamekey] = array($unamekey, $row->iname);
			$encoded = "iid".$unamekey;
			$this->setHiddenValue('"'.$encoded.'"', $row->iid);
			//$lf->logMessage("name: ".$uname[$unamekey]."\n");
			$unamekey = $unamekey +1;
		}

		$this->setNameListTextBox($retname, $uname, $selectionindex-1);
	}

	public function getInitiativeIDFromSelectionBox() {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation."login/Initiatives/initiative.php");
		
		// here is the index provided by the user	
		$index = $this->getNameListSelectedIndex('initiatives');
		//echo "<p>****** getInitiativeIDFromSelecteonBox select index: ".$index."</p>";

		$iid = $this->getHiddenValue("iid".$index);
		//echo "<p>****** getInitiativeIDFromSelecteonBox iid: ".$iid."</p>";
		
		return($iid);
	}
	
	public function getInitiativeNameFromSelectionBox() {
	
		$iid = $this->getInitiativeIDFromSelectionBox();
		//echo "<p>*********getInitiativeNameFromSelectionBox iid: ".$iid."</p>";
		
		$in = new Initiative($this->lf);
		$row = $in->getInitiative($iid);
		return($row->iname);
	}
	
	
	public function initializeForm() {
	
		$this->setCheckbox(mechairman, TRUE);
		$this->setCheckbox(doproposal, FALSE);
		$this->setCheckbox(suggchairman, FALSE);
		$this->setCheckbox(residentsdecide, TRUE);
	
		$this->setTextBox(30, "iname", "", "*");
		$this->setTextBox(15, "descname", "", "*");
		$this->setTextArea(2, 29, "shortdescription", "", "*");
		$this->setTextArea(14, 29, "abstract", "", "*");
		$this->setTextArea(14, 29, "proposalreq","","*");
		$this->setTextBox(4, "vdeadline", "", "*");
		$this->setMemberNameSelectionBox("chairindex", 0);
	}


	public function inputComplete(&$retarr) {
		$refresh = FALSE;
			
		if (!($retarr['iname'] = $this->getTextBox(30, 'iname', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['descname'] = $this->getTextBox(30, 'descname', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['shortdescription'] = $this->getTextArea(2, 29, 'shortdescription', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['abstract'] = $this->getTextArea(14, 29, 'abstract', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['proposalreq'] = $this->getTextArea(14, 29, 'proposalreq', "must be set"))) $refresh = TRUE;
			
		$retarr['residentsdecide'] = $this->checkBoxValue('residentsdecide'); 
		
		$retarr['suggchairman'] = $this->checkBoxValue('suggchairman'); 

		$retarr['doproposal'] = $this->checkBoxValue('doproposal'); 

		$retarr['mechairman'] = $this->checkBoxValue('mechairman'); 
						
		if (!($retarr['vdeadline'] = $this->getTextArea(4, 'vdeadline', "must be set"))) $refresh = TRUE;

		if (!$retarr['mechairman']) {
			if ($retarr['suggchairman']) {
				if ($this->getNameListSelectedIndex("chairindex") == 0 ) {
					$this->lf->logMessage("iForm/inputComplete: mechairman unset, suggchairman set, but chairindex is 0\n");					$this->setMemberNameSelectionBox("chairindex", 0);
					$this->setSimpleErrorText("chairindex", "must be set");
					$refresh = TRUE;
				} else {
					$userdata = $this->getChairmanName("chairindex");
					$retarr['schairname'] = $userdata['name'];
					$retarr['schairemail'] = $userdata['email'];				}	
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
	
	public function initializeEditForm(&$inrow) {
		$this->setTextBox(30, "iname", $inrow['iname'], "*");
		$this->setTextArea(2, 29, "shortdescription", $inrow['shortdesc'], "*");
		$this->setTextArea(14, 29, "abstract", $inrow['abstract'], "*");
		$this->setTextArea(14, 29, "proposalreq",$inrow['proposalreq'],"*");
		$this->setTextBox(4, "vdeadline", $inrow['duration'], "*");	
	}
	
	

public function editInputComplete(&$retarr) {
		$refresh = FALSE;
			
		if (!($retarr['iname'] = $this->getTextBox(30, 'iname', "must be set"))) $refresh = TRUE;
				
		if (!($retarr['shortdesc'] = $this->getTextArea(2, 29, 'shortdescription', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['abstract'] = $this->getTextArea(14, 29, 'abstract', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['proposalreq'] = $this->getTextArea(14, 29, 'proposalreq', "must be set"))) $refresh = TRUE;
								
		if (!($retarr['vdeadline'] = $this->getTextBox(4, 'vdeadline', "must be set"))) $retval = TRUE;
		
		if($refresh) $retval = FALSE;
		return($retval);
	}





	public function returnVotingResultsTable($iid, $lf) {
	
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation."login/VisitorDataClass.php");
		include($rootlocation."login/Initiatives/initiative.php");
		include($rootlocation."login/Initiatives/ballet.php");
		include($rootlocation."login/Initiatives/vote.php");
		include($rootlocation.'login/TableClass.php');
		
		$in = new Initiative($lf);
		$irow = $in->getInitiative($iid);
		$this->setText('iname', $irow['iname']);
		$v = new Vote($lf);
		$b = new Ballet($lf);
		
		$row = $v->getVoteInfo($iid);
		$b->tallyVotingResults($row->vtablename, $votearray);
		$this->setText('votestotal', $votearray['votestotal']);
		$this->setText('possible', $votearray['possible']);
		if( $votearray['vresult'] === "uncertainq" ) {
			$this->setText('results', "uncertain");
		} else {
			$this->setText('results', $votearray['vresult']);
		}
		
		$this->setText('votesyes', $votearray['votesyes']);
		$this->setText('votesno', $votearray['votesno']);
	
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
		$dT->addRow( $dT->rCQ("Voter").$dT->rCQ("Vote").$dT->rCQ("Comment").$dT->rCQ("Date Voted") );
		$dT->resetCellText();		

		while( ($rowb = $b->nextVoterResults($row->vtablename, &$vname)) ) {
			$dT->addRow( $dT->rCQ($vname).$dT->rCQ($rowb->vote).$dT->rCQ($rowb->votecomment).$dT->rCQ( date('r', $rowb->datevoted)) ); 	
		}	
		
		$this->setText('votertable', $dT->renderTableText());
		
		return($this->renderFormText());
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
		//$this->lf->logMessage("putProperButton: ".$button."\n");
		return ($button);	
	}
	
	
	public function returnInitiativeDetailTable($myid, $mypriv, $vid, $iid, $if, $instatus='all', $format='long') {
		// if $iid is non zero it's the initiative just loaded from the screen
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/initiative.php");
		include_once($rootlocation."login/Initiatives/proposal.php");

		
		// instantiating initiative automatically closes any open initiatives where voting has expired
		$in = new Initiative($this->lf);
		$v = new Vote($this->lf);
		$ballet = new Ballet($this->lf);
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
		
		//$dT->addRow($dT->rCQ('BMA - Open Initiatives (patent pending)'));				
		$dT->addRow('Initiatives & Proposals - Gary Gross (patent pending)');
		$createlink = "Create an Initiative";
		$this->setHyperlink($createlink, $homelocationpart.$homelocationsub.'login/Initiatives/iForm.php', 'new');
		$dT->addRow($this->displayHyperlink($createlink));		

		switch( $format ) {
		case 'long':
			$dT->addRow($dT->rCQ('Initiative').$dT->rCQ('Descriptive Name').$dT->rCQ('Submittal Date').$dT->rCQ('Submitter Name').$dT->rCQ('Status').$dT->rCQ('Proposals').
			$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
			break;		
		case 'short':			
			$dT->addRow($dT->rCQ('Initiative').$dT->rCQ('Status').$dT->rCQ('Proposals').
			$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
		}	

		
		$dT->resetCellText();
		
		$numinitiatives = 0;
		while($row = $in->nextInitiative($instatus)) {

			$p = new Proposal($this->lf);

			$numinitiatives++;	
			// We're actually rendering a form which was populated elsewhere (that population depended
			// on a context	
						
			$this->isetSubmit("submitbutton", $row->iid, "Vote");
			
			$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');
			
			$statusdisp = $row->istatus;
			
			if($row->istatus === "open") {
				$linkname = $row->descname." current ballet results";
				$this->setHyperlink($linkname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowBalletDetail.php?iid='.$row->iid, 'new');
				$statusdisp = "open<br>".$this->displayHyperlink($linkname);	
				
/*
				$plinkname = "Proposals";
				if( $p->nextProposal($row->iid) ) {
					$this->setHyperlink($plinkname, $homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php?iid='.$row->iid, 'new');
				}
*/				

				$plinkname = "Proposals";
				if( $p->nextProposal($row->iid) ) {
					//echo "<p>******thinks there is a proposal for iid: ".$row->iid."</p>";
					$this->setHyperlink($plinkname, $homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php?iid='.$row->iid, 'new');
					$proposals = $this->displayHyperlink($plinkname);
				} else {
					//echo "<p>******no proposals for iid: ".$row->iid."</p>";
					$proposals = $plinkname;
				}	


		
	
				$nplinkname = "Add a Proposal";
				$ptype = "Initiative Proposal";
				$this->setHyperlink($nplinkname, $homelocationpart.$homelocationsub.'login/Initiatives/pForm.php?iid='.$row->iid.'&ptype='.$ptype, 'new');
				
				if( ($row->submittedbyid == $myid) || ($mypriv == $upriv[webadmin]) ) {
					//echo "<p>******should be editable initiative</p>";
					$eilinkname = "Edit";
					$this->setHyperlink($eilinkname, $homelocationpart.$homelocationsub.'login/Initiatives/iEditForm.php?iid='.$row->iid, 'new');				
				} 				
				$initiativecolumn = $this->displayHyperlink($row->iname).'<br>'.'['.$this->displayHyperlink($eilinkname).']';
				$proposalcolumn = $proposals.'<br><br>['.$this->displayHyperlink($nplinkname).']';
			
				// covers all open initiatives not updated from the screen
			
				if( ($iid != $row->iid) ) {
					
					$this->iid = $row->iid;
					$vrow = $v->getVoteInfo($row->iid);

					//echo "<p>*********returnInitiativeDetailTable initiatives not updated from the screen: ".$row->iid."</p>";
					
					// I may not be a qualified voter for this initiative
					
					if( !$ballet->getBalletInfo($vrow->vtablename, $vid, &$votet) ) {
						$vformtext = "not applicable, not a voter for this initiative";
						//echo "<p>********".$vformtext."</p>";
					} else {
						//echo "<p>******i'm a legal voter</p>";
						//print_r($votet);
						// since i am a legal voter, fill in what I voted last time
						$this->isetCheckbox("yesvote", $this->iid, $votet['yes']);		
						$this->isetCheckbox("novote", $this->iid, $votet['no']);
						$this->isetSimpleErrorText("voteerror", $row->iid, "this is how you voted last time");		
						if( ($votet['yes'] == 0) && ($votet['no'] == 0) ) $this->isetSimpleErrorText("voteerror", $row->iid, "please vote on this initiative");
						$this->isetTextBox(15,"votecomment", $row->iid, $votet['comment'],"");
						$vformtext = $this->renderFormText();
					}
					
					
				} else {
					
					// covers the initiative just voted from the screen
					
					$this->iid = $iid;
					
					$votecomment = $this->igetTextBox('votecomment',$iid, "");
					//echo "<p>*******DisplayInitiativeDetail votecomment ".$votecomment."</p>";		
					$vote['yes'] = $this->icheckBoxValue("yesvote", $iid);
					$vote['no'] = $this->icheckBoxValue("novote", $iid);
					if( $vote['yes'] && $vote['no']) {
						echo "<p>both boxes checked</p>";
						$this->isetSimpleErrorText("voteerror", $iid, "check only one box either yes or no");
						$this->isetCheckbox("yesvote", $iid, 0);
						$this->setCheckbox("novote", $iid, 0);
						$vformtext = $this->renderFormText();
					} else if( !$vote['yes'] && !$vote['no'] ) {
						echo "<p>neither box checked</p>";
						$this->isetSimpleErrorText("voteerror", $iid, "we didn't get your vote please select yes or no");
						$this->isetCheckbox("yesvote",0);
						$this->isetCheckbox("novote",0);
						$vformtext = $this->renderFormText();
					} else {
						//echo "<p> got a vote trying to cast ballet</p>";
						// cast our ballet
						$v = new Vote($lf);
						
						try {	
							$v->castMyVote($vid, $iid, $vote, $votecomment);
						} catch (Exception $e) {
							//echo "<p>********got exception </p>";
							$this->setSimpleErrorText('voteerror', $e->getMessage());
							$vformtext = $this->renderFormText();		
						}
					}			
				}			
			
			} else {
				$vformtext = "not applicable, initiative closed";
			}

			
			//$this->lf->logMessage("returnInitiativeDetailTable: the vote submit table: ".$vformtext."\n");
			
			// Next embed our form inside a larger table and then return the html for the whole thing			

			$truncatedate = $num = number_format($in->getDaysRemaining($row->datetimeopened, $row->duration), 2, '.', '');

			switch( $format ) {
			case 'long':			
				$disp_row = array(	"iname" => $dT->rCQ($initiativecolumn), "descname" => $dT->rCQ($row->descname),
									"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
									"submittedbynm" => $dT->rCQ($row->submittedbynm), 
									"istatus" => $dT->rCQ($statusdisp), 
									"proposals" => $dT->rCQ( $proposalcolumn ),
									"duration" => $dT->rCQ($truncatedate), "vote" => $dT->rCQ($vformtext) );
										
				
				$dT->addRow($disp_row[iname].$disp_row[descname].$disp_row[datetimeopened].$disp_row[submittedbynm]
				.$disp_row[istatus].$disp_row['proposals'].$disp_row['duration'].$disp_row['vote']);
				break;
			case 'short':			
			
				$disp_row = array(	"iname" => $dT->rCQ($initiativecolumn), "descname" => $dT->rCQ($row->descname),
									"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
									"submittedbynm" => $dT->rCQ($row->submittedbynm), 
									"istatus" => $dT->rCQ($statusdisp), 
									"proposals" => $dT->rCQ( $proposalcolumn ),
									"duration" => $dT->rCQ($truncatedate), "vote" => $dT->rCQ($vformtext) );
										
				
				$dT->addRow($disp_row[iname].$disp_row['istatus'].$disp_row['proposals'].$disp_row['duration'].$disp_row['vote']);
				break;						
			}		
		}
		
		//if(numinitiatives == 0) return("<p>There are no open initiatives at this time</p>");
		
		return($dT->renderTableText());
		
	}

public function returnShortInitiativeDetailTable($vid, $iid, $if, $instatus='all') {
		// if $iid is non zero it's the initiative just loaded from the screen
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/initiative.php");
		include_once($rootlocation."login/Initiatives/proposal.php");

		
		// instantiating initiative automatically closes any open initiatives where voting has expired
		$in = new Initiative($this->lf);
		$v = new Vote($this->lf);
		$ballet = new Ballet($this->lf);
		$p = new Proposal($this->lf);
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
				
		$dT->addRow($dT->rCQ('Initiative Title').$dT->rCQ('Proposals').
		$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
		
		$dT->resetCellText();
		
		$numinitiatives = 0;
		while($row = $in->nextInitiative($instatus)) {

			$numinitiatives++;	
			// We're actually rendering a form which was populated elsewhere (that population depended
			// on a context	
						
			$this->isetSubmit("submitbutton", $row->iid, "Vote");
			
			$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');
			
			$statusdisp = $row->istatus;
			
			if($row->istatus === "open") {
				$linkname = $row->descname." current ballet results";
				$this->setHyperlink($linkname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowBalletDetail.php?iid='.$row->iid, 'same');
				$statusdisp = "open - ".$this->displayHyperlink($linkname);	
				
/*
				$plinkname = "Proposals";
				if( $p->nextProposal($row->iid) ) {
					$this->setHyperlink($plinkname, $homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php?iid='.$row->iid, 'same');
				}
*/				

				$plinkname = "Proposals";
				if( $p->nextProposal($row->iid) ) {
					echo "<p>******thinks there is a proposal for iid: ".$row->iid."</p>";
					$this->setHyperlink($plinkname, $homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php?iid='.$row->iid, 'new');
					$proposals = $this->displayHyperlink($plinkname);
				} else {
					echo "<p>******no proposals for iid: ".$row->iid."</p>";
					$proposals = $plinkname;
				}			
	
				$nplinkname = "Add a Proposal";
				$ptype = "Initiative Proposal";
				$this->setHyperlink($nplinkname, $homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php?iid='.$row->iid, 'same');
				$this->setHyperlink($nplinkname, $homelocationpart.$homelocationsub.'login/Initiatives/pForm.php?iid='.$row->iid.'&ptype='.$ptype, 'same');

				$proposalcolumn = $this->displayHyperlink($plinkname).'<br>'.$this->displayHyperlink($nplinkname);
			
				// covers all open initiatives not updated from the screen
			
				if( ($iid != $row->iid) ) {
					
					$this->iid = $row->iid;
					$vrow = $v->getVoteInfo($row->iid);

					//echo "<p>*********returnInitiativeDetailTable initiatives not updated from the screen: ".$row->iid."</p>";
					
					// I may not be a qualified voter for this initiative
					
					if( !$ballet->getBalletInfo($vrow->vtablename, $vid, &$votet) ) {
						$vformtext = "not applicable, not a voter for this initiative";
						//echo "<p>********".$vformtext."</p>";
					} else {
						//echo "<p>******i'm a legal voter</p>";
						//print_r($votet);
						// since i am a legal voter, fill in what I voted last time
						$this->isetCheckbox("yesvote", $this->iid, $votet['yes']);		
						$this->isetCheckbox("novote", $this->iid, $votet['no']);
						$this->isetSimpleErrorText("voteerror", $row->iid, "this is how you voted last time");
						if( ($votet['yes'] == 0) && ($votet['no'] == 0) ) $this->isetSimpleErrorText("voteerror", $row->id, "please vote on this initiative");
						$this->isetTextBox(15,"votecomment", $row->iid, $votet['comment'],"");
						$vformtext = $this->renderFormText();
					}
					
					
				} else {
					
					// covers the initiative just voted from the screen
					
					$this->iid = $iid;
					
					$votecomment = $this->igetTextBox('votecomment',$iid, "");
					//echo "<p>*******DisplayInitiativeDetail votecomment ".$votecomment."</p>";		
					$vote['yes'] = $this->icheckBoxValue("yesvote", $iid);
					$vote['no'] = $this->icheckBoxValue("novote", $iid);
					if( $vote['yes'] && $vote['no']) {
						echo "<p>both boxes checked</p>";
						$this->isetSimpleErrorText("voteerror", $iid, "check only one box either yes or no");
						$this->isetCheckbox("yesvote", $iid, 0);
						$this->setCheckbox("novote", $iid, 0);
						$vformtext = $this->renderFormText();
					} else if( !$vote['yes'] && !$vote['no'] ) {
						echo "<p>neither box checked</p>";
						$this->isetSimpleErrorText("voteerror", $iid, "we didn't get your vote please select yes or no");
						$this->isetCheckbox("yesvote",0);
						$this->isetCheckbox("novote",0);
						$vformtext = $this->renderFormText();
					} else {
						//echo "<p> got a vote trying to cast ballet</p>";
						// cast our ballet
						$v = new Vote($lf);
						
						try {	
							$v->castMyVote($vid, $iid, $vote, $votecomment);
						} catch (Exception $e) {
							//echo "<p>********got exception </p>";
							$this->setSimpleErrorText('voteerror', $e->getMessage());
							$vformtext = $this->renderFormText();		
						}
					}			
				}			
			
			} else {
				$vformtext = "not applicable, initiative closed";
			}

			
			//$this->lf->logMessage("returnInitiativeDetailTable: the vote submit table: ".$vformtext."\n");
			
			// Next embed our form inside a larger table and then return the html for the whole thing			

			$truncatedate = $num = number_format($in->getDaysRemaining($row->datetimeopened, $row->duration), 2, '.', '');
			
			$disp_row = array(	"iname" => $dT->rCQ($this->displayHyperlink($row->iname)), "descname" => $dT->rCQ($row->descname),
								"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
								"submittedbynm" => $dT->rCQ($row->submittedbynm), 
								"istatus" => $dT->rCQ($statusdisp), 
								"proposals" => $dT->rCQ( $proposalcolumn ),
								"duration" => $dT->rCQ($truncatedate), "vote" => $dT->rCQ($vformtext) );
									
			
			$dT->addRow($disp_row[iname].$disp_row['proposals'].$disp_row['duration'].$disp_row['vote']);
		
		
		
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











class pjForm extends Form {
	protected $submittedby;
	protected $htmllist;
	protected $inameemsg;
	protected $descnameemsg;
	protected $lf;
	protected $mysqldb;
	protected $iid;
	

	public function __construct($htmlpage,$lf,$mysqldb) {
		parent::__construct($htmlpage);
		$this->lf = $lf;
		
		//$this->lf->logMessage("iForm constructor\n");

		$this->mysqldb = $mysqldb;
	}


	public function inputComplete(&$retarr) {
		$refresh = FALSE;
			
		if (!($retarr['pjname'] = $this->getTextBox('pjname', "must be set"))) {
			$refresh = TRUE;
		} else {
			// NOTE need addnl processing here to insure legal name
			$retarr['pjname'] = $this->getTextBox('pjname', " ");
		}
		
		if (!($retarr['descname'] = $this->getTextBox('descname', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['descname'] = $this->getTextBox('descname', " ");
		}
		
		if (!($retarr['shortdesc'] = $this->getTextArea('shortdesc', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['shortdesc'] = $this->getTextArea('shortdesc', " ");
		}
	
		if (!($retarr['abstract'] = $this->getTextArea('abstract', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['abstract'] = $this->getTextArea('abstract', " ");
		}
		
		if (!($retarr['filesdir'] = $this->getTextBox('filesdir', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['filesdir'] = $this->getTextBox('filesdir', " ");
		}
				
								
		$retval = TRUE;
		if($refresh) $retval = FALSE;
		return($retval);
	}

public function editInputComplete(&$retarr) {
		$refresh = FALSE;
			
		if (!($retarr['pjname'] = $this->getTextArea('pjname', "must be set"))) {
			$refresh = TRUE;
		} else {
			// NOTE need addnl processing here to insure legal name
			$retarr['pjname'] = $this->getTextArea('pjname', " ");
		}
				
		if (!($retarr['shortdesc'] = $this->getTextArea('shortdescription', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['shortdesc'] = $this->getTextArea('shortdescription', " ");
		}
	
		if (!($retarr['abstract'] = $this->getTextArea('abstract', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['abstract'] = $this->getTextArea('abstract', " ");
		}
				
		$retval = TRUE;
		if($refresh) $retval = FALSE;
		return($retval);
	}

	public function returnProjectDetailTable($myid, $mypriv, $vid, $iid, $if, $instatus='all', $format='long') {
		// if $iid is non zero it's the initiative just loaded from the screen
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/initiative.php");
		include_once($rootlocation."login/Initiatives/proposal.php");

		
		// instantiating initiative automatically closes any open initiatives where voting has expired
		$in = new Initiative($this->lf);
		$v = new Vote($this->lf);
		$ballet = new Ballet($this->lf);
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
		
		//$dT->addRow($dT->rCQ('BMA - Open Initiatives (patent pending)'));				
		$dT->addRow('Initiatives & Proposals - Gary Gross (patent pending)');
		$createlink = "Create an Initiative";
		$this->setHyperlink($createlink, $homelocationpart.$homelocationsub.'login/Initiatives/iForm.php', 'new');
		$dT->addRow($this->displayHyperlink($createlink));		

		switch( $format ) {
		case 'long':
			$dT->addRow($dT->rCQ('Initiative').$dT->rCQ('Descriptive Name').$dT->rCQ('Submittal Date').$dT->rCQ('Submitter Name').$dT->rCQ('Status').$dT->rCQ('Proposals').
			$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
			break;		
		case 'short':			
			$dT->addRow($dT->rCQ('Initiative').$dT->rCQ('Status').$dT->rCQ('Proposals').
			$dT->rCQ('Voting Days Left').$dT->rCQ('Vote on this Initiative'));
		}	

		
		$dT->resetCellText();
		
		$numinitiatives = 0;
		while($row = $in->nextInitiative($instatus)) {

			$p = new Proposal($this->lf);

			$numinitiatives++;	
			// We're actually rendering a form which was populated elsewhere (that population depended
			// on a context	
						
			$this->isetSubmit("submitbutton", $row->iid, "Vote");
			
			$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');
			
			$statusdisp = $row->istatus;
			
			if($row->istatus === "open") {
				$linkname = $row->descname." current ballet results";
				$this->setHyperlink($linkname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowBalletDetail.php?iid='.$row->iid, 'new');
				$statusdisp = "open<br>".$this->displayHyperlink($linkname);	
				

				$plinkname = "Proposals";
				if( $p->nextProposal($row->iid) ) {
					echo "<p>******thinks there is a proposal for iid: ".$row->iid."</p>";
					$this->setHyperlink($plinkname, $homelocationpart.$homelocationsub.'login/Initiatives/DisplayProposalDetail.php?iid='.$row->iid, 'new');
					$proposals = $this->displayHyperlink($plinkname);
				} else {
					echo "<p>******no proposals for iid: ".$row->iid."</p>";
					$proposals = $plinkname;
				}		
	
				$nplinkname = "Add a Proposal";
				$ptype = "Initiative Proposal";
				$this->setHyperlink($nplinkname, $homelocationpart.$homelocationsub.'login/Initiatives/pForm.php?iid='.$row->iid.'&ptype='.$ptype, 'new');
				
				if( ($row->submittedbyid == $myid) || ($mypriv == $upriv[webadmin]) ) {
					//echo "<p>******should be editable initiative</p>";
					$eilinkname = "Edit";
					$this->setHyperlink($eilinkname, $homelocationpart.$homelocationsub.'login/Initiatives/iEditForm.php?iid='.$row->iid, 'new');				
				} 				
				$initiativecolumn = $this->displayHyperlink($row->iname).'<br>'.'['.$this->displayHyperlink($eilinkname).']';
				$proposalcolumn = $proposals.'<br>['.$this->displayHyperlink($nplinkname).']';
			
				// covers all open initiatives not updated from the screen
			
				if( ($iid != $row->iid) ) {
					
					$this->iid = $row->iid;
					$vrow = $v->getVoteInfo($row->iid);

					//echo "<p>*********returnInitiativeDetailTable initiatives not updated from the screen: ".$row->iid."</p>";
					
					// I may not be a qualified voter for this initiative
					
					if( !$ballet->getBalletInfo($vrow->vtablename, $vid, &$votet) ) {
						$vformtext = "not applicable, not a voter for this initiative";
						//echo "<p>********".$vformtext."</p>";
					} else {
						//echo "<p>******i'm a legal voter</p>";
						//print_r($votet);
						// since i am a legal voter, fill in what I voted last time
						$this->isetCheckbox("yesvote", $this->iid, $votet['yes']);		
						$this->isetCheckbox("novote", $this->iid, $votet['no']);
						$this->isetSimpleErrorText("voteerror", $row->iid, "this is how you voted last time");		
						if( ($votet['yes'] == 0) && ($votet['no'] == 0) ) $this->isetSimpleErrorText("voteerror", $row->iid, "please vote on this initiative");
						$this->isetTextBox(15,"votecomment", $row->iid, $votet['comment'],"");
						$vformtext = $this->renderFormText();
					}
					
					
				} else {
					
					// covers the initiative just voted from the screen
					
					$this->iid = $iid;
					
					$votecomment = $this->igetTextBox('votecomment',$iid, "");
					//echo "<p>*******DisplayInitiativeDetail votecomment ".$votecomment."</p>";		
					$vote['yes'] = $this->icheckBoxValue("yesvote", $iid);
					$vote['no'] = $this->icheckBoxValue("novote", $iid);
					if( $vote['yes'] && $vote['no']) {
						echo "<p>both boxes checked</p>";
						$this->isetSimpleErrorText("voteerror", $iid, "check only one box either yes or no");
						$this->isetCheckbox("yesvote", $iid, 0);
						$this->setCheckbox("novote", $iid, 0);
						$vformtext = $this->renderFormText();
					} else if( !$vote['yes'] && !$vote['no'] ) {
						echo "<p>neither box checked</p>";
						$this->isetSimpleErrorText("voteerror", $iid, "we didn't get your vote please select yes or no");
						$this->isetCheckbox("yesvote",0);
						$this->isetCheckbox("novote",0);
						$vformtext = $this->renderFormText();
					} else {
						//echo "<p> got a vote trying to cast ballet</p>";
						// cast our ballet
						$v = new Vote($lf);
						
						try {	
							$v->castMyVote($vid, $iid, $vote, $votecomment);
						} catch (Exception $e) {
							//echo "<p>********got exception </p>";
							$this->setSimpleErrorText('voteerror', $e->getMessage());
							$vformtext = $this->renderFormText();		
						}
					}			
				}			
			
			} else {
				$vformtext = "not applicable, initiative closed";
			}

			
			//$this->lf->logMessage("returnInitiativeDetailTable: the vote submit table: ".$vformtext."\n");
			
			// Next embed our form inside a larger table and then return the html for the whole thing			

			$truncatedate = $num = number_format($in->getDaysRemaining($row->datetimeopened, $row->duration), 2, '.', '');

			switch( $format ) {
			case 'long':			
				$disp_row = array(	"iname" => $dT->rCQ($initiativecolumn), "descname" => $dT->rCQ($row->descname),
									"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
									"submittedbynm" => $dT->rCQ($row->submittedbynm), 
									"istatus" => $dT->rCQ($statusdisp), 
									"proposals" => $dT->rCQ( $proposalcolumn ),
									"duration" => $dT->rCQ($truncatedate), "vote" => $dT->rCQ($vformtext) );
										
				
				$dT->addRow($disp_row[iname].$disp_row[descname].$disp_row[datetimeopened].$disp_row[submittedbynm]
				.$disp_row[istatus].$disp_row['proposals'].$disp_row['duration'].$disp_row['vote']);
				break;
			case 'short':			
			
				$disp_row = array(	"iname" => $dT->rCQ($initiativecolumn), "descname" => $dT->rCQ($row->descname),
									"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
									"submittedbynm" => $dT->rCQ($row->submittedbynm), 
									"istatus" => $dT->rCQ($statusdisp), 
									"proposals" => $dT->rCQ( $proposalcolumn ),
									"duration" => $dT->rCQ($truncatedate), "vote" => $dT->rCQ($vformtext) );
										
				
				$dT->addRow($disp_row[iname].$disp_row['istatus'].$disp_row['proposals'].$disp_row['duration'].$disp_row['vote']);
				break;						
			}		
		}
		
		//if(numinitiatives == 0) return("<p>There are no open initiatives at this time</p>");
		
		return($dT->renderTableText());
		
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

	
	public function returnProposalDetailTable($iid) {
		
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include_once($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/proposal.php");
		
		$prop = new Proposal($this->lf);
		
		$dT = new Table();
		$dT->setTableWidthPcnt(100);
		$dT->setBoarderSize(1);
		$dT->setCellPaddingSize(7);		
		$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
				
		

		
		echo "<p>**********returnProposalDetailTable iid: ".$iid."</p>";
		if( $iid != 0 ) {
			
			$dT->addRow($dT->rCQ('Proposal Title').$dT->rCQ('Descriptive Name').$dT->rCQ('Submittal Date').$dT->rCQ('Submitter Name').$dT->rCQ('Status').$dT->rCQ('Duration (days)'));
			$dT->resetCellText();		
			while($row = $prop->nextProposal()) {	
				if( $iid == $row->iid && $row->pstatus == 'open' ) {
					// only print the open ones for this iid
					echo "<p>******displayProposalDetail found a proposal: ".$row->pname."</p>";
					$this->setHyperlink($row->pname, $homelocationpart.$homelocationsub.'login/Proposals/'.$row->filename, 'new');
					//$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');		
					
					$disp_row = array(	"pname" => $dT->rCQ($this->displayHyperlink($row->pname)), "descname" => $dT->rCQ($row->descname),
										"datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
										"submittedbynm" => $dT->rCQ($row->submittedbynm), "pstatus" => $dT->rCQ($row->pstatus), 
										"duration" => $dT->rCQ($row->duration));
											
					$dT->addRow($disp_row[pname].$disp_row[descname].$disp_row[datetimeopened].$disp_row[submittedbynm].$disp_row[pstatus].$disp_row[duration]);
				}
			}

		} else {
			
			$dT->addRow($dT->rCQ('Proposal Title').$dT->rCQ('Descriptive Name').$dT->rCQ('Description').$dT->rCQ('Submittal Date').$dT->rCQ('Submitter Name').$dT->rCQ('Status').$dT->rCQ('Duration (days)'));
			$dT->resetCellText();				
			while($row = $prop->nextProposal()) {	
				
				// print them all
				$this->setHyperlink($row->pname, $homelocationpart.$homelocationsub.'login/Proposals/'.$row->filename, 'new');
				//$this->setHyperlink($row->iname, $homelocationpart.$homelocationsub.'login/Initiatives/ShowInitiativeDetail.php?iid='.$row->iid, 'same');		
				
				$disp_row = array(	"pname" => $dT->rCQ($this->displayHyperlink($row->pname)), "descname" => $dT->rCQ($row->descname),
									"shortdesc" => $dT->rCQ($row->shortdesc), "datetimeopened" => $dT->rCQ( date('r', $row->datetimeopened) ), 
									"submittedbynm" => $dT->rCQ($row->submittedbynm), "pstatus" => $dT->rCQ($row->pstatus), 
									"duration" => $dT->rCQ($row->duration));
										
				$dT->addRow($disp_row[pname].$disp_row[descname].$disp_row[shortdesc].$disp_row[datetimeopened].$disp_row[submittedbynm].$disp_row[pstatus].$disp_row[duration]);
			}

		}
				
		return($dT->renderTableText());
		
	}

	public function populateDetailPage($pid) {
		include($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
		include($rootlocation.'login/TableClass.php');
		include_once($rootlocation."login/Initiatives/proposal.php");
		
		//echo "<p>**********populateDetailPage</p>";

		$prop = new Proposal($this->lf);
		$row = $prop->getProposal($pid);
		$this->setText('pname', $row['pname']);
		$this->setText('pstatus', $row['pstatus']);
		$this->setText('shortdesc', $row['shortdesc']);
		$this->setText('submittedbynm', $row['submittedbnm']);
		$this->setText('duration', $row['duration']);
		$this->setText('filename', $row['filename']);
		$this->setText('type', "");
		if( $row['iid'] != 0 ) $this->setText('type', "Initiative Proposal");
	
		return($this->renderFormText());

	}
	
	public function initializeForm() {
	
		$this->setTextBox(30,"pname", "", "*");
		$this->setTextBox(15, "descname", "", "*");
		$this->setTextArea(3, 40, "shortdesc", "", "*");
		$this->setTextBox(4, "duration", "", "*");
		$this->setSimpleErrorText("uploaded", "*");
	}


	public function inputComplete(&$retarr) {
	
		//echo "<p>******proposal/inputComplete</p>";
		
		$refresh = FALSE;
			
		if (!($retarr['pname'] = $this->getTextBox(30, 'pname', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['descname'] = $this->getTextBox(15, 'descname', "must be set"))) $refresh = TRUE;
		
		if (!($retarr['shortdesc'] = $this->getTextArea(3, 40, 'shortdesc', "must be set"))) $refresh = TRUE;

		if (!($retarr['duration'] = $this->getTextBox(4, 'duration', "must be set"))) $refresh = TRUE;

/*	REVISIT	
		if (!($retarr['uploaded'] = $this->getTextArea('uploaded', "must be set"))) {
			$refresh = TRUE;
		} else {
			$retarr['uploaded'] = $this->getTextArea('uploaded', " ");
		}
*/	
	
		$retval = TRUE;
		if($refresh) $retval = FALSE;
		return($retval);
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