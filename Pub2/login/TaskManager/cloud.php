<?php require_once "header.php"; ?>
<?php
// Change the following variables to connect to your mysql databse
$mydb_host = "localhost";
$mydb_user = "root";
$mydb_pass = "";
$mydb_name = "db_todolist";

// Connection to the databse
mysql_connect($mydb_host,$mydb_user,$mydb_pass) or die(mysql_error());
mysql_select_db($mydb_name) or die(mysql_error());
 
// Create the tag array  with 50 records from the database,  all  modified during the last 2 days 
function tag_info() {
	$result = mysql_query("SELECT * FROM tdl_tags LIMIT 100");
	/*print_r(mysql_num_rows($result));
	exit;
	*/	while($row = mysql_fetch_assoc($result)) 
		{
		$result2 = mysql_query("SELECT * FROM tdl_tags where tags_tags = '".$row['tags_tags']."'");
		$row2 = mysql_fetch_assoc($result2);
		$TagsNum = mysql_num_rows($result2);
		
			$arr[$row['tags_tags']] = $TagsNum;
		}
ksort($arr);
return $arr;
	}	

function tag_cloud() {
// Define the min and max size of the fonts 
	$min_size = 10;
	$max_size = 30;
	$tags = tag_info();
	$minimum_count = min(array_values($tags));
	$maximum_count = max(array_values($tags));
	$spread = $maximum_count - $minimum_count;
if($spread == 0) {
$spread = 1;
}

$cloud_html = '';
$cloud_tags = array();

foreach ($tags as $tag => $count) {
// My records have a + between each word, I'm removing these with the line below
$tagshow = str_replace("+"," ",$tag);
$size = $min_size + ($count - $minimum_count) * ($max_size - $min_size) / $spread;
// create the html output - make sure to adapt tout you url
$cloud_tags[] = '<a style="font-size: '. floor($size) . 'px'
. '" class="tag_cloud'. floor($size) . '" href="http://www.ilikeyoutube.com/index.php?tag=' . $tag
. '" title="\'' . $tag . '\' returned a count of ' . $count . '">'
. htmlspecialchars(stripslashes($tagshow)) . '</a>';
}
$cloud_html = join("\n", $cloud_tags) . "\n";
return $cloud_html;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<style type="text/css">

.around {
margin: 10px auto 0px auto;
width: 144px;
border: 0px solid #CCCCCC;
padding: 0px;
text-align:justify;
line-height:10px;
height:350px;
}

.small { font-family: Verdana, Geneva, Courier; font-size: 5pt; color: #CCCCCC;}

.cloud {
margin: 144px auto 0px auto;
width: 400px;
border: 1px solid #CCCCCC;
padding: 5px;
text-align:justify;
line-height:10px;
height:349px;
}

.myDiv {
width:250px;
}

.tag_cloud8{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud8:link { color: #00F0FF; }
.tag_cloud8:visited { color: #00F0FF; text-decoration: underline; }
.tag_cloud8:hover { color: #000AD2; }
.tag_cloud8:active { color: #00F0FF; }
	
.tag_cloud9{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud9:link { color: #00D9FF; }
.tag_cloud9:visited { color: #00D9FF; text-decoration: underline; }
.tag_cloud9:hover { color: #000AD2; }
.tag_cloud9:active { color: #00F0FF; }
	
.tag_cloud10{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud10:link { color: #00B3FF; }
.tag_cloud10:visited { color: #00B3FF; text-decoration: underline; }
.tag_cloud10:hover { color: #000AD2; }
.tag_cloud10:active { color: #00F0FF; }

.tag_cloud11{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud11:link { color: #00B3FF; }
.tag_cloud11:visited { color: #00B3FF; text-decoration: underline; }
.tag_cloud11:hover { color: #000AD2; }
.tag_cloud11:active { color: #00F0FF; }

.tag_cloud12{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud12:link { color: #00B3FF; }
.tag_cloud12:visited { color: #00B3FF; text-decoration: underline; }
.tag_cloud12:hover { color: #000AD2; }
.tag_cloud12:active { color: #00F0FF; }

.tag_cloud13{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud13:link { color: #0066FF; }
.tag_cloud13:visited { color: #0066FF; text-decoration: underline; }
.tag_cloud13:hover { color: #000AD2; }
.tag_cloud13:active { color: #0066FF; }

.tag_cloud14{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud14:link { color: #0066FF; }
.tag_cloud14:visited { color: #0066FF; text-decoration: underline; }
.tag_cloud14:hover { color: #000AD2; }
.tag_cloud14:active { color: #0066FF; }

.tag_cloud15{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud15:link { color: #0066FF; }
.tag_cloud15:visited { color: #0066FF; text-decoration: underline; }
.tag_cloud15:hover { color: #000AD2; }
.tag_cloud15:active { color: #0066FF; }

.tag_cloud16{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud16:link { color: #0066FF; }
.tag_cloud16:visited { color: #0066FF; text-decoration: underline; }
.tag_cloud16:hover { color: #000AD2; }
.tag_cloud16:active { color: #0066FF; }

.tag_cloud17{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud17:link { color: #0057F8; }
.tag_cloud17:visited { color: #0057F8; text-decoration: underline; }
.tag_cloud17:hover { color: #000AD2; }
.tag_cloud17:active { color: #0057F8; }

.tag_cloud18{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud18:link { color: #0057F8; }
.tag_cloud18:visited { color: #0057F8; text-decoration: underline; }
.tag_cloud18:hover { color: #000AD2; }
.tag_cloud18:active { color: #0057F8; }

.tag_cloud19{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud19:link { color: #0057F8; }
.tag_cloud19:visited { color: #0057F8; text-decoration: underline; }
.tag_cloud19:hover { color: #000AD2; }
.tag_cloud19:active { color: #0057F8; }

.tag_cloud20{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud20:link { color: #0057F8; }
.tag_cloud20:visited { color: #0057F8; text-decoration: underline; }
.tag_cloud20:hover { color: #000AD2; }
.tag_cloud20:active { color: #0057F8; }

.tag_cloud21{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud21:link { color: #004DF2; }
.tag_cloud21:visited { color: #004DF2; text-decoration: underline; }
.tag_cloud21:hover { color: #000AD2; }
.tag_cloud21:active { color: #004DF2; }

.tag_cloud30{padding: 1px; text-decoration: none; font-family: verdana; }
.tag_cloud30:link { color: #000AD2; }
.tag_cloud30:visited { color: #000AD2; text-decoration: underline; }
.tag_cloud30:hover { color: #000000; }
.tag_cloud30:active { color: #000AD2; }

</style>
			<div class="myDiv">
			<?php print tag_cloud(); ?>
			</div>

	

<?php require_once "footer.php"; ?>