<?php
require_once "resources/config.php";
session_start();
$time = date("U");
if (isset($_SESSION['uid'])){
	$uid = $_SESSION['uid'];	
	try{
		$stmt = $dbh->prepare("SELECT registered FROM users WHERE uid = :uid");
		$stmt->bindParam(":uid" , $uid, PDO::PARAM_INT, 11);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_OBJ);
	}catch(PDOException $e){
		$e->getMessage();
	}
	if ($stmt->rowCount() == 1){
		header("Location: profile");
	}elseif($stmt->rowCount() == 0){
		if($time < $event_start){
			$_SESSION['registered'] = 1;
			header("Location: rules");
		}else{
			header("Location: register-close");
		}
	}else{
		echo "Something has gone wrong";
	}
}else{
	echo "Something has gone wrong";
}
?>