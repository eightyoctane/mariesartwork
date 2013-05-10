<?php
$thisyear = date('Y');
$nextyear = $thisyear + 1;
$thismonthandday = date('-m-d');

header("Location: calendar.php?display=list&start_date=$thisyear$thismonthandday&end_date=$nextyear$thismonthandday");
?>
