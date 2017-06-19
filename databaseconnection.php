<?php
global $db_handle;
function intiateDatabaseConnection(){
	$GLOBALS['db_handle'] = mysql_connect($GLOBALS['DatabaseHost'], $GLOBALS['DatabaseRootUser'], $GLOBALS['DatabaseRootPassword']);
	return $db_found = mysql_select_db($GLOBALS['DatabaseName']);	
}

function closeDatabaseConnection(){
	mysql_close($GLOBALS['db_handle']);
}

?>