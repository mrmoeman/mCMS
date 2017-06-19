<?php
include "loginform.php";
$adminpage = false;
$pageContent = "";
if($loggedin == false){
	$pageContent = $loginFormOutput;
}
else{
	$pageContent = $pageContent . $loginFormOutput;
	if(isset($_GET['dt'])){
		$myDashboardType = $_GET['dt'];
		if($myDashboardType == 1){
			include "post_editor.php";
		}
	}
	else{
		include "page_editor.php";
	}

}
 ?>