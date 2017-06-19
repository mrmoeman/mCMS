<?php

function getListofPages(){
	$mycontentarray = array();
	intiateDatabaseConnection();
	$SQL = "SELECT * FROM `mcms_pages` ORDER BY `mcms_pages`.`page_id` ASC";
	$result = mysql_query($SQL);
	$myCount = 0;
	if( mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)) {
			$mycontentarray[$myCount] = $row["page_id"];	
			$mycontentarray[$myCount + 1] = $row["page_name"];	
				
			$myCount+=2; 
		}
	}
	closeDatabaseConnection();
	return $mycontentarray;
}

function getFirstAvaliablePageID(){
	$startSearching = true;
	$checkID = 1;
	intiateDatabaseConnection();
	while($startSearching == true){
		
		$SQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $checkID . "' LIMIT 1";
		$result = mysql_query($SQL);
		//if we have got more than 0 row returned
		if( mysql_num_rows($result) > 0){
			$checkID = $checkID + 1;
		}
		else{
			$startSearching = false;
			closeDatabaseConnection();
			return $checkID;	
		}
	}
}

function doesPageExistForID($IDin){
	intiateDatabaseConnection();
		
	$SQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $IDin . "' LIMIT 1";
	$result = mysql_query($SQL);
	//if we have got more than 0 row returned
	if( mysql_num_rows($result) > 0){
		closeDatabaseConnection();
		return false;
	}
	else{
		$startSearching = false;
		closeDatabaseConnection();
		return true;	
	}
}

function getPageDataArrayforID($inID){
	
	intiateDatabaseConnection();
	$myPageArray = array();
	$mySQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $inID . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			$myPageArray[0] = $row["page_id"];	
			$myPageArray[1] = unsanitiseInput($row["page_name"]);	
			$myPageArray[2] = unsanitiseInput($row["page_admin"]);	
			$myPageArray[3] = unsanitiseInput($row["page_content"]);	
			$myPageArray[4] = unsanitiseInput($row["page_header"]);	
			$myPageArray[5] = unsanitiseInput($row["page_menu"]);
		}
	}
	closeDatabaseConnection();
	return $myPageArray;
}

function outputListofPages(){
	
	$myContent = "";
	global $requestURL;
	$myPageNameArray = array();
	$myPageNameArray = getListofPages();
	
	for($i = 0; $i < count($myPageNameArray); $i+=2){
		$myContent = $myContent . '<a href="' . $requestURL . '/?pid=' . $myPageNameArray[$i] .'"><div class="page-list-item">';
		$myContent = $myContent . '<p>' . $myPageNameArray[$i + 1] . '</p>';
		$myContent = $myContent . '</div>';
	}
	
	return $myContent;
}

function CreatePage(){
	$myPageID = getFirstAvaliablePageID();
	$myPageName = "New Page";
	$myPageAdmin = 0;
	$myPageContent = "";
	$myPageHeader = "";
	$myPageMenu = "New Page";

	intiateDatabaseConnection();

	$SQL = "INSERT INTO `mcms_pages` (`page_id`, `page_name`, `page_admin`, `page_content`, `page_header`, `page_menu`) VALUES ('". $myPageID . "', '" . $myPageName . "', '" . $myPageAdmin . "', '" . $myPageContent . "', '" . $myPageHeader . "', '" . $myPageMenu . "');";
	$result = mysql_query($SQL);
	$UserAdded = true;
	closeDatabaseConnection();
}

function updatePage(){
	intiateDatabaseConnection();
	$myPageID = $_POST['pageID'];
	$myPageName = sanitiseInput($_POST['pageName']);
	$myPageContent = sanitiseInput($_POST['pageContent']);
	$myPageMenu = sanitiseInput($_POST['pageMenu']);
	$myPageHeader = sanitiseInput($_POST['pageHeader']);
	
		
	$SQL = "UPDATE `mcms_pages` SET `page_id` = '" . $myPageID . "', `page_name` = '" . $myPageName . "', `page_content` = '" . $myPageContent . "', `page_menu` = '" . $myPageMenu . "', `page_header` = '" . $myPageHeader . "' WHERE `mcms_pages`.`page_id` = '" . $myPageID . "';";
	$result = mysql_query($SQL);
	$UserUpdated = true;
	closeDatabaseConnection();
}

function outputPageForm($pageIDIn){
	if($pageIDIn == -1){
		
	}
	else{
		
		$myPageData = getPageDataArrayforID($pageIDIn);
		
		$PageForm = '<FORM NAME ="form1" METHOD ="POST" ACTION = "">
	
		<p class="flat-text">Page Name: </p>
		<INPUT class="input-editor-box-reg" TYPE = "TEXT" VALUE ="' . $myPageData[1] . '" NAME = "pageName">
	
		<p class="flat-text">Page Content: </p>
		<textarea rows="50" class="input-editor-box-big" TYPE = "TEXT" NAME = "pageContent">' . $myPageData[3] . '</textarea>
	
		<p class="flat-text">Page Header: </p>
		<INPUT class="input-editor-box-reg" TYPE = "TEXT" VALUE ="' . $myPageData[4] . '" NAME = "pageHeader">
	
		<p class="flat-text">Menu Display Name: </p>
		<INPUT class="input-editor-box-reg" TYPE = "TEXT" VALUE ="' . $myPageData[5] . '" NAME = "pageMenu">
	
		<input type="HIDDEN" name="pageID" value="' . $pageIDIn . '">
		
		<p></p>
		<INPUT class="input-login-button" TYPE = "Submit" Name = "UpdatePage" VALUE = "Update">
		
		</FORM>';
	}
	
	return $PageForm;
}

if (isset($_POST['UpdatePage'])) {
	updatePage();	
}

if (isset($_POST['CreatePage'])) {
	CreatePage();	
}

if(isset($_GET['pid'])){
	$myPageID = $_GET['pid'];
	$pageContent = $pageContent . outputPageForm($myPageID);
}
else{
	$pageContent = $pageContent . outputListofPages();
	
	
	$pageContent = $pageContent . '<FORM NAME ="form1" METHOD ="POST" ACTION = ""><p></p>
	<INPUT class="input-login-button" TYPE = "Submit" Name = "CreatePage" VALUE = "New Page">
	</FORM>';
}
?>