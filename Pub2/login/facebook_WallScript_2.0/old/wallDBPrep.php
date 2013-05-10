include_once($_SERVER['DOCUMENT_ROOT']."/homedata.php");
include_once($rootlocation."login/dbdata.php");
require_once($rootlocation."login/libfunctions.php");
include_once($rootlocation."login/mysqlClass.php");
include_once($rootlocation."login/rFormClass.php");
include_once($rootlocation."logFileClass.php");
include_once($rootlocation."PresenterClass.php");
include_once($rootlocation."login/sessionSetup.php");
	
	
$lf = new logFile();
