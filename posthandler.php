<?php
	class postTag{
		
		var $tagName;
		var $postArray = array();
		
		//This checks what tag this tag array is
		function isThisTag($stringIn){
			if($stringIn == $this->tagName){
				return true;	
			}
			return false;
		}
		
		//This Sets the Tag of a post array
		function setTagName($stringIn){
			$this->tagName = $stringIn;
		}
		
		//This returns the post array for this particular tag
		function getPostArray(){
			return $this->postArray;
		}
		
		//This adds a post to this set of tags
		function addPost($postName){
			$arraySize = count($this->postArray);
			$this->postArray[$arraySize] = $postName;
		}
	}
	
	//This checks whether we have already registered a tag
	function doesTagExist($tagName){
		global $postTagRegistry;
		foreach ($postTagRegistry as $myPostTag) {
			if($myPostTag->isThisTag($tagName) == true){
				return true;
			}
		}
		return false;
	}
	
	//This adds a new tag to our tag registry
	function addTagToRegistry($tagName){
		global $postTagRegistry;
		$arraySize = count($postTagRegistry);
		$myNewTag = new postTag();
		$myNewTag->setTagName($tagName);
		$postTagRegistry[$arraySize] = $myNewTag;
	}
	
	//This returns a tag for a particular string
	function getTagForName($tagName){
		global $postTagRegistry;
		foreach ($postTagRegistry as $myPostTag) {
			if($myPostTag->isThisTag($tagName) == true){
				return $myPostTag;
			}
		}
	}
	
	//this returns a list of posts for multiple tags
	function getCombinedPostLists($tagArray){
		$newPostArray = array();
		for($i = 0; $i < count($tagArray); $i++){
			if(doesTagExist($tagArray[$i])){
				$tempTag = (getTagForName($tagArray[$i]));
				$tempTagArray = $tempTag->getPostArray();
				for($x = 0; $x < count($tempTagArray); $x++){
					if(in_array($tempTagArray[$x], $newPostArray) == false){
						$newPostArray[count($newPostArray)] = $tempTagArray[$x];
					}
				}
			}
		}
		return $newPostArray;
	}
	
	//this adds a post to our tag registry
	function addPostToTagRegistry($postFileName){
		global $postTagRegistry;
		
		prepPostforPosting($postFileName);

		$postTagArray = explode("," , $GLOBALS['post_tags']);
		
		for($i = 0; $i < count($postTagArray); $i++){
			if(doesTagExist($postTagArray[$i]) == false){
				addTagToRegistry($postTagArray[$i]);
			}
			if(doesTagExist($postTagArray[$i]) == true){
				$myTag = getTagForName($postTagArray[$i]);
				$myTag->addPost($postFileName);
			}
		}
	}
	
	//this checks whether a post exists from a filename
	function doesPostExistFromName($postName){
		
		intiateDatabaseConnection();
		$SQL = "SELECT * FROM `mcms_posts` WHERE post_name='" . $postName . "' LIMIT 1";
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
	
	//this outputs our posts content as a page
	function outputPostAsPage($postName){
		
		intiateDatabaseConnection();
		prepPostforPosting($postName);
		newComment();
		deleteComment();
		$GLOBALS['post_comments'] = buildCommentArray($GLOBALS['post_id']);
		closeDatabaseConnection();
		
		echo '<h2 class="post-title">' . $GLOBALS['post_title'] . '</h2>';
		echo '<div class="small-grey-text">Date: ' . str_replace("-", "/", $GLOBALS['post_date']) . '</div>';
		echo '<div class="small-grey-text">Tags: ' . str_replace(",", ", ", $GLOBALS['post_tags']) . '</div>';
		contentOutput($GLOBALS['post_content']);
		
		$myTempCommentArray = array();
		$myTempCommentArray = $GLOBALS['post_comments'];
		
		if(sizeof($myTempCommentArray) > 0){
		echo '<hr>';
		echo '<h2>Comments</h2>';
		echo '<hr>';
		
		for($i = 0; $i < sizeof($myTempCommentArray); $i+=5){
			if($myTempCommentArray[$i + 2] == 0){
				echo '<div class="post-comment">';
				echo '<p>' . $myTempCommentArray[$i] . '</p><div class="small-grey-text">' . $myTempCommentArray[$i + 3] . '</div>';
				echo '<p>' . $myTempCommentArray[$i + 1] . '</p>';
				/*if($loggedin == true){
					echo '<FORM NAME ="commentform" METHOD ="POST" ACTION = "">
					<INPUT class="input-login-button" TYPE = "Submit" Name = "commentDelete" VALUE = "Post">
					</form>	';
				}*/
				if($GLOBALS['successfulLogin'] == true){
					echo '<FORM NAME ="commentform" METHOD ="POST" ACTION = "">
					<input type="HIDDEN" name="commentID" value="' . $myTempCommentArray[$i + 4] . '">
					<INPUT class="input-login-button" TYPE = "Submit" Name = "commentDelete" VALUE = "Delete">
					</form>	';
				}
				echo '<hr>';
				echo '</div>';
			}
			else{
				echo '<div class="post-comment">';
				echo '<p>' . $myTempCommentArray[$i] . ' - <b><r>Site Admin</r></b></p><div class="small-grey-text">' . $myTempCommentArray[$i + 3] . '</div>';
				echo '<p>' . $myTempCommentArray[$i + 1] . '</p>';
				if($GLOBALS['successfulLogin'] == true){
					echo '<FORM NAME ="commentform" METHOD ="POST" ACTION = "">
					<input type="HIDDEN" name="commentID" value="' . $myTempCommentArray[$i + 4] . '">
					<INPUT class="input-login-button" TYPE = "Submit" Name = "commentDelete" VALUE = "Delete">
					</form>	';
				}
				echo '<hr>';
				echo '</div>';
			}
		}
		}
		
		echo '<h2>Leave a Comment</h2>';
		echo '<FORM NAME ="commentform" METHOD ="POST" ACTION = "" id="commentform">
		<p>Name:</p>
		<INPUT class="input-login-box" TYPE = "TEXT" VALUE ="" NAME = "commentName">
		<p>Comment:</p>
		<textarea rows="10" class="input-editor-box-big" TYPE = "TEXT" NAME = "commentContent"></textarea>
		<p></p>
		</form>
		<button type="submit" form="commentform" Name = "commentSubmit" value="commentSubmit">Post</button>
		';
	}
	
	//function to handle adding a new comment
	function newComment(){
		if (isset($_POST['commentSubmit'])) {
			$myCommentID = getFirstAvaliableCommentID();
			$myCommentName = sanitiseInput($_POST['commentName']);
			$myCommentContent = sanitiseInput(strip_tags($_POST['commentContent']));
			addComment($myCommentID, $myCommentName, $myCommentContent);
			header('Location:'.$_SERVER['REQUEST_URI']);
		}
	}
	
	function deleteComment(){
		if (isset($_POST['commentDelete'])) {
			$myCommentID = sanitiseInput($_POST['commentID']);
			$SQL = "DELETE FROM `mcms_comments` WHERE `mcms_comments`.`comment_id` = " . $myCommentID;
			$result = mysql_query($SQL);
			header('Location:'. $_SERVER['REQUEST_URI']);
		}	
	}
	
	function getFirstAvaliableCommentID(){
		$startSearching = true;
		$checkID = 1;
		while($startSearching == true){
		
			$SQL = "SELECT * FROM `mcms_comments` WHERE comment_id='" . $checkID . "' LIMIT 1";
			$result = mysql_query($SQL);
			//if we have got more than 0 row returned
				if( mysql_num_rows($result) > 0){
				$checkID = $checkID + 1;
			}
			else{
				$startSearching = false;
				return $checkID;	
			}
		}
	}
	
	function addComment($IDin, $Namein, $Commentin){
		if($GLOBALS['successfulLogin'] == true){
			$SQL = "INSERT INTO `mcms_comments` (`comment_id`, `comment_name`, `comment_content`, `comment_verified`, `comment_date`, `comment_post_id`) VALUES ('". $IDin . "', '" . $Namein . "', '" . $Commentin . "', '1', '" . date("c") . "', '" . $GLOBALS['post_id'] . "');";
			$result = mysql_query($SQL);
		}
		else{
			$SQL = "INSERT INTO `mcms_comments` (`comment_id`, `comment_name`, `comment_content`, `comment_verified`, `comment_date`, `comment_post_id`) VALUES ('". $IDin . "', '" . $Namein . "', '" . $Commentin . "', '0', '" . date("c") . "', '" . $GLOBALS['post_id'] . "');";
			$result = mysql_query($SQL);
		}
	}
	
	//this will return an array of post names from our database
	function retrieveArrayofPostNamesFromDatabase(){
		$postNameArray = array();
		intiateDatabaseConnection();
		$SQL = "SELECT * FROM `mcms_posts` ORDER BY `mcms_posts`.`post_date` DESC";
		$result = mysql_query($SQL);
		$myCount = 0;
		if( mysql_num_rows($result) > 0){
			while($row = mysql_fetch_assoc($result)) {
				$postNameArray[$myCount] = $row["post_name"];	
				$myCount++; 
			}
		}
		closeDatabaseConnection();
		return $postNameArray;
	}
	
	//we gotta make an array out of our comments
	function buildCommentArray($postIDin){
		
		$myCommentArray = array();
		$check = 0;
		
		$SQL = "SELECT * FROM `mcms_comments` WHERE comment_post_id='" . $postIDin . "'";

		$result = mysql_query($SQL);
		if( mysql_num_rows($result) > 0){
			 while($row = mysql_fetch_assoc($result)) {
				 $myCommentArray[$check] = unsanitiseInput($row["comment_name"]);
				 $myCommentArray[$check + 1] = unsanitiseInput($row["comment_content"]);
				 $myCommentArray[$check + 2] = $row["comment_verified"];
				 $myCommentArray[$check + 3] = $row["comment_date"];
				 $myCommentArray[$check + 4] = $row["comment_id"];
				 
				 $check +=5;
			 }
		}
		
		return $myCommentArray;
	}
	
	//this sets up our variable for the current post
	function prepPostforPosting($myPostName){
		
		$GLOBALS['post_title']="";
		$GLOBALS['post_excerpt'] = "";
		$GLOBALS['post_content'] = "";
		$GLOBALS['post_tags']="";
		$GLOBALS['post_date']="";
		$GLOBALS['post_id']="";
		$GLOBALS['post_icon']="";
		$GLOBALS['post_comments'] = array();
		
		$SQL = "SELECT * FROM `mcms_posts` WHERE post_name='" . $myPostName . "' LIMIT 1";
		$result = mysql_query($SQL);
		if( mysql_num_rows($result) > 0){
			//check our rows we have got
			 while($row = mysql_fetch_assoc($result)) {
				 $GLOBALS['post_title'] = $row["post_name"];
				 $GLOBALS['post_excerpt'] = $row["post_excerpt"];
				 $GLOBALS['post_content'] = $row["post_content"];
				 $GLOBALS['post_tags'] = $row["post_tags"];
				 $GLOBALS['post_date'] = $row["post_date"];
				 $GLOBALS['post_id'] = $row["post_id"];
				 $GLOBALS['post_icon'] = $row["post_icon"];
			 }
		}
	}
	
	//Variable for Posts
	global $post_title;
	global $post_excerpt;
	global $post_content;
	global $post_tags;
	global $post_date;
	global $post_id;
	global $post_comments;
	global $post_icon;
	
	
	//$dir = "content/posts/";
	//$postFiles = scandir($dir, 1);
	$postTagRegistry = array();
	$postFileArray = retrieveArrayofPostNamesFromDatabase();
	
	intiateDatabaseConnection();
	for($i = 0; $i < count($postFileArray); $i++){
		addPostToTagRegistry($postFileArray[$i]);
	}
	closeDatabaseConnection();
	
	
?>