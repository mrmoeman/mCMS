<?php
	class videoShortCode extends blankShortCode{
		function isShortCodeValid($shortCodeIn){
			if (strpos($shortCodeIn, 'EASYVIDEO') !== false) {
				return true;	
			}
			return false;
		}
		
		function doShortCode($shortCodeIn){
			$tempArray = explode(" ", $shortCodeIn);
			if(count($tempArray) > 1){
				echo '<div style="padding: 1%; text-align:center;">';
				echo'<div class="videoWrapper">';
    			echo'<iframe width="940" height="529" src=	"' . $tempArray[1] . '" frameborder="0" allowfullscreen=""></iframe>';
				echo'</div>';
				echo '</div>';
			}
			
		}
	}
	

	
	$myPostsShortCode = new videoShortCode();
	addShortCode($myPostsShortCode);

?>