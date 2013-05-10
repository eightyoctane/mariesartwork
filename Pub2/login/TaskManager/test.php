<html>
<head>
<title>Flick-Like Editing Test</title>

<style>
input.editMode {
background-color : #FFFF99;
}
textarea.editMode {
background-color : #FFFF99;
}
.savingAjaxWithBackground {
background-color : #FFFF99;
}
.superBigSize {
    font-size: 45px;
    font-weight: bold;
}
td.underlinedTD {
    border-bottom:1px #000000 dashed;
}
table.infoBox {
background-color:#F6F6F6;
border:#999999 double 1px;
font-size:11px;
padding:2px;
}
</style>

<script type="text/javascript">
function createRequestObject() {
    var ro;
    var browser = navigator.appName;
    if(browser == "Microsoft Internet Explorer"){
        ro = new ActiveXObject("Microsoft.XMLHTTP");
    }else{
        ro = new XMLHttpRequest();
    }
    return ro;
}

var http = createRequestObject();

function sndReq(action) {
    http.open('get', action);
    http.onreadystatechange = handleResponse;
    http.send(null);
}

function handleResponse() {
    if(http.readyState == 4)
	{
        if(http.responseText=="name") {
            var replaceText = document.getElementById('person_name').value;
            document.getElementById('name_rg_display_section').innerHTML = replaceText;
            document.getElementById('name_rg').style.display = '';
            document.getElementById('name_hv').style.display = 'none';
        }
        else if(http.responseText=="email") {
            var replaceText = document.getElementById('email_value').value;
            document.getElementById('email_rg_display_section').innerHTML = "<b>Email:</b> " + replaceText;
            document.getElementById('email_rg').style.display = '';
            document.getElementById('email_hv').style.display = 'none';
        }
        else if(http.responseText=="phone") {
            var replaceText = document.getElementById('phone_value').value;
            document.getElementById('phone_rg_display_section').innerHTML = "<b>Phone:</b> " + replaceText;
            document.getElementById('phone_rg').style.display = '';
            document.getElementById('phone_hv').style.display = 'none';
        }
    }
}
        function flashRow(obj) {    
            obj.bgColor = "#FFFF99";
        }
        function unFlashRow(obj) {    
            obj.bgColor = "#F6F6F6";
        }
</script>    
</head>
<body>


<?php 
$editingMyOwn = 1; //Verification code should go here. Set variable to whether user is editing a page they are allowed to.
$firstName = "John";
$lastName = "Doe";
$email = "john.doe@gmail.com";
$phone = "555-555-1234";
?>



