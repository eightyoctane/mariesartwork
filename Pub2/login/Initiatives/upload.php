<?php
require_once($_SERVER['DOCUMENT_ROOT']."/homedatapub2.php");

$target = $rootlocation."login/Proposals/"; 
 $target = $target . basename( $_FILES['uploaded']['name']) ; 
 $ok=1; 
 if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) 
 {
 echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
 $msg = "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded";
 } 
 else {
 echo "Sorry, there was a problem uploading your file.";
 $msg = "Sorry, there was a problem uploading your file.";

 }
 header('Location: '.$homelocationpart.$homelocationsub."login/Initiatives/pForm.php?iid=".$iid."&ptype=".$ptype."smsg=".$msg);				
?>