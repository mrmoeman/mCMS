<?php

$loginFormOutput ="";
$loginFormOutput = $loginFormOutput . '<div class="log-in-box-container">';
$loginFormOutput = $loginFormOutput .  '<div class="log-in-box">';
if ($loggedin == false){
	//POST is for hidden stuff, GET is for non hidden stuff
	//Action tells us where we go after the form is submitted
	if ($loginfail == true && $UserDisabled == false){
		$loginFormOutput = $loginFormOutput .  '<p>Incorrect Username or Password.</p>';	
	}
	if ($UserDisabled == true){
		$loginFormOutput = $loginFormOutput .  '<p>This User has been disabled due to multiple failed logins.</p>';	
		$loginFormOutput = $loginFormOutput .  '<p>Please contact the administrator.</p>';
	}
	
	$loginFormOutput = $loginFormOutput . '<FORM NAME ="form1" METHOD ="POST" ACTION = "">
    <div class="split split-top" style="padding:1%;">
    <div class="split-1-2 split-top">
    <p class="flat-text">Username: </p>
    </div>
	<div class="split-1-2 split-top">
	<INPUT class="input-login-box" TYPE = "TEXT" VALUE ="" NAME = "username">
	</div>
    </div>
    <div class="split split-top" style="padding:1%;">
    <div class="split-1-2 split-top">
	<p class="flat-text">Password: </p>
    </div>
	<div class="split-1-2 split-top">
	<INPUT class="input-login-box" TYPE = "password" VALUE ="" NAME = "password">
	</div>
    </div>
	<!--name for our submit button is what we check when we load the page-->
	<div class="split-center" style="padding:1%;">
	<INPUT class="input-login-button" TYPE = "Submit" Name = "Submit1" VALUE = "Login">
	</div>
	</FORM>';
	$loginFormOutput = $loginFormOutput .  '</div></div>';
}
else{
	/*$loginFormOutput = $loginFormOutput . '<div class="split-center" style="padding:1%;">
	<p class="flat-text">Welcome Back ' . $user . '</p>
    </div>
	<FORM NAME ="logout" METHOD ="POST" ACTION = "">
	<div class="split-center" style="padding:1%;">
	<INPUT class="input-login-button" TYPE = "Submit" Name = "logout" VALUE = "Log Out">
	</div>
	</FORM>';*/
	$loginFormOutput = "";
}

?>