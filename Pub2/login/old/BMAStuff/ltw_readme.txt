ltwCalendar
by Matt Wade
http://codewalkers.com/
demo of ltwCalendar at http://codewalkers.com/cal/calendar.php
$Id: ltw_readme.txt 28 2006-04-01 23:38:13Z mootinator $

License:
Fair License

Copyright 2006 Matt Wade and Contributors

Usage of the works is permitted provided that this
instrument is retained with the works, so that any entity
that uses the works is notified of this instrument.

DISCLAIMER: THE WORKS ARE WITHOUT WARRANTY.

[2004, Fair License: rhid.com/fair]

Release 4.2.1

Usernames are now validated.  The username must now be from 1 to 20
characters long, containing only letters, numbers and the underscore.
Current users who upgrade to this version and have usernames which do not
meet these criteria will be unable to log in.
 
*** Please ensure all existing usernames meet the above criteria prior to
installing version 4.2.1.

See ltw_changelog.txt for bug fix details.
This release provided by Kevin Stricker

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                        New Features in 4.2
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*) Added printable calendar PDF generation to the monthly calendar 
using FPDF (www.fpdf.org)

*) Added solutions to many common problems with 4.13 based on feedback from
the forums at codewalkers.com

Release 4.13
See ltw_changelog.txt for bug fix details.
This release provided by Tom Levandusky.
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                         New Features in 4.1
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*) Split Month, Day, & Event views into separate files.
   Since these are the files that get customized the most for display purposes,
   this should allow calendar users to upgrade easier while maintaining their
   customized look.

*) Added 'List' View:   calendar.php?display=list
   URL arguments are:
	&start_date=yyyy-mm-dd     Day to start the list from
	&end_date=yyyy-mm-dd       Day to end the list
	&cat_ids=n,n,n             list only these categories
   By default, you will get a list of the current month

   In order for the Event Manager to return to the correct view (list or month), 
   a new _REQUEST parameter 'returnto' is now included in the links.  The 
   accepted values are 'month' or 'list', and the default value is 'month'.

   In order to control the size of the columns in the list view, you will find
   the variables $dwidth (Date Width), $tWidth (Time Width) and $nWidth (Name
   width) in the function ltwCalendarList::displayWidth

*) Added category filter to month view: calendar.php?display=month
   URL arguments are:
	&cat_ids=n,n,n            list only these categories

*) Added password aging 
	Setting the parameter 'max_pw_age' (in days) will force a user to 
	change their password when they log in if the old password is 
	more than 'max_pw_age' days old.

*) Added basic Db maintainence
	You can now delete old event and log entries out of the database on 
	line.

*) Improved performance on the month and list displays
	Previously, the month display did a separate db query for each day, 
	resulting in (upto) 31 separate accesses.  The logic has been re-
	written to do one query that loads all events for that month into an
	array. The code then goes thru the in memory array once for each day.

	For the Month display, this should not pose a problem with memory 
	resources as each event takes up < 200 bytes. With 100 events per
	month, the array consumes ~20kb of memory.
	
	Since the List display loads the location and description fields too,
	the 'list_query_size' configuration parameter has been added. This 
	parameter limits the number of days to be queried at one time, so
	if the list display is for the current month, and 'list_query_size' is
	7, then the code will do four queries (one for each week) and still
	perform better than if it did one query for each day.


	
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                           Installation
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
1. Unzip the files into a directory under your web server's docroot.
   In the directory will be:
	calendar.php    - the "switchboard" script exposed on the web
	ltw_install.php - the installer script
	ltw_style.css   - the calendar style sheet 
	ltw_readme.txt  - this file
	
   A sub-directory named 'private' is also created that contains the 
   configuration file, sample header & footer files, and other files
   that make up the calendar:
   	.htaccess         - apache access control file, set to deny all
	ltw_config.php    - calendar configuration file
	ltw_header.php    - sample calendar header file
	ltw_footer.php    - sample calendar footer file
	ltw_classes.php   - "core" calendar objects class
	ltwdisplayxxx.php - calendar display classes
	ltwxxxxmgr.php    - calendar "manager" classes

   Because the 'private' directory contains the database configuration 
   parameters including usernames and passwords, it should be protected
   from web access.  The base installation contains a .htaccess file that
   contains the statement "deny all" doing just that.  For other web servers
   you should configure them to deny access to this directory.

   A more secure approach is to move the contents of the 'private' directory
   outside of the docroot on your server.  In order to do this, you need to:
   	a) Move the config file, ltw_config.php to its new location.
	b) Modify the location of config file in ltw_install.php and 
	   calendar.php:
	   require_once('./private/ltw_config.php');
	c) Move the header & footer files to their new location.
	d) Modify the location of the files in ltw_config.php:
	e) Move the other files in 'private' to their new location.
	f) Modify the line defining 'include_dir' in the config file.
   
