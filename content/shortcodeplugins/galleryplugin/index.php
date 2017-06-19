<?php
	class galleryShortCode extends blankShortCode{
		function isShortCodeValid($shortCodeIn){
			if (strpos($shortCodeIn, 'EASYGALLERY') !== false) {
				return true;	
			}
			return false;
		}
		
		function doShortCode($shortCodeIn){
			$tempArray = explode(" ", $shortCodeIn);
			if(count($tempArray) > 1){
				echo '<div class="split split-center">';
				for($i = 1; $i < count($tempArray); $i++){
					global $baseURL;
					$ImageURL = "http://" . $baseURL . "images/" . $tempArray[$i];
					$BlankURL = "http://" . $baseURL . "images/square_blank.png";
					echo "<div class=\"split-1-3-S image-grid\"><div onclick=\"showPopupImage('" . $ImageURL . "')\"><img class=\"project-thumb\" style=\"background-image:url(" . $ImageURL . ");\" src=\"" . $BlankURL . "\"></div></div>";
				}
				echo '</div>';
			}
			
		}
	}
	
	
	$myPostsShortCode = new galleryShortCode();
	addShortCode($myPostsShortCode);

?>