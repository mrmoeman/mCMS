<?php
	class postsShortCode extends blankShortCode{
		function isShortCodeValid($shortCodeIn){
			if (strpos($shortCodeIn, 'SHOWPOSTS') !== false) {
				return true;	
			}
			return false;
		}
		
		function doShortCode($shortCodeIn){
			
			$myShortCodeArray = explode(" ",$shortCodeIn);
			$tagArray = array();
			
			for($i = 0; $i < count($myShortCodeArray); $i ++){
					if($myShortCodeArray[$i] == "TAG="){
						$i++;
						$tempArray =  explode("," , $myShortCodeArray[$i]);
						for($x = 0; $x < count($tempArray); $x ++){
							$tagArray[count($tagArray)] = $tempArray[$x];
							
						}
					}
			}
			$postArray = array();
			if(count($tagArray) > 0){
				$postArray = getCombinedPostLists($tagArray);
			}
			else{
				$postArray = retrieveArrayofPostNamesFromDatabase();
			}

			intiateDatabaseConnection();
			for($i = 0; $i < count($postArray); $i ++){
				postOutput($postArray[$i]);
				if($i < count($postArray) - 1){
					echo '<hr class="post-line">';	
				}
			}
			closeDatabaseConnection();
			
		}
	}
	
	function postOutput($postName){
		prepPostforPosting($postName);
		
		
		echo "<h2>" . $GLOBALS['post_title']  . "</h2>";
		global $baseURL;
		global $postsPageURL;
		
		echo '<div class="split-1-3-S split-top">';
		echo '<img class="project-thumb" style="background-image:url(' . "'http://" . $baseURL . 'images/' . $GLOBALS['post_icon'] . "'" . ');" src="http://' . $baseURL . 'images/square_blank.png">';
		

		echo '</div>';
		echo '<div class="split-2-3-S split-top">';
		
		echo '<div class="post_excerpt_text">';
		echo '<p>' . $GLOBALS['post_excerpt'] . '</p>';
		echo '<div style="width: 100%; text-align:center;">';
		$postIdentifierURL = "http://" . $baseURL . $postsPageURL ."/" . str_replace(" ","-",$GLOBALS['post_title']);
		echo '<a href="' . $postIdentifierURL . '"><button>Read More</button></a>';
		echo '</div>';
		echo '</div>';
		
		echo '</div>';
		
	}
	
	$myPostsShortCode = new postsShortCode();
	addShortCode($myPostsShortCode);

?>