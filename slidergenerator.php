<?php

function generateSlider($baseURL, $sliderString){
	
	$SliderItemArray = explode(",", $sliderString);
	$sliderOutput = "";
	
	$sliderOutput = $sliderOutput . '<script src="js/slider-fade.js"></script>';
	$sliderOutput = $sliderOutput .  '<script> setImageNumber(' . ((count ($SliderItemArray))) . ',"images/"); </script>';
	$sliderOutput = $sliderOutput .  '<div class="header-main">';
	$sliderOutput = $sliderOutput .  '<div class="slider-container">';
	$sliderOutput = $sliderOutput .  '<img id="fading-banner" class="banner-image header-image-container" src="http://' . $baseURL . 'images/banner-blank.png" alt="">';
	for($i = 0; $i < count ($SliderItemArray); $i++){
		$slider_image_src = $SliderItemArray[$i];
		$opacity = 0;
		if($i == 0){
			$opacity = 1;
		}
	
		$sliderOutput = $sliderOutput .  '<img id="head-' . ($i + 1) . '" class="visible-header head-' . ($i + 1) . '" src="http://' . $baseURL . 'images/' . $slider_image_src . '">';
	
	}
	$sliderOutput = $sliderOutput .  '</div>';
	$sliderOutput = $sliderOutput .  '</div>';
	$sliderOutput = $sliderOutput .  '<script>fadeslider();</script>';
	
	return $sliderOutput;
}
?>