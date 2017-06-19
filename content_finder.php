
<div class="main-content-section">
<?php 
$pageTitle = str_replace("-", " ", $pageTitle);
if(doesPageExist($pageTitle)){
	
	$pageContent = getPageContent($pageTitle);
	checkUserPriviledge(getPagePrivilledge($pageTitle), $loggedin, $baseURL, $adminFlag, $loginFlag);
	contentOutput($pageContent);
}
else if($postFlag == true){
	outputPostAsPage($pageTitle);	
}
else if($adminFlag == true){
	if(file_exists ('content/admin/' . $pageTitle . '.php')){
		include 'content/admin/' . $pageTitle . '.php'; 
		checkUserPriviledge($adminpage, $loggedin, $baseURL, $adminFlag, $loginFlag);
		contentOutputNoShortcode($pageContent);
	}
	if($pageTitle == "404"){
		header('Location:' . 'http://' . $baseURL);
		die();
	}
}
else{
	if($pageTitle == "404"){
		echo '<h1>404</h1>';
		echo '<h3>Oops Page Not Found</h3>';	
	}
}
?>
</div>