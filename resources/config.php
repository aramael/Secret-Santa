<?php
date_default_timezone_set('America/Los_Angeles');
error_reporting(0);

/*				SERVER AUTH
***********************************************/
$db_Host = "localhost";
$db_Name = DATABASE_NAME;
$db_User = DATABASE_USER;
$db_Pass = DATABASE_PASSWORD;

/*				GAME CONSTANTS
***********************************************/
$project_name = "Secret Santa";
$project_url = PROJECT_URL;
$event_start = 1323133989 /*Time in since Unix Epoch*/;
$facebook_app_id = FACEBOOK_APP_ID;
$facebook_app_secret = FACEBOOK_APP_SECRET;
$facebook_url_return = $project_url."/facebook-login.php";
$twitter_message = "";

/*				PDO DB INFORMATION
***********************************************/
try {
	$dbh = new PDO("mysql:host=".$db_Host.";dbname=".$db_Name, $db_User, $db_Pass);
}
catch(PDOException $e){
	echo $e->getMessage();
}

//Include Functions
include "functions.php";
?>