<?php

function contentOutput($content){
	$contentArray = explode ( '##' , $content);
	
	for($i = 0; $i < count($contentArray); $i++){
		if(($i + 1) % 2 == 0){
			checkAndRunShortCode($contentArray[$i]);
		}
		else{
			echo unsanitiseInput($contentArray[$i]);
		}
		
	}
}

function contentOutputNoShortcode($content){
	$contentArray = explode ( '####' , $content);
	
	for($i = 0; $i < count($contentArray); $i++){
		if(($i + 1) % 2 == 0){
			
		}
		else{
			echo unsanitiseInput($contentArray[$i]);
		}
		
	}
}

function sanitiseInput($myString){
	
	$myString = str_replace("'", "&#39;", $myString);
	$myString = str_replace('"', "&#34;", $myString);
	
	return $myString;
}

function unsanitiseInput($myString){
	
	$myString = str_replace("&#39;", "'", $myString);
	$myString = str_replace('&#34;', '"', $myString);
	
	return $myString;
}

function getPageMenuforID($myID){
	$mySQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $myID . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			return $row["page_menu"];	 
		}
	}
	return "";
}

function getPageNameforID($myID){
	$mySQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $myID . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			return $row["page_name"];	 
		}
	}
	return "";
}

function getPageHeaderStateforID($myID){
	$mySQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $myID . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	$headerState = "";
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			$headerState = $row["page_header"];	 
		}
	}
	
	$headersize = count(explode(",", $headerState));

	if(empty($headerState)){
		return 0;
	}
	else if($headersize > 1){
		return 2;
	}
	else{
		return 1;	
	}
}

function getPageHeaderforID($myID){
	$mySQL = "SELECT * FROM `mcms_pages` WHERE page_id='" . $myID . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			return $row["page_header"];	 
		}
	}
	return "";
}

function getPageIDforName($myName){
	$mySQL = "SELECT * FROM `mcms_pages` WHERE page_name='" . $myName . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			return $row["page_id"];	 
		}
	}
	return "";
}

function getPageContent($pageTitle){
	intiateDatabaseConnection();
	$myContent = "";
	$SQL = "SELECT * FROM `mcms_pages` WHERE page_name='" . $pageTitle . "' LIMIT 1";
	$result = mysql_query($SQL);
	if( mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)) {
			$myContent = $row["page_content"];	 
		}
	}
	closeDatabaseConnection();
	return $myContent;
}

function getPagePrivilledge($pageTitle){
	intiateDatabaseConnection();
	$myContent = "";
	$SQL = "SELECT * FROM `mcms_pages` WHERE page_name='" . $pageTitle . "' LIMIT 1";
	$result = mysql_query($SQL);
	if( mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)) {
			$myContent = $row["page_admin"];	 
		}
	}
	closeDatabaseConnection();
	return $myContent;
}

function doesPageExist($pageTitle){
	intiateDatabaseConnection();
	$pageTitle = str_replace("-", " ", $pageTitle);
	$myContent = "";
	$SQL = "SELECT * FROM `mcms_pages` WHERE page_name='" . $pageTitle . "' LIMIT 1";
	$result = mysql_query($SQL);
	if( mysql_num_rows($result) > 0){
			closeDatabaseConnection();
			return true; 
	}
	else{
			closeDatabaseConnection();
			return false;
	}
}

function checkUserPriviledge($isadminpage, $isloggedin, $baseURL, $adminflag, $loginflag){
	if($isadminpage == true || ($adminflag == true && $loginflag == false)){
		if($isloggedin == false){
			header('Location:' . 'http://' . $baseURL);
			die();
		}
	}
}

?>