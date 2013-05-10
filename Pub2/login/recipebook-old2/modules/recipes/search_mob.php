<form name="inputForm" action="index.php?m=recipes&a=search_mob" method="post">
<div data-role="fieldcontain">
    <label for="search">Recipe Search:</label>
    <input type="search" name="recipeSearch" id="search" value="" />
</div>
</form>

<?php 
$search = isset($_POST['recipeSearch']) ? $_POST['recipeSearch'] : "";

if ($search != "")
{?>
<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
	<li data-role="list-divider">Search Results</li>
<?php 
	#Construct the Query
	$query="";
	$query_order = " ORDER BY recipe_name";
	$query_all="SELECT
					recipe_id,
					recipe_name,
					recipe_comments,
					recipe_private,
					recipe_owner,
					recipe_serving_size,
					user_name
				FROM $db_table_recipes
				LEFT JOIN $db_table_users ON user_login = recipe_owner";
	$query = $query_all . " WHERE ";
	if ($search != "") {
		$query .= " recipe_name LIKE '%". $DB_LINK->addq(htmlentities($search, ENT_QUOTES), get_magic_quotes_gpc()) . "%' OR ";
		$query .= " recipe_directions LIKE '%".$DB_LINK->addq(htmlentities($search, ENT_QUOTES), get_magic_quotes_gpc()) . "%' OR ";
		$query .= " recipe_source LIKE '%". $DB_LINK->addq(htmlentities($search, ENT_QUOTES), get_magic_quotes_gpc()) . "%' OR ";
		$query .= " recipe_comments LIKE '%". $DB_LINK->addq(htmlentities($search, ENT_QUOTES), get_magic_quotes_gpc()) . "%'";
	}
	$query = preg_replace("/AND$/", "", $query);
	$query .= $query_order;
	
	if ($query != "") {
		$counter=0;
		$recipes = $DB_LINK->Execute($query);
		# exit if we did not find any matches
		if ($recipes->RecordCount() == 0)
		{
			echo $LangUI->_('No values returned from search') . "<br>";
		}
		else
		{
			while (!$recipes->EOF) 
			{
				$recipe_id = $recipes->fields['recipe_id'];
				/*
					If this is a private recipe and the user does not have access to it, then skip it
				*/
				if (($recipes->fields['recipe_private'] == $DB_LINK->true) &&
					(!$SMObj->getUserLoginID() ||
					 (!$SMObj->checkAccessLevel("EDITOR") &&
					 $SMObj->getUserLoginID() != $recipes->fields['recipe_owner'] &&
					 !$SMObj->hasGroupsWith($recipes->fields['recipe_owner'])))) {
						 $recipes->MoveNext();
						 continue;
				}?>
				
				<li>
					<a href="./index.php?m=recipes&a=view_mob&recipe_id=<?php echo $recipes->fields['recipe_id'];?>"><?php echo $recipes->fields['recipe_name'];?></a>
				</li>
				<?php $recipes->MoveNext();
			}	
		}	
	}
}
?>
</ul>
