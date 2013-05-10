<?php
////////////////////////////////////////////////////////////////////////////
// calendar.php
// $Id: calendar.php 28 2006-04-01 23:38:13Z mootinator $
//
// ltwCalendar 'switchboard'
// This file is the one exposed to the web and has minimal functionality
// built in
////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////
// include the configuration file and core classes.
// note the classes file is included AFTER 'include_dir' is defined 
// by the config file.
///////////////////////////////////////////////////////////////////////

//Fix output buffering issues on some installations
ob_start();

require_once('./private/ltw_config.php');
require_once($ltw_config['include_dir'].'/ltw_classes.php');

//the following lines are added for backwards compatibility for pre 4.1.0 PHP versions..
if ( !isset($_POST)    ) $_POST    = $HTTP_POST_VARS;
if ( !isset($_REQUEST) ) $_REQUEST = $HTTP_GET_VARS;
//end backward compatibility

// Set the default up to a display of this month
if ( !isset($_REQUEST['display'])   ) $_REQUEST['display'] = "month";
if ( !isset($_REQUEST['timestamp']) ) $_REQUEST['timestamp'] = time();

validate_number($_REQUEST['timestamp'], 'timestamp');

switch( $_REQUEST['display'] ){
case 'admin':
	// All Admin tasks here
	switch( $_REQUEST['task'] ){
	case 'add':
		require_once($ltw_config['include_dir'].'/ltweventmgr.php');
		$event = new ltwEventMgr($_REQUEST['timestamp']); 
		if ( !empty($_REQUEST['date']) ) $event->add($_REQUEST['date']); else $event->add();
		break;

	case 'categories':
		require_once($ltw_config['include_dir'].'/ltwcatmgr.php');
		$cat = new ltwCatMgr;
		$cat->manage();
		break;

	case 'changepw':
		require_once($ltw_config['include_dir'].'/ltwpwmgr.php');
		$pw = new ltwPwMgr;
		$pw->manage();
		 break;

	case 'delete':
		require_once($ltw_config['include_dir'].'/ltweventmgr.php');
		$event = new ltwEventMgr($_REQUEST['timestamp']);
		$event->delete($_REQUEST['id']);
		break;
		
	case 'dbmaint':
		require_once($ltw_config['include_dir'].'/ltwdbmgr.php');
		$dbm = new ltwDbMgr;
		$dbm->manage();
		break;

	case 'edit':
		require_once($ltw_config['include_dir'].'/ltweventmgr.php');
		$event = new ltwEventMgr($_REQUEST['timestamp']);
		$event->edit($_REQUEST['id']);
		break;

	case 'login':
		$auth = new ltwAuth; 
		$auth->login();
		break;
				
	case 'logout':
		$auth = new ltwAuth; 
		$auth->logout();
		break;

	case 'logs':
		require_once($ltw_config['include_dir'].'/ltwlogmgr.php');
		$log = new ltwLogMgr;
		$log->display();
		break;

	case 'users':
		require_once($ltw_config['include_dir'].'/ltwusermgr.php');
		$users = new ltwUserMgr;
		$users->manage();
		 break;

	} //end admin switch($task)
	break;        
			
case 'day':
	require_once($ltw_config['include_dir'].'/ltwdisplayday.php');
	validate_number($_REQUEST['stamp'], 'stamp');
	validate_number($_REQUEST['day'], 'day');
	$cal = new ltwCalendar($_REQUEST['stamp']);
	$cal->displayDay($_REQUEST['day']);
	break;
		
case 'event':
	require_once($ltw_config['include_dir'].'/ltwdisplayevent.php');
	$cal = new ltwCalendar(time());
	validate_number($_REQUEST['id'], 'id');
	$cal->displayEvent($_REQUEST['id'],$_REQUEST['date']);
	break;

case 'list':
	require_once($ltw_config['include_dir'].'/ltwdisplaylist.php');

	// translate the input from the GET form to a start_date
	if ( isset($_REQUEST['year']) && isset($_REQUEST['month']) ){
		validate_number($_REQUEST['year'], 'year');
		validate_number($_REQUEST['month'], 'month');
		$_REQUEST['start_date'] = $_REQUEST['year'].'-'.$_REQUEST['month'].'-1';
		$_REQUEST['end_date']   = $_REQUEST['year'].'-'.$_REQUEST['month'].'-'.date('t',strtotime($_REQUEST['start_date']));
	}
	
	// this from a normal request
	if ( !isset($_REQUEST['start_date']) ) $_REQUEST['start_date'] = date('Y-n').'-1';
	if ( !isset($_REQUEST['end_date'])   ) $_REQUEST['end_date']   = date('Y-m').'-'.date('t',strtotime($_REQUEST['start_date']));
	
	$cal = new ltwCalendarList();
	$cal->displayList();
	break;

case 'pdf':
	require_once($ltw_config['include_dir'].'/ltwpdfmonth.php');
	if ( !isset($_REQUEST['month']) || $_REQUEST['month'] < 1 ||
	     !isset($_REQUEST['year'])  || $_REQUEST['month'] > 12 ) {
		$timestamp = time();
	}else{
		$timestamp = mktime(12,12,12,$_REQUEST['month'],1,$_REQUEST['year']);
		}
		$cal = new ltwPdfMonth($timestamp);
		$cal->displayMonth();
		break;
case 'month':
default:

	require_once($ltw_config['include_dir'].'/ltwdisplaymonth.php');
	
	// this from a normal request
	if ( !isset($_REQUEST['month']) || $_REQUEST['month'] < 1 ||
	     !isset($_REQUEST['year'])  || $_REQUEST['month'] > 12 ){
		$timestamp = time();
	}else{
		$timestamp = mktime(12,12,12,$_REQUEST['month'],1,$_REQUEST['year']);
	}
	$cal = new ltwCalendar($timestamp);
	$cal->displayMonth();
	break;
}//end switch

//Flush output buffer
ob_end_flush();

//Simple integer input validation
function validate_number($untrusted_input, $variable_name)
{
	if (!preg_match('/^\d{0,10}$/', trim($untrusted_input)))
	{
		die ("Unable to display calendar due to bad input :" . $variable_name . " -> " . htmlentities($untrusted_input));
	}
}

?>
