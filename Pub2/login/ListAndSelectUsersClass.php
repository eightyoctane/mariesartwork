<?php
class ListAndSelectUsers {
	var $table;
	var $rowcontent;
	var $hidden_names;
	var $hidden_values;
	var $callback;
	var $hidden;
	var $slist = array();
	
	function ListAndSelectUsers() {
		$this->hidden = " ";
		
	}
	
	function addCallback($action) {
		$this->callback = "<form action ='$action' form method='POST'>";
	}
	
	function addHidden($hiddenname, $hiddenval) {
			$this->hidden .= "<input type=hidden name='$hiddenname' value='$hiddenval'>";
	}
	
	//not tested
	function addSmallSubmitButton($label, $name) {
   		return '<input class="button" type="submit" value="'.$label.'" name="'.$name.'">';
 	}
 	//not tested
 	function addSmallButton($label, $action) {
   		return '<input class="button" type="button" value="'.$label.'" onClick="'.$action.'">';
 	}
 	
 	
	/*
		form
		hidden values
		table header
		table rows
		table footer
	*/	
	function renderLSScreen() {
		//echo "renderLSSCREEN() adding form callback<br />";
		$this->table = $this->callback;
		$this->table .= $this->theader;
		$this->table .= $this->hidden;
		$this->table .= $this->rowcontent;
		$this->table .= "</table>";
		$this->table .= "</form>";
		//echo "renderLSScreen() adding table footer <br />";
		return "$this->table";	
	}
	
	function addRow($id, $name, $uname) {
		//echo "addRow id: $id name: $name uname: $uname <br />";
		$this->rowcontent .= "<tr>
					<td align=center width=10% height=57><input type=checkbox name=slist[] value=$id />
					<td>$name </td>
					<td>$uname</td></tr>";		
	}
	
	function getEVListCount() {
		return(count($_POST['slist']));
	}
	
	function getEVList() {
		$this->slist = $_POST['slist'];
		return($this->slist);
	}
	

	
	function addTableHeader($w='50%',$l='center',$b='1') {
				$this->theader = "<table width=$w align=$l border=$b>
				<tr> 
				<input type=submit value='Press to View/Edit Checkmarked Items' name='VE'>
				<input type=submit value='Press to Delete\nCheckmarked Boxes' name='RM'>
				</th>
				</tr>
				<tr>
				<th>c</th>
				<th>Name</th>
				<th>Username</th>
				</tr>";
	}

}