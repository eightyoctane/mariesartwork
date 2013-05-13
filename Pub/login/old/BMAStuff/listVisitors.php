<?php

/* the profile editor is always invoked from another program, meaning
the rForm class was instantiated there the first time*/
session_start();

require_once("libfunctions.php");
include_once("mysqlClass.php");
include_once("rFormClass.php");
include_once("../logFileClass.php");
include_once("sPageClass.php");
include_once("sessionSetup.php");
include_once("TableClass.php");
	
	
$lf = new logFile();
	
$sp = new sPage("../bma_RegionAndHeader.htm");
	
sessionSetup($sp,$lf,2,1,"por.php");



$sp->addMenu("porMenu.html");	

$_SESSION['prevpage'] = $_SESSION['homepage'];	

$sp->addHtmlText("listResidentsPFButton.htm");


/* Connect to the MySQL server and select database. */

$mysqldb = new mysql("localhost","bluemov2_vadmin","1200", "bluemov2_visitors");
$mysqldb->connect();
$mysqldb->select();

$query = "SELECT rowID, name, username, phone, email, add_apt, add_street, add_city,".
" add_state, add_zip, add_country, u_type, u_priv, phone FROM user ORDER BY name";

$mysqldb->query($query);

$numrows = $mysqldb->numRows();

$dT = new Table();
$dT->setTableWidthPcnt(100);
$dT->setBoarderSize(1);
$dT->setCellPaddingSize(7);

$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
$dT->addRow($dT->rCQ('NAME').$dT->rCQ('EMAIL').$dT->rCQ('PHONE').$dT->rCQ('APT').$dT->rCQ('STREET').
$dT->rCQ('CITY').$dT->rCQ('STATE').$dT->rCQ('ZIP').$dT->rCQ('COUNTRY'));
$dT->resetCellText();

while ($numrows--) {

	$row = $mysqldb->fetchObject();
	
	if($row->u_type <= 1) {
	
		$disp_row = array("name" => $dT->rCQ($row->name), "email" => $dT->rCQ($row->email), "add_apt" => $dT->rCQ($row->add_apt), 
		"add_street" => $dT->rCQ($row->add_street), "add_city" => $dT->rCQ($row->add_city),  "add_state" => $dT->rCQ($row->add_state),
		"add_zip" => $dT->rCQ($row->add_zip), "add_country" => $dT->rCQ($row->add_country), "phone" => $dT->rCQ($row->phone) );	
		
		$dT->addRow($disp_row[name].$disp_row[email].$disp_row[phone]
		.$disp_row[add_apt].$disp_row[add_street]
		.$disp_row[add_city].$disp_row[add_state]
		.$disp_row[add_zip].$disp_row[add_country]);
	}
}

//$lf->logMessage($dT->renderTableText());

$sp->addBodyText($dT->renderTableText());

$sp->renderSessionPage();
