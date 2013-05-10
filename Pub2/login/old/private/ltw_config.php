<?php
////////////////////////////////////////////////////////////////////////////
// ltw_config.php
// $Id: ltw_config.php,v 1.1 2010/12/07 10:44:22 bluemov2 Exp bluemov2 $
//
// ltwCalendar Configuration File
////////////////////////////////////////////////////////////////////////////


// 'include_dir' defines the file system path to the directory that contains
// the .php scripts needed for the calendar.  This 'logically' separates the
// configuration information from the code, making it easier to run multiple
// calendars on the same server off the same code "base".
// By default, the code is kept in the same directory as the config file.
$ltw_config['include_dir']	= './private';

// 'html_xxxxer_file' defines the file system path to the header and footer 
// files, making them easier to share with other php scripts on a site
$ltw_config['html_header_file']  = $ltw_config['include_dir'].'/ltw_header.php';
$ltw_config['html_footer_file']  = $ltw_config['include_dir'].'/ltw_footer.php';

// 'hrs_per_day' defines whether to use a 12 to 24 hour clock display 
// for starting and ending times.
// valid settings are 12 or 24
$ltw_config['hrs_per_day'] = 12;

// 'salt' makes the database encryption for user logins unique to your setup 
//by defining the string that 'salts' the crypt() function when dealing with
// passwords in the database.
// Change the following to some random 9 character string.
$ltw_config['salt'] ='Rb98-JC##';

// Define the database connection parameters. 
// Currently only db_type = 1 (My SQL) is supported.
$ltw_config['db_type']	  	= 1;
$ltw_config['db_server']	= 'localhost';
$ltw_config['db_name']	  	= 'bluemov2_calendar';
$ltw_config['db_user']	  	= 'bluemov2_vadmin';
$ltw_config['db_pass']	  	= '1200';
$ltw_config['db_persistent']	= false;

// Define the table names in the database to use
$ltw_config['db_table_calendar']= 'ltw_eventsv4';
$ltw_config['db_table_category']= 'ltw_categoryv4';
$ltw_config['db_table_users']	= 'ltw_usersv4';
$ltw_config['db_table_log']	= 'ltw_logv4';

// 'list_query_size' defines the number of days to return for each
// query in the "list" view.  This variable allows you to "throttle"
// the number of events returned to minimize memory impact.
// If your calendar has a lot of events with long descriptions, you may
// want to reduce the size of this parameter.
$ltw_config['list_query_size'] = 31;

// 'login_required' defines whether or not a user must log in to see 
// the calendar. If 'login_required', then a user must also have
// :Read Privledge" to view the calendar.
// Set login_required = 1 to enable
$ltw_config['login_required'] = 0;

// 'uloglevel' defines the user 'event' logging that occurs
// This is a bitmapped value, so you can set from
// none to all of the bits for full logging
//   Bit 0x01 User Add/Edit/Delete (brief)
//   Bit 0x02 User Add/Edit/Delete (detailed)
//   Bit 0x04 User Login 'Events' 
//   Bit 0x08 User Change Password 'Events'
// examples
//   uloglevel = 0x04  - write only Login/Out 'events'
//   uloglevel = 0x05  - logins and minimal user change 'events'
//   uloglevel = 0x0f  - full logging
//   uloglevel = 0x00  - disabled
$ltw_config['uloglevel'] = 0x0f;
	
// 'eloglevel' defines the event change logging that occurs
// This is a bitmapped value, so you can set from
// none to all of the bits for full logging
//   Bit 0: Event Add/Edit/Delete (brief)
//   Bit 1: Event Add/Edit/Delete (detailed)
// examples
//   eloglevel = 0x01  - minimal logging
//   eloglevel = 0x03  - maximum logging
//   eloglevel = 0x00  - disabled
$ltw_config['eloglevel'] = 0x0f; 
	
// 'cloglevel' defines the category change logging that occurs
// This is a bitmapped value, so you can set from
// none to all of the bits for full logging
//   Bit 0x01 Category add/edit/delete (brief)
//   Bit 0x02 Category add/edit/delete (detailed)
// examples
//   cloglevel = 0x01  - minimal logging
//   cloglevel = 0x03  - maximum logging
//   cloglevel = 0x00  - disabled
$ltw_config['cloglevel'] = 0x0f; 

