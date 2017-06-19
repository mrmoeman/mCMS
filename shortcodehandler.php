<?php

function addShortCode($myNewShortCode){
	global $shortCodeRegistry;
	$arraySize = count($shortCodeRegistry);
	$shortCodeRegistry[$arraySize] = $myNewShortCode;
}

function checkAndRunShortCode($shortCodeInput){
	global $shortCodeRegistry;
	foreach ($shortCodeRegistry as $myShortCode) {
		if($myShortCode->isShortCodeValid($shortCodeInput) == true){
			$myShortCode->doShortCode($shortCodeInput);
		}
	}
}

function buildShortCodeRegistry(){
	$dir = "content/shortcodeplugins";
	$shortCodeFolders = scandir($dir, 1);
	global $shortCodeRegistry;
	
	for($i = 0; $i < count($shortCodeFolders); $i++){
		if (is_dir($dir) == true){
			if($shortCodeFolders[$i] != '.' && $shortCodeFolders[$i] != '..'){
				include 'content/shortcodeplugins/' . $shortCodeFolders[$i] . '/index.php';
			}
		}
	}
	
}
$shortCodeRegistry = array();
buildShortCodeRegistry();


?>