<?PHP
//variables//
$loggedin = false;
$loginfail = false;
$UserDisabled = false;
$user = '';
$username = '';
$usertype = '';
$cookie_name = 'session_id';
global $successfulLogin;
	
	//function to retrieve user IP address
	function getUserIP(){
    	$client  = @$_SERVER['HTTP_CLIENT_IP'];
    	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    	$remote  = $_SERVER['REMOTE_ADDR'];

    	if(filter_var($client, FILTER_VALIDATE_IP))
    	{
        	$ip = $client;
    	}
    	elseif(filter_var($forward, FILTER_VALIDATE_IP))
    	{
        	$ip = $forward;
    	}
    	else
    	{
        	$ip = $remote;
    	}

    	return $ip;
	}
	
	
	//function to create our session ID
	function createSessionID($val){
      	$chars="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,-";
      	srand((double)microtime()*1000000);
      	$i = 0;
      	$pass = '' ;
      	while ($i<=$val) 
    	{
        	$num  = rand() % 33;
        	$tmp  = substr($chars, $num, 1);
        	$pass = $pass . $tmp;
        	$i++;
      	}
    return $pass;
    }

	//check to see if user has just logged out
	if (isset($_POST['logout'])) {
		//we check the cookie
		if(isset($_COOKIE[$cookie_name])){
			$cookie_session_id = $_COOKIE[$cookie_name];
			
			intiateDatabaseConnection();
			
			$SQL = "SELECT * FROM `mcms_users` WHERE session_id='" . $cookie_session_id . "' LIMIT 1";
			$result = mysql_query($SQL);
			if( mysql_num_rows($result) > 0){
				//check our rows we have got
			 	while($row = mysql_fetch_assoc($result)) {
				 	$user = $row["user_id"];
					$usertype = $row["usertype"];
					$username = $row["username"];
					//update our database with our session ID to mark us as logged out
					$sql = "UPDATE `mcms_users` SET `session_id` = '' WHERE `mcms_users`.`user_id` = " . $user;
					mysql_query($sql);
			 	}
			}
		}
	}
	
	//check to see if we have already logged in using our session id cookie
	if(!isset($_COOKIE[$cookie_name])) {
    	//echo "<p>Cookie named '" . $cookie_name . "' is not set!</p>";
		//check the database for existing session_id
	} else {
    	//echo "<p>Cookie '" . $cookie_name . "' is set!</p>";
    	//echo "<p>Value is: " . $_COOKIE[$cookie_name] . "</p>";
		//check database for matching username+password
		$cookie_session_id = htmlspecialchars($_COOKIE[$cookie_name]);
		
		intiateDatabaseConnection();
		
		$SQL = "SELECT * FROM `mcms_users` WHERE session_id='" . $cookie_session_id . "' LIMIT 1";
		//echo $SQL;
		$result = mysql_query($SQL);
		if( mysql_num_rows($result) > 0){
			//check our rows we have got
			 while($row = mysql_fetch_assoc($result)) {
				 $user = $row["username"];
				 $usertype = $row["usertype"];
				 $username = $row["username"];
				 setcookie($cookie_name, $cookie_session_id, time()+(3600*3), "/"); // 86400 = 1 day
			 }
			 //echo '<p>You are currently logged in as ' . $user . '</p>';
			 
			 //now to see if their IP address matches what we got so you can't spoof the session id
			 $LogInAttemptIP = getUserIP();
			 $SQL = "SELECT * FROM `mcms_users` WHERE IP='" . $LogInAttemptIP . "' LIMIT 1";
			 $result = mysql_query($SQL);
			 if( mysql_num_rows($result) > 0){
			 //check our rows we have got
			 	while($row = mysql_fetch_assoc($result)) {

					setcookie($cookie_name, $cookie_session_id, time()+(3600*3), "/"); // 86400 = 1 day
			 	}
				
				//lets check the date to make sure it matches
				$LogInDateAttempt = date("Y-m-d");
			 	$SQL = "SELECT * FROM `mcms_users` WHERE logindate='" . $LogInDateAttempt . "' LIMIT 1";
			 	$result = mysql_query($SQL);
			 	if( mysql_num_rows($result) > 0){
			 	//check our rows we have got
			 		while($row = mysql_fetch_assoc($result)) {

						setcookie($cookie_name, $cookie_session_id, time()+(3600*3), "/"); // 86400 = 1 day
			 		}
				$loggedin = true;
			 	}
			 }
			// $loggedin = true;
		}
		mysql_close( $db_handle );
	}

	


	//check to see if the page was submitted or just refreshed
	if (isset($_POST['Submit1']) && $loggedin == false) {
	//connect to database
	
	//get username and password from form submission
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);
	
	if (intiateDatabaseConnection()) {
		//check database for matching username+password
		$SQL = "SELECT * FROM `mcms_users` WHERE Username='" . $username . "' LIMIT 1";
		//echo $SQL;
		$result = mysql_query($SQL);
		//if we have got more than 0 row returned
		$UserLoginAttempts = 0;
		if( mysql_num_rows($result) > 0){
			//check our rows we have got
			 while($row = mysql_fetch_assoc($result)) {
				 //retrieve the hashed password from our results
				 $hashedpassword = $row["password"];
				 //we need to retireve our saltytears
				 $passwordsalt = $row["Salt"];
				 $UserLoginAttempts = $row["loginattempts"];
				 $password = $password . $passwordsalt;
				 //we verify the hashed password against the provided
				 if (password_verify($password,$hashedpassword) && $UserLoginAttempts < 3){
					 //print ("<p>Welcome back, " . $row["username"] . "!</p>");
					 $loggedin = true;
					 $User_ID = $row["user_id"];
					 $user = $row["username"];
					 $usertype = $row["usertype"];
					 //set a cookie with a unique session id to mark that we are now logged in and add it as a value on our database
					 $cookie_name = "session_id";
					 $cookie_value = createSessionID(30);
					 $userIP = getUserIP();
					 $logindate = date("Y-m-d");
					 setcookie($cookie_name, $cookie_value, 0, "/"); // 86400 = 1 day
					 
					 //update our database with our session ID
					 $sql = "UPDATE `mcms_users` SET `session_id` = '" . $cookie_value . "' WHERE `mcms_users`.`user_id` = " . $User_ID;
					 mysql_query($sql);
					 
					 //update our database with our IP
					 $sql = "UPDATE `mcms_users` SET `IP` = '" . $userIP . "' WHERE `mcms_users`.`user_id` = " . $User_ID;
					 mysql_query($sql);
					 
					 //update our database with our IP
					 $sql = "UPDATE `mcms_users` SET `logindate` = '" . $logindate . "' WHERE `mcms_users`.`user_id` = " . $User_ID;
					 mysql_query($sql);
					 
				 }
				 else{
					 //print ("<p>Incorrect Username or password, Friendo!</p>");
					 //unset the cookie if somehow we fail to login
					 $UserLoginAttempts++;
					 if($UserLoginAttempts >= 3){
						 $UserDisabled = true;
					 }
					 
					 //update our database with failed login attempt
					 $User_ID = $row["user_id"];
					 $newsql = "UPDATE `mcms_users` SET `loginattempts` = '" . $UserLoginAttempts . "' WHERE `mcms_users`.`user_id` = " . $User_ID;
					 mysql_query($newsql);
					
					 
					 
					 setcookie($cookie_name, "", 0, "/"); // 86400 = 1 day
					 $loggedin = false;
					 $loginfail = true;
				 }
    		 }
		}
		else{
			//print ("<p>Incorrect Username or password, Friendo!</p>");	
			//unset the cookie if somehow we fail to login
			setcookie($cookie_name, "", time() - (86400/6), "/"); // 86400 = 1 day
			$loginfail = true;
		}
		//close the database once we're done
		closeDatabaseConnection();
	}
	else {
		//close the database if we fail
		closeDatabaseConnection();
	}
	}
	$GLOBALS['successfulLogin'] = $loggedin;
?>