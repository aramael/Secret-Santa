<?php
/**
 *Choosking Likes
 *
 */
require_once "resources/config.php";
$title = "Registration";
session_start();
if (isset($_SESSION['uid'])){
	$uid = $_SESSION['uid'];
	if (isset($_POST['submit'])){
		try{
			$stmt = $dbh->prepare("UPDATE users SET likes = :likes WHERE uid = :uid");
			$stmt->bindParam(":uid", $_SESSION['uid'], PDO::PARAM_INT, 11);
			$stmt->bindParam(":likes", $_POST['likes'], PDO::PARAM_STR, 500);
			$stmt->execute();
			header("Location: index.php");
		}catch(PDOException $e){
			echo $e->getMessage();
		}
	}
	try{
		$stmt = $dbh->prepare("SELECT likes FROM users WHERE uid = :uid");
		$stmt->bindParam(":uid" , $uid, PDO::PARAM_INT, 11);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_OBJ);
		$data = $stmt->fetch();
	}catch(PDOException $e){
		$e->getMessage();
	}
	if (is_null($data->likes)){
		try{
			$stmt = $dbh->prepare("UPDATE users SET likes = 'facebook' WHERE uid = :uid");
			$stmt->bindParam(":uid" , $uid, PDO::PARAM_INT, 11);
			$stmt->execute();
		}catch(PDOException $e){
			$e->getMessage();
		}
		get_header();
		?>
		
		<h2>Choose One</h2>        
		<p class="intro">Your're almost there! We just require you to give us a bit of your information regarding what you like or dislike. You can do this one of two ways. If you connect with facebook we will automatically grab some of the stuff that you have liked, or if you prefer you can just give them to us in the text box below.</p>
		<a href="facebook-login.php" class="facebook">Connect with Facebook</a>
		<hr />
		<form method="post" action="">
			<center><label for="likes" class="firstChar">Likes &amp; Dislikes</label></center>
			<br/>
			<textarea name="likes" class="xxlarge" style="margin:0 0 0 -5px;"></textarea>
			<br/>
			<input type="submit" value="Submit" name="submit" class="large awesome blue" />
		</form>
		
		<?php
		get_footer();
	}else{
		header("Location: profile");
	}
}
?>