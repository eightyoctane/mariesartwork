<?php

/* the profile editor is always invoked from another program, meaning
the rForm class was instantiated there the first time*/
include_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");
include_once($rootlocation."dbdata.php");

include_once($rootlocation."PresenterClass.php");	
include_once($rootlocation.'logFileClass.php');
require_once($rootlocation.'login/libfunctions.php');
require_once($rootlocation.'login/mysqlClass.php');
require_once($rootlocation.'login/rFormClass.php');
require_once($rootlocation.'login/TableClass.php');

$lf = new logFile();
$lf->logMessage("listResidents.php: Started\n");
$sp = new sessionPageBrowser($regionandheaderfull,$upriv['anyowner'],$homelandingurlfull[$utype['resident']]);	
$sp->addBodyContent($rootlocation.$staticcontentloc."listResidentsPFButton.htm");


/* Connect to the MySQL server and select database. */

$mysqldb = new mysql($system,$dbuser,$dbpwd,$database);
$mysqldb->connect();
$mysqldb->select();

$query = "SELECT rowID, name, username, status, phone, email, add_apt, add_street, add_city,".
" add_state, add_zip, add_country, u_type, u_priv, phone, vid FROM user ORDER BY name";

$mysqldb->query($query);

$numrows = $mysqldb->numRows();

$dT = new Table();
$dT->setTableWidthPcnt(100);
$dT->setBoarderSize(1);
$dT->setCellPaddingSize(7);

$dT->setCellText("arial",2,"#000000",TRUE,TRUE);
$dT->addRow($dT->rCQ('NAME').$dT->rCQ('EMAIL').$dT->rCQ('PHONE').$dT->rCQ('APT').$dT->rCQ('STREET').
$dT->rCQ('CITY').$dT->rCQ('STATE').$dT->rCQ('ZIP').$dT->rCQ('COUNTRY').$dT->rCQ('VID').$dT->rCQ('STATUS'));
$dT->resetCellText();

if (!$numrows) {
	$dT->addRow("No residents found in the database");
} else {

	$lf->logMessage("listResidents.php: Found ".$numrows." residents.\n");

	while ($numrows--) {
	
		$row = $mysqldb->fetchObject();
		
		#if($row->u_type > $utype['visitor'] AND $row->u_priv != $upriv['webadmin'] AND $row->status != "inactive") {
		if($row->u_type > $utype['visitor'] AND $row->u_priv != $upriv['webadmin']) {

		
			#$lf->logMessage("listResidents.php: ".$row->name."\n");

		
			$disp_row = array("name" => $dT->rCQ($row->name), "email" => $dT->rCQ($row->email), "add_apt" => $dT->rCQ($row->add_apt), 
			"add_street" => $dT->rCQ($row->add_street), "add_city" => $dT->rCQ($row->add_city),  "add_state" => $dT->rCQ($row->add_state),
			"add_zip" => $dT->rCQ($row->add_zip), "add_country" => $dT->rCQ($row->add_country), "phone" => $dT->rCQ($row->phone), 
			"vid" => $dT->rCQ($row->vid), "status" => $dT->rCQ($row->status)  );	
			
			$dT->addRow($disp_row[name].$disp_row[email].$disp_row[phone]
			.$disp_row[add_apt].$disp_row[add_street]
			.$disp_row[add_city].$disp_row[add_state]
			.$disp_row[add_zip].$disp_row[add_country]
			.$disp_row[vid].$disp_row[status]);
		}
	}
}

#$lf->logMessage("listResidents.php: The table:\n".$dT->renderTableText()."\n");
$lf->logMessage("listResidents.php: Rendering Table.\n");


$sp->addBodyText($dT->renderTableText());

$sp->renderPage();
?>