// 'min_pw_length' defines the minimum password number of characters allowed 
// for a password. The minimum number for reasonable security should be 8
$ltw_config['min_pw_length'] = 8;

// 'pw_strength' defines the ammount of password checking that occurs.
// Valid values are:
//	0 - minimum -  must be min_pw_length and can not contain username
//                            forward, backwards, or subbing 53011 for seoll
//      1 - medium  -  add it must contain numbers
//      2 - high    -  add it mus be mixed case + punctuation
$ltw_config['pw_strength'] = 0;

// 'max_pw_age' defines the number of days a password is valid.
// During the login process, if this number of days has elapsed since
// the last password change, then the user's USCHGPW bit is set
//
// max_pw_age = 0 disables the function
$ltw_config['max_pw_age'] = 180;

// 'bad_logins' and 'grace_period' define the number of times 
// and in what period of seconds an invalid login will cause a user account
// to be "locked".  
// bad_logins = 0  - disabled
$ltw_config['bad_logins']   = 5;    
$ltw_config['grace_period'] = 120;  
		
// 'cat_fgcolor' and 'cat_bgcolor' define the default color for event links
// if no matching category is found in the database
// (ie the category is deleted for existing events)
$ltw_config['cat_fgcolor'] = "#000000";
$ltw_config['cat_bgcolor'] = "#ffffff";

// 'week_starts_monday' defines whether the monthly calendar display
// starts on Sunday or Monday.
//  'week_starts_monday' = 0   - start the week on Sunday.
//  'week_starts_monday' = 1   - start the week on Monday.
// See Note in following section on 'daynames' too!
$ltw_config['week_starts_monday'] = 0;

// 'daynames' defines the names of the days that appear across the top of the 
// "month" display. The array is defined twice, once for each posibility, to make
// it easier to set up.
// 
if ( $ltw_config['week_starts_monday'] == 1 ){
  // if week starts Monday
  $ltw_config['daynames'] = array("Monday","Tuesday","Wednesday", "Thursday","Friday","Saturday","Sunday",);
}else{
  // if week starts Sunday
  $ltw_config['daynames'] = array("Sunday","Monday","Tuesday","Wednesday", "Thursday","Friday","Saturday");
}
	
// 'monthnames' defines the names of the months as they appear on the calendar
$ltw_config['monthnames'] = 
array(1=>"January","February","March","April", "May","June","July","August", "September","October","November","December");

// 'start_time' and 'end_time' set the default event start and ending times.
// The format is HH:MM:TD, and is sensitive to 'hrs_per_day'
// 	HH - hour  			: 1-12 or 0-23
// 	MM - minute			: 0, 15, 30, 45
//  TD - time of day: AM/PM or ignored (if hrs_per_day = 24)
// if clock_base = 24, then the 3rd field is ignored
// start_time = 0  - disables feature
// end_time   = 0  - disables feature
$ltw_config['start_time'] = 0;
$ltw_config['end_time']   = 0;

// 'bullets' defines whether or not a bullets are displayed for each event.
//  bullets = 0  - disables the feature
$ltw_config['bullets']	= '0';

// 'email_enabled'  defines whether to send an eMail whenever an event is 
//                  added or updated.
// 'email_from'     defines the 'From: address' of the sender
// 'email_reply_to' defines the 'Reply-To: address' of the sender, optional.
// 'email_host'     defines the SMTP host to mail to (for Windows installations)
//
// email_enabled = 0  - disables feature
$ltw_config['email_enabled']	= '1';
$ltw_config['email_from']	= 'ltwAdmin@bluemountainairpark.com';
$ltw_config['email_reply_to']	= '';
$ltw_config['email_host']	= '';

// 'use_popups'  defines whether or not to use javascript popup windows
//               or just load new page in current browser window
//
// use_popups = 0  - disables popups
// use_popups = 1  - enaables popups
$ltw_config['use_popups']	= 1;	


?>
