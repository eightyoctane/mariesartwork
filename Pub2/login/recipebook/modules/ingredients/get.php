<?
require_once("classes/DBUtils.class.php");

$searchText = isset ($_GET['term'] ) ? $_GET['term'] : ".*";
$searchLimit = 100;//isset ($_GET['limit'] ) ? $_GET['limit'] : 100;

$count = 0;
$sResult = "";

$sql = "SELECT * FROM $db_table_ingredients";
$ingredients = $DB_LINK->Execute($sql);

$searchResults = array();

while (!$ingredients->EOF) 
{
	if ($searchText != "" && preg_match("/" . $searchText . "/i", $ingredients->fields['ingredient_name']))
	{
		$key = $ingredients->fields['ingredient_name'];
		$value = $ingredients->fields['ingredient_id'];
		array_push($searchResults, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
		//$searchResults[] = ;
		//$sResult .= "|".$ingredients->fields['ingredient_id']."#".$ingredients->fields['ingredient_name'];
	}
    $ingredients->MoveNext();
}

// return a friendly no-found message
if (count($searchResults) == 0)
{
	$key = "No Results for '$searchText' Found";
	$value = "";
	array_push($searchResults, array("id"=>$value, "label"=>$key, "value" => strip_tags($key)));
	$searchResults[] = "";
	//$sResult = "|#No Results for '$searchText' Found";
}

echo array_to_json($searchResults);

function array_to_json( $array ){

    if( !is_array( $array ) ){
        return false;
    }

    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ){

        $construct = array();
        foreach( $array as $key => $value ){

            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.

            // Format the key:
            if( is_numeric($key) ){
                $key = "key_$key";
            }
            $key = "\"".addslashes($key)."\"";

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "\"".addslashes($value)."\"";
            }

            // Add to staging array:
            $construct[] = "$key: $value";
        }

        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";

    } else { // If the array is a vector (not associative):

        $construct = array();
        foreach( $array as $value ){

            // Format the value:
            if( is_array( $value )){
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ){
                $value = "'".addslashes($value)."'";
            }

            // Add to staging array:
            $construct[] = $value;
        }

        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    }

    return $result;
}
?>

