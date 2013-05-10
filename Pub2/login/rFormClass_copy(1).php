<?php
class Form {
	var $website;
	var $faction;
	var $sfaction;
	var $hiddenval;
	var $formname;
	var $u_type;
	var $htmlpage;
	var $upriv;
	var $checkboxscript;

	function Form($htmlpage) {
		$this->hiddenval = "";
		$this->htmlpage = $htmlpage;
		//$this->htmlpage = "testpage.htm";
		
	}

	function setWebsite($ws) {
		$this->website = $ws;
		//echo "<p> website: $this->website </p>";
	}
	
	function setFormName($fn) {
		$this->formname=$fn;
	}
	
	function setEditCallBackPgm($fa) {
		$this->faction = $fa;
	}
	
	function setSuccessCallBackPgm($sfa) {
		$this->sfaction = $sfa;
	}
	
	function setHiddenValue($id, $value) {
		$this->hiddenval .="<input type=hidden name=$id value=$value>";
		$this->u_type = $value;
	}
	
	function setCheckBox($id, $value) {
		if($value==1) {
			$default = "checked";
		} else {
			$value = 1;
			$default = '';
		}
		$this->checkboxscript = '<input type="checkbox" name="'.$id.'" value="'.$value.'" '.$default.'>';
	}
	
	function getCheckBox() {
		return $this->checkboxscript;
	}

	function renderFormText() {
		ob_start();
		include($this->htmlpage);
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}

}

class rForm extends Form {
	
	
		/* echo "no input detected." */
	//                    1     2       3       4      5    6   7    8      9
	// 10       11       12      13    14    15        16
	function renderRform($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, $utypemsg, $uprivmsg, $enotify) {
		
			
		//echo "<p> website: $this->website </p>";
//        10      11       12    13     14
		//echo "In renderRform, name=$n, username=$u, email=$e, password=$pwd, email=$e, apt=$aapt, st=$ast<br />";
		//echo "faction: $this->faction";
		// We do it this way so we can make small changes to the
		// form in MSFrontpage	
		include_once($this->htmlpage);
		//echo(include($this->htmlpage));
	}
	
	function renderRformText($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, 
		$utypemsg, $uprivmsg, $enotify) {
		ob_start();
		include($this->htmlpage);
		//$form = file_get_contents($this->htmlpage);	
		//echo $form;
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer);
	}
	
		function renderRformTextD($nmsg, $umsg, $pwdmsg, $emsg, $n, $u, $e, $aapt, $ast, 
		$acity, $astate, $azip, $acou, $ph, $utypehtml, $uprivhtml, 
		$utypemsg, $uprivmsg, $enotify, $vd) {
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
