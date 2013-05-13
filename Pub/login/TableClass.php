<?php
class Table {
	var $starttable;
	var $tablecontent;
	var $endtable;
	var $bwidth;
	var $bsize;
	var $cpsize;
	var $fontin;
	var $fontout;
	var $boldin;
	var $boldout;
	var $italicin;
	var $italicout;
	
	function Table($bsize=0,$bwidth=100,$cpsize=1) {
	$this->bsize=$bsize;
	$this->bwidth=$bwidth;
	$this->cpsize=$cpsize;
	$this->setCellText();
	}
	
	function addRow($rcontent) {
		$this->tablecontent .= '<tr>'.$rcontent.'</tr>';
	}
	
	function rCQ($content) {
		// "Return Column Qualifier
		return '<td>'.$this->boldin.$this->italicin.$this->fontin.$content.$this->fontout.$this->italicout.$this->boldout.'</td>';
	}
	
	function setTableWidthPcnt($bwidth) {
		$this->bwidth = bwidth;
	}
	
	function setBoarderSize($bsize) {
		$this->bsize = $bsize;
	}
	
	function setCellText($face="arial",$fsize=1,$color='#000000',$bold=FALSE,$italic=FALSE) {
		$this->fontin = "<font face=$face size=$fsize color=$color>";
		$this->fontout = "</font>";
		if ($bold) {
			$this->boldin = "<b>";
			$this->boldout = "</b>";
		} else {
			$this->boldin = "";
			$this->boldout = "";
		}
		if ($italic) {
			$this->italicin = "<i>";
			$this->italicout = "</i>";
		} else {
			$this->italicin = "";
			$this->italicout = "";
		}
	}
	
	function resetCellText() {
		$this->setCellText();
	}
	
	
	function setCellPaddingSize($cpsize) {
		$this->cpsize = $cpsize;
	}
		
	function renderTableText() {
		ob_start();
		$this->starttable = '<table border="'.$this->bsize
		.'" width="'.$this->bwidth
		.'%" id="table1" cellpadding="'.$this->cpsize
		.'" style="border-collapse: collapse">';
		
		$this->endtable = '</table>';
		echo $this->starttable.$this->tablecontent.$this->endtable;		
		$buffer = ob_get_contents();
		ob_end_clean();
		return ($buffer); 
	}
	


}
?>