<table  cellpadding="3" cellspacing="2" class="infoBox">
                                    <tr><td align="right" class="underlinedTD"><b>Contact</b></td></tr>
                                    <tr><td></td></tr>
                                    <?php
                                        if($editingMyOwn ==1) { ?>
                                            <tr id="name_rg">
                                            <td onMouseOver="flashRow(this);" onMouseOut="unFlashRow(this);" onClick="
                                            
                                            document.getElementById('name_hv_editing_section').style.display = '';
                                            document.getElementById('name_hv_saving_section').style.display = 'none';
                                            document.getElementById('name_rg').style.display = 'none';
                                            document.getElementById('name_hv').style.display = '';
                                            
                                            
                                            ">
                                            <div class="superBigSize" id="name_rg_display_section">
                                                <?php echo $firstName . " " . $lastName; ?>
                                            </div></td></tr>
                                            <tr id="name_hv"><td>
                                            <div id="name_hv_editing_section">
                                                    <input type="text" class="superBigSize editMode" size="<?php $val=strlen($firstName)+strlen($lastName); echo $val; ?>" value="<?php echo $firstName . " " . $lastName; ?>" id="person_name" /> <br /><input type="button" value="Save" onClick="document.getElementById('name_hv_editing_section').style.display='none';document.getElementById('name_hv_saving_section').style.display='';var req = 'updateProfileAjax.php?part=name&val=' + document.getElementById('person_name').value;sndReq(req);" /> Or <input type="button" value="Cancel" onClick="                        
                        document.getElementById('name_rg').style.display = '';
                        document.getElementById('name_hv').style.display = 'none';
                        "/>
                                            
                                            </div>
                                            <span id="name_hv_saving_section" class="savingAjaxWithBackground">
                                            Saving...
                                            </span>
                                            <script type="text/javascript">
                                                document.getElementById('name_hv').style.display = 'none';
                                                document.getElementById('name_hv_saving_section').style.display = 'none';
                                            </script>
                                        </td></tr>
                                        <?php } 
                                        else { ?>
                                        <tr>
                                            <td>
                                            <div class="superBigSize"><?php echo $firstName . " " . $lastName; ?></div>
                                        </td></tr>
                                        <?php } ?>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        <!--<tr><td align="right" class="underlinedTD">Email</td></tr>-->
                                        
                                        
                                        
                                        
                                        <?php
                                        if($editingMyOwn ==1) { ?>
                                            <tr id="email_rg">
                                            <td onMouseOver="flashRow(this);" onMouseOut="unFlashRow(this);" onClick="
                                            document.getElementById('email_hv_editing_section').style.display = '';
                                            document.getElementById('email_hv_saving_section').style.display = 'none';
                                            document.getElementById('email_rg').style.display = 'none';
                                            document.getElementById('email_hv').style.display = '';
                                            
                                            ">
                                            <div class="" id="email_rg_display_section"><b>Email:</b> <?php echo $email; ?></div>
                                            </td></tr>
                                            
                                            <tr id="email_hv"><td>
                                            <div id="email_hv_editing_section"><b>Email:</b> 
                                                <input type="text" class=" editMode" size="<?php $val=strlen($email); echo $val; ?>" value="<?php echo $email; ?>" id="email_value" /> <br /><input type="button" value="Save" onClick="document.getElementById('email_hv_editing_section').style.display='none';document.getElementById('email_hv_saving_section').style.display='';var req = 'updateProfileAjax.php?part=email&val=' + document.getElementById('email_value').value;sndReq(req);" /> Or <input type="button" value="Cancel" onClick="document.getElementById('email_rg').style.display = '';
                                 document.getElementById('email_hv').style.display = 'none';"/>
                                                
                                            </div>
                                            <span id="email_hv_saving_section" class="savingAjaxWithBackground">
                                            Saving...
                                            </span>
                                            <script type="text/javascript">
                                                document.getElementById('email_hv').style.display = 'none';
                                                document.getElementById('email_hv_saving_section').style.display = 'none';
                                            </script>
                                        </td></tr>
                                        <?php } 
                                        else { ?>
                                        <tr>
                                            <td>
                                            <div class=""><b>Email:</b> <?php echo $email; ?></div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        
                                        
                                        
                                        
                                        
                                            <?php
                                        if($editingMyOwn ==1) { ?>
                                            <tr id="phone_rg">
                                            <td onMouseOver="flashRow(this);" onMouseOut="unFlashRow(this);" onClick="
                                            document.getElementById('phone_hv_editing_section').style.display = '';
                                            document.getElementById('phone_hv_saving_section').style.display = 'none';
                                            document.getElementById('phone_rg').style.display = 'none';
                                            document.getElementById('phone_hv').style.display = '';
                                            
                                            ">
                                            <div id="phone_rg_display_section"><b>Phone:</b> <?php echo $phone; ?></div>
                                            </td></tr>
                                            
                                            
                                            <tr id="phone_hv"><td>
                                            <div id="phone_hv_editing_section"><b>Phone:</b> 
                                                <input type="text" class=" editMode" size="<?php $val=strlen($phone); echo $val; ?>" value="<?php echo $phone; ?>" id="phone_value" /> <br /><input type="button" value="Save" onClick="document.getElementById('phone_hv_editing_section').style.display='none';document.getElementById('phone_hv_saving_section').style.display='';var req = 'updateProfileAjax.php?part=phone&val=' + document.getElementById('phone_value').value;sndReq(req);" /> Or <input type="button" value="Cancel" onClick="document.getElementById('phone_rg').style.display = '';
                                 document.getElementById('phone_hv').style.display = 'none';"/>
                                                
                                            </div>
                                            <span id="phone_hv_saving_section" class="savingAjaxWithBackground">
                                            Saving...
                                            </span>
                                            <script type="text/javascript">
                                                document.getElementById('phone_hv').style.display = 'none';
                                                document.getElementById('phone_hv_saving_section').style.display = 'none';
                                            </script>
                                        </td></tr>
                                        <?php } 
                                        else { ?>
                                        <tr>
                                            <td>
                                            <div class=""><b>Phone:</b> <?php echo $phone; ?></div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                
                                        
</table>
</body> 