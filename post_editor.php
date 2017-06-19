<?php

function getListofPosts(){
	$mycontentarray = array();
	intiateDatabaseConnection();
	$SQL = "SELECT * FROM `mcms_posts` ORDER BY `mcms_posts`.`post_id` ASC";
	$result = mysql_query($SQL);
	$myCount = 0;
	if( mysql_num_rows($result) > 0){
		while($row = mysql_fetch_assoc($result)) {
			$mycontentarray[$myCount] = $row["post_id"];	
			$mycontentarray[$myCount + 1] = $row["post_name"];	
				
			$myCount+=2; 
		}
	}
	closeDatabaseConnection();
	return $mycontentarray;
}

function getFirstAvaliablePostID(){
	$startSearching = true;
	$checkID = 1;
	intiateDatabaseConnection();
	while($startSearching == true){
		
		$SQL = "SELECT * FROM `mcms_posts` WHERE post_id='" . $checkID . "' LIMIT 1";
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

function doesPostExistForID($IDin){
	intiateDatabaseConnection();
		
	$SQL = "SELECT * FROM `mcms_posts` WHERE post_id='" . $IDin . "' LIMIT 1";
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

function getpostDataArrayforID($inID){
	
	intiateDatabaseConnection();
	$mypostArray = array();
	$mySQL = "SELECT * FROM `mcms_posts` WHERE post_id='" . $inID . "' LIMIT 1";
	$myresult = mysql_query($mySQL);
	if( mysql_num_rows($myresult) > 0){
		while($row = mysql_fetch_assoc($myresult)) {
			$mypostArray[0] = $row["post_id"];	
			$mypostArray[1] = unsanitiseInput($row["post_name"]);	
			$mypostArray[2] = unsanitiseInput($row["post_excerpt"]);	
			$mypostArray[3] = unsanitiseInput($row["post_content"]);	
			$mypostArray[4] = unsanitiseInput($row["post_tags"]);	
			$mypostArray[5] = unsanitiseInput($row["post_date"]);
			$mypostArray[6] = unsanitiseInput($row["post_icon"]);
		}
	}
	closeDatabaseConnection();
	return $mypostArray;
}

function outputListofPosts(){
	
	$myContent = "";
	global $requestURL;
	$mypostNameArray = array();
	$mypostNameArray = getListofPosts();
	
	for($i = 0; $i < count($mypostNameArray); $i+=2){
		$myContent = $myContent . '<a href="' . $requestURL . '/?dt=1&pid=' . $mypostNameArray[$i] .'"><div class="post-list-item">';
		$myContent = $myContent . '<p>' . $mypostNameArray[$i + 1] . '</p>';
		$myContent = $myContent . '</div>';
	}
	
	return $myContent;
}

function CreatePost(){
	$myPostID = getFirstAvaliablePostID();
	$myPostName = "New Post";
	$myPostExcerpt = "";
	$myPostContent = "";
	$myPostTags = "";
	$myPostIcon = "";
	$myPostDate = "";

	intiateDatabaseConnection();

	$SQL = "INSERT INTO `mcms_posts` (`post_id`, `post_name`, `post_excerpt`, `post_content`, `post_tags`, `post_date`, `post_icon`) VALUES ('". $myPostID . "', '" . $myPostName . "', '" . $myPostExcerpt . "', '" . $myPostContent . "', '" . $myPostTags . "', '" . $myPostDate . "', '" . $myPostIcon . "');";
	$result = mysql_query($SQL);
	$UserAdded = true;
	closeDatabaseConnection();
}

function updatePost(){
	intiateDatabaseConnection();
	$myPostID = $_POST['postID'];
	$myPostName = sanitiseInput($_POST['postName']);
	$myPostExcerpt = sanitiseInput($_POST['postExcerpt']);
	$myPostContent = sanitiseInput($_POST['postContent']);
	$myPostTags = sanitiseInput($_POST['postTags']);
	$myPostIcon = sanitiseInput($_POST['postIcon']);
	$myPostDate = sanitiseInput($_POST['postDate']);
	
		
	$SQL = "UPDATE `mcms_posts` SET `post_id` = '" . $myPostID . "', `post_name` = '" . $myPostName . "', `post_tags` = '" . $myPostTags . "', `post_date` = '" . $myPostDate . "', `post_content` = '" . $myPostContent . "', `post_excerpt` = '" . $myPostExcerpt . "', `post_icon` = '" . $myPostIcon . "' WHERE `mcms_posts`.`post_id` = '" . $myPostID . "';";
	$result = mysql_query($SQL);
	$UserUpdated = true;
	closeDatabaseConnection();
}



function outputPostForm($postIDIn){
	if($postIDIn == -1){
		
	}
	else{
		
		$myPostData = getPostDataArrayforID($postIDIn);
		
		$PostForm = '<FORM NAME ="form1" METHOD ="POST" ACTION = "">
	
		<p class="flat-text">Post Name: </p>
		<INPUT class="input-editor-box-reg" TYPE = "TEXT" VALUE ="' . $myPostData[1] . '" NAME = "postName">
		
		<p class="flat-text">Post Excerpt: </p>
		<textarea rows="10" class="input-editor-box-big" TYPE = "TEXT" NAME = "postExcerpt">' . $myPostData[2] . '</textarea>
	
		<p class="flat-text">Post Content: </p>
		<textarea rows="50" class="input-editor-box-big" TYPE = "TEXT" NAME = "postContent">' . $myPostData[3] . '</textarea>
	
		<p class="flat-text">Post Tags: </p>
		<INPUT class="input-editor-box-reg" TYPE = "TEXT" VALUE ="' . $myPostData[4] . '" NAME = "postTags">
		
		<p class="flat-text">Post Icon: </p>
		<INPUT class="input-editor-box-reg" TYPE = "TEXT" VALUE ="' . $myPostData[6] . '" NAME = "postIcon">
	
		<p class="flat-text">Post Date: </p>
		<INPUT class="input-editor-box-reg" TYPE = "DATE" VALUE ="' . date('Y-d-m',strtotime($myPostData[5])) . '" NAME = "postDate">
	
		<input type="HIDDEN" name="postID" value="' . $postIDIn . '">
		
		<p></p>
		<INPUT class="input-login-button" TYPE = "Submit" Name = "UpdatePost" VALUE = "Update">
		
		</FORM>';
	}
	
	return $PostForm;
}

if (isset($_POST['UpdatePost'])) {
	updatePost();	
}

if (isset($_POST['CreatePost'])) {
	CreatePost();	
}

if(isset($_GET['pid'])){
	$myPostID = $_GET['pid'];
	$pageContent = $pageContent . outputPostForm($myPostID);
}
else{
	$pageContent = $pageContent . outputListofPosts();
	
	
	$pageContent = $pageContent . '<FORM NAME ="form1" METHOD ="POST" ACTION = ""><p></p>
	<INPUT class="input-login-button" TYPE = "Submit" Name = "CreatePost" VALUE = "New Post">
	</FORM>';
}
?>