2. Edit ltw_config.php file and set the rest of the options.
   ltw_config.php is heavily commented, read thru it.

   If you are running a local installation and do not have a db password set
   (a very bad thing to not do), you MUST comment out the following line in 
   ltw_install.php:
   if ( empty($ltw_config['db_pass']) ) 
      $errors .= "Missing 'db_pass' parameter in ltw_config.php<br>";

3. Point your browser to http://yourdomain/<dir>/ltw_install.php

   The  installer will give you a welcome message and a link to continue 
   the install. Once you click the link, the installer will go thru each table
   it needs and create or upgrade it as necessary.
   
    !!! If you are upgrading an existing calendar, 
    !!! It is very important you back up your database before doing the upgrade !!!
    The default table names in the config file have a "v4" appended, so by default,
    the installer will create a new database.  What is suggested, if you have 
    the room, is to make a copy of your old tables, then upgrade the copies.
   
4. Click the link to continue the install process.

	The install process:
	1) Creates the log and category tables if not there
	2) Adds columns email, status, privledges, bad_logins, bad_logins_start and
	last_pw_change to the user table.
	3) Adds columns day_event, cat_id, event_end and location to the event table.
	4) Sets event_end = event_date for all non-recurring events
	5) Sets event_end = 2003-12-31 for al recurring events


5. Click the link to create a new "administrator" account.

	!!! Even if you are upgrading an existing calendar, 
	!!! YOU MUST CREATE A NEW ADMIN ACCOUNT. 
  	The calendar now supports giving a user read/edit/log/admin 
	privledges.  By creating a new admin, you can set the privledges on all 
	other user accounts as needed. A user does not need to be an admin 
	anymore to add/edit/delete events.
	
	(Ok, you don't really have to create a new account. You can go into the
	user table and set privledges = 128 on existing accounts if you want to
	do it that way, but it seems a lot of not so technical people use the 
	calendar, thus the instructions)
   
4. After the admin account is created, delete the ltw_install.php.
   You won't need it to add any more users, you can do so on line now.

Note!
  The calendar ships with the 'main' script named calendar.php.
  Since all calendar functions go thru this script, and it uses $PHP_SELF
  to get its name, you can rename 'calendar.php' easily and things will still
  work.  For instance, if you install the calendar in a sub-directory named 
  "calendar" under the docroot, you may want to rename calendar.php to 
  index.php if that is a configured "directory index" for your web server.
  Then calls to http://your-domain/calendar will display the month calendar.

A few shortcuts:
	ltw_install.php?command=install bypasses the opening screen
	ltw_install.php?command=user jumps to the add admin form

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                        Configuring the Calendar
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Read ltw_config.php.  All the parameters and what they do are in there.

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
                                Notes
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

1) In ltw_style.css, the style name for the "Month & Year" at the top of the 
   month display has changed from "caption.cal" to ".caption". 
   This was necessary since that became another table to support the "jump"
   function.
   
2) Multiple day events are now supported.

   An "event end" date has been added for events so they can span multiple days.
   The date validation code in add() and edit() now compares date AND time, 
   so you can start an event (as an example) on Monday at 8:00pm and end it 
   on Tuesday at 7:00am. No more "Bad Time" errors unless it really is!

   The month display has changed slightly too. If an event has an end date 
   different than the start date, AND the end time is less than the start time,
   then the start time is not displayed on the last day of the event.  This
   is so an "overnight" event shows up both days on the calendar, but displays
   the start time on the first day only.

   The "event" display now shows start date/time, end date/time, day long 
   events and recurring events (and day of week) appropriately.  
   To acomplish this in a consistant manner, a new function 'displayOneEvent()' 
   was added to class ltwCalendar. Functions ltwCalendar::displayDay() and 
   ltwCalendar::displayEvent() now call this function.
   
3) End dates for recurring events are now implemented
   The "End Date" applies to recurring events. The event will recur until
   the date exceeds the 'event end' date.

4) Auto-locking of accounts that have too many bad login attempts
   in a given time period can be enabled.  The account can be unlocked 
   on line by an administrator thru the user manager.
 
5) User Account status has been added, including
   locked - the account is locked and can't log in. This can be set by the
            administrator or by the bad login auto lock function
   
   pw change - the user must change his password at next login. 
            When you create a new user, you can give them a 'temporary'
	    password and require them to change it at the next login.
     
   User Status is a bitmapped field in the database.  The constants for the
   current values are defined at the top of ltw_classes.php. If you want
   to add more statuses, define them here to stay 'consistant'.

6) User password 'aging'.
   Whenever a user password is changed by the user or an administrator, the
   change is noted in the 'last_pw_change' datetime field in the database.
   This allows an administrator to implement a password aging policy "outside"
   the calendar.  

7)User Account privledges have been added, including:
     read - read the calendar (if login required) 
     edit - add/edit/delete events
     logs - view logs
     email- recieve event change emails
     admin- read/edit events, edit logs, edit categories, admin users

   User Privledge is a bitmapped field in the database.  The constants for 
   the current values are defined at the top of ltw_classes.php. If you want
   to add more statuses, define them here to stay 'consistant'.

   If for some reason all accounts have the 'admin' privledge removed, you 
   can restore it on an account by setting the Privledges column in the 
   database = 128.
     ie. UPDATE ltw_usersV4 SET privledges = 128 where username = '<sone name>'
    
8) Logging of changes to a database table has been added.
   Logging can be configured for user, category, and calendar 'events'
   at three levels: none, minimal, detailed.

   In addition, user logins and password changes cane be logged.
   See the 'uloglevel', 'eloglevel' and 'cloglevel' config parameters.

   Note: The detailed log messages display the data as stored in the 
         database, all times are in 24 hour format.

    Logs can be viewed on line with the "View Logs" privledge.  
    
9) Event changes (add/edit/delete) can be emailed to a list of users who have
   the 'email' privledge set and an email address entered.
   See the 'email_enabled', 'email_from', 'email_reply_to' and  'email_host'
   config parameters.

   The "body" of the sent message is the "detailed" log entry, all times are 
   in 24 hour format. To customize the message, edit the notifier() function 
   in ltweventmgr.php, using the contents of $this->event to populate the 
   message variables.

10) Password validation has been added.
   The minimum length and "strength" of passwords can be set by configuration
   parameters 'min_pw_length' and "pw_strength'.
   'pw_strength can be assigned values of 0 (min), 1(medium) or 2(high)

   Passwords are validated as follows:
   'pw_strength' == 0
     1) Can not contain the username forward or backward or by substituting the numbers
        "53011" for the characters "seoll" respectively
     2) Must be a minimum length (see the 'min_pw_length' config parameter)
     
    'pw_strength' == 1 
     1) Level 0 rules PLUS 
     2) Must contain at least one number

    'pw_strength == 2'
     1) Level 1 rules PLUS
     2) Must contain at least one lower & one UPPER case character
     3) Must Coontain one punctuation character

     Examples:
     Settings: Level 1, min_pw_length = 6, username jimbo
     Valid:    any six characters not containing jimbo, jimb0, obmij, 0bmij

     Settings: Level 2, min_pw_length = 6, username jimbo
     Valid:    any six characters not containing jimbo, jimb0, obmij, 0bmij
               AND containing one numeric character (0-9) other than the 0 for o

     Settings: Level 3, min_pw_length = 6, username jimbo
     Valid:    any six characters not containing jimbo, jimb0, obmij, 0bmij
               AND containing one numeric character (0-9)
	       AND containing one UPPER CASE LETTER
	       AND containing one lower case character
	       AND containing one punctuation character
	       
    To add or delete rules, modify the ltwAuth::isPasswordValid() function in 
    ltw_classes.php


11) Database maintainence
    New in version 4.1 is a simple db manager that will allow an administrator 
    to purge old event and log entries. The db manager tells you the daye of 
    the oldest event (or log entry) and the total number of records in the table.
    You can then delete all rows from the database for either (or both) tables
    that exist prior to the date you select.
    
12) I have one tester running Norton Personal Firewall, and he found he was 
   required to right click and open in a new window the login link to be able to
   log on.  I suspect it has to do with NPF blocking popups or javascript 
   that opens new windows.
   
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
++  Older Versions
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
Release v4.0 
This release provided by Tom Levandusky.
eMail code provided by Ian in Australia
Most other features are from the forums at Codewalkers.
*) All links now use $PHP_SELF instead of 'calendar.php' 
   This will allow the calendar to be installed in any directory on the
   web server.
   
*) An 'include_dir' parameter is now defined in the config file.
   This parameter defines where to look for all files associated with the
   calender "executable code", making runing (and upgrading) multiple 
   calendars on the same server easier.

*) A 'use_popups' parameter is now defined in the config file.
   You can use this to disable the popup windows so everything appears
   in the main browser window.

*) The calendar now works correctly on non-javascript enabled browsers.

*) 12 and 24 hour time formats are supported.

*) The calendar week can start on Sunday or Monday.
   
*) Event "Locations" have been added. 

*) A "jump to" for has been added to the month display to make moving 
   several months in any direction easier.

*) The calendar can be configured to require login to view it.
   "Read" privledge (see # 13 below) is then required to access it.
   
*) Multiple day events are now supported.
   
*) End dates for recurring events are now implemented

*) Users can now change their passwords on line once they have logged in.

*) Auto-locking of accounts that have too many bad login attempts
   in a given time period can be enabled.

*) User Account status has been added.
     
*) User Account privledges have been added.
     
*) On-line user account management has been added for Administrators.
    
*) Event, Category and User Activity logging to a db table has been added.

*) Logs can be viewed on line with the appropriate privledges.
    
*) Event changes (add/edit/delete) can be emailed.

*) Password validation has been added
      
*) The installer has been rewritten to use the standard classes in 
   ltw_classes.php and will 'upgrade' the database from V2 or V3 versions of
   the calendar

*) The code is more "modular" to minimize what gets parsed and (hopefully) 
   improve performance. It should also make 'add ons' easier to write.

   
Release v3.0 (This release provided by Tom Levandusky.)
------------
1)You can now specify Meeting categories and their display colors.
2)Meetings now show their Starting time, unless "All Day Event" is chosen.
3)The correct next month is displayed when the date is the 31st of a month
  and the next month has less than 31 days.
4)The header & footer filenames are now config parameters, making it easier
  to integrate the calendar into an overall site.
5)Renamed install.php to ltw_install.php
6)Default starting & ending times can be set for the meeting creation
  form

Release v2.2
------------
You can now use <b>,<i>, and <a> tags in descriptions
Current month now pulls from month array set in config file. Was overlooked.


Release v2.1
------------
Added backwards compatibility for pre4.1.0 PHP versions
Spiced up the installer a bit
When clicking on Add Event at the bottom of the calendar, it now defaults to todays date


Release v2.0
-------------
Moved to CSS for all formatting.
You can now have any text you want for the month names and day names.
Changed over to $_POST variables. This may cause the calendar to not function on older PHP installations.
Added an edit option. To edit an event, go the event in the popup screen and click edit.
made some addslashes and stripslashes calls to tighten things up.
fixed bug where time was not being stored.


Release v1.5
-------------
Fixed table creation errors. (Renamed some columns)
Added config value for table names. You can now name tables whatever you want.
Added an install script.


Release v1.02
-------------
Minor bug fixes.
Basically just fixed some more undefined variables.


Release v1.01
--------------
Minor bug fixes.
Basically just fixed some undefined variables.


Release v1.0
------------
First release.
Just seeing how the public likes it.

