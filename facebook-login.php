<?php
/**
 *Choosking Likes
 *
 */
require_once "resources/config.php";
session_start();
if (isset($_SESSION['uid'])){
	$code = $_REQUEST["code"];
	
	if(empty($code)) {
		$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
		$dialog_url = "https://www.facebook.com/dialog/oauth?scope=offline_access,user_likes&client_id=" 
		  . $facebook_app_id . "&redirect_uri=" . urlencode($facebook_url_return) . "&state="
		  . $_SESSION['state'];
	
		echo("<script> top.location.href='" . $dialog_url . "'</script>");
	}
	
	if($_REQUEST['state'] == $_SESSION['state']) {
		$token_url = "https://graph.facebook.com/oauth/access_token?"
		  . "client_id=" . $facebook_app_id . "&redirect_uri=" . urlencode($facebook_url_return)
		  . "&client_secret=" . $facebook_app_secret . "&code=" . $code;
	
		$response = file_get_contents($token_url);
		$params = null;
		parse_str($response, $params);
		$user = facebook_info("me","", $params['access_token']);
		
		try {
			$stmt = $dbh->prepare("INSERT INTO facebook (uid, facebookID, access_token) VALUES (:uid, :facebookID, :access_token)");
			
			/*** bind the paramaters ***/
			$stmt->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_INT, 25);
			$stmt->bindParam(':facebookID', $user->id, PDO::PARAM_INT, 25);
			$stmt->bindParam(':access_token', $params['access_token'], PDO::PARAM_STR, 50);
			
			/*** execute the prepared statement ***/
			$stmt->execute();
			
			/*** redirect user to index ***/
			header ("Location: index.php");
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		var_dump($params);
	}else {
		 echo("The state does not match. You may be a victim of CSRF.");
	   }
}else{
	header ("Location: index.php");
}
?>