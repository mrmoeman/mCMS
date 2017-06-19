
<?php
$requestURL = $_SERVER['REQUEST_URI'];
$myTempStringArray = explode("/?", $requestURL);
$requestURL = $myTempStringArray[0];
$requestURLArray = explode( "/" , $requestURL);
$baseURL = $_SERVER['SERVER_NAME'] . "/";

include 'content/config.php';
include 'blankshortcode.php';
include 'databaseconnection.php';
include 'loginchecker.php';
include 'contenthelper.php';

//$baseURL = $_SERVER['SERVER_NAME'] ."/";
$pageTitle = $homePageName;
$sliderpage = FALSE;
$myArraySize = count ($requestURLArray);
$postFlag = false;
$adminFlag = false;
$loginFlag = false;
$shouldrediect = false;
$addiontalURLOffset = 0;

if($isSiteLocal == true){
	$addiontalURLOffset = 1;
}

if($myArraySize > 1 + $addiontalURLOffset){
	if($requestURLArray[1 + $addiontalURLOffset] != ""){
		$pageTitle = $requestURLArray[$myArraySize - 1];
	}
	if($requestURLArray[1 + $addiontalURLOffset] == $homePageName){
		header('Location:' . 'http://' . $baseURL);
		die();
	}
}

if($myArraySize > 2 + $addiontalURLOffset){
	if($requestURLArray[2 + $addiontalURLOffset] != ""){
		$pageTitle = str_replace("-", " ",$requestURLArray[$myArraySize - 1]);
		if($requestURLArray[1 + $addiontalURLOffset] == $postsPageURL){
			$postFlag = true;
		}
		if($requestURLArray[1 + $addiontalURLOffset] == "Admin"){
			$adminFlag = true;
		}
		if($requestURLArray[2 + $addiontalURLOffset] == "Dashboard"){
			$loginFlag = true;
		}
	}
}


include 'shortcodehandler.php';
include 'posthandler.php';

if($postFlag == false && $adminFlag == false){
	if(doesPageExist($pageTitle)){}
	else{
		if($pageTitle == "404"){

		}else{
			header('HTTP/1.0 404 not found');
			$pageTitle = '404';
		}
	}
}
else if($adminFlag == true){
	if(file_exists ('content/admin/' . $pageTitle . '.php')){}
	else{
		if($pageTitle == "404"){

		}else{
			header('HTTP/1.0 404 not found');
			$pageTitle = '404';
		}
	}
}
else{
	if(doesPostExistFromName($pageTitle) == false){
		header('HTTP/1.0 404 not found');
		$pageTitle = '404';
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">


<title><?php echo $pageTitle; ?> - <?php echo $SiteName; ?></title>
<?php include 'metainclude.php' ?>
<html>
<link href="http://<?php echo $baseURL; ?>/css/main.css" rel="stylesheet" type="text/css" />
<link href="http://<?php echo $baseURL; ?>css/split.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<script src="http://<?php echo $baseURL; ?>js/mobilemenu.js"></script>
<header>
<?php include 'header.php' ?>
</header>
    
<div class="content"> 
<?php include 'content_finder.php' ?>
</div>

<footer>
<?php include 'footer.php' ?>
</footer>

<script>calculateMobileMenuHeight();</script>

</html>