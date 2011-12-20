<?php
/**
 *Registration Page
 */
session_start();
require_once "resources/config.php";
$time = date('U');
/**
 *Define the Form Key
 */
$formKey = new formKey();
if (isset($_POST['submit'])){
	//Save Information Into Session if needed for callback
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['firstName'] = $_POST['firstName'];
	$_SESSION['lastName'] = $_POST['lastName'];
	
	//Parse & Safeguard Information
	$error = "";
	
	if ($_POST['email'] == "") {
		$error .= "<li>Please fill out your Email.</li>";
	}else{
		if (!preg_match("^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+(?:[A-Z]{2}|com|org|net|edu|gov|mil|biz|info|mobi|name|aero|asia|jobs|museum)\b^", $_POST['email'])){
			$error .= "<li>The <b>E-mail</b> is not valid, must be someone@horacemann.org!</li>";
		}else{
			try{
				$stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
				$stmt->bindParam(":uid", $_POST['email'], PDO::PARAM_INT, 11);
				$stmt->execute();
				$stmt->setFetchMode(PDO::FETCH_OBJ);
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			if($stmt->rowCount == 1){
				$error .= "<li>The <b>E-mail</b> address you supplied is already taken</li>";
			}
		}
	}
	if ($_POST['firstName'] == "") {
		$error .= "<li>Please fill out your First name</li>";
	}
	if ($_POST['lastName'] == "") {
		$error .= "<li>Please fill out your Last name</li>";
	}
	//Check Password
	if ($_POST['password'] == "") {
		$error .= "<li>Please fill out your password</li>";
	}
	if ($_POST['passwordConfirm'] == "") {
		$error .= "<li>Please fill out your re-enter your password</li>";
	}
	if (isset($_POST['password']) && isset($_POST['passwordConfirm']) && $_POST['password'] !== $_POST['passwordConfirm']) {
		$error .= "<li>Your password's do not match.</li>";
	}
	if ($error == "") {
		try{
			$stmt = $dbh->prepare("INSERT INTO users (email, fname, lname, password) VALUES (:email, :fname, :lname, :password)");
			$stmt->bindParam(":email", $_POST['email'], PDO::PARAM_INT, 11);
			$stmt->bindParam(":fname", $_POST['firstName'], PDO::PARAM_INT, 11);
			$stmt->bindParam(":lname", $_POST['lastName'], PDO::PARAM_INT, 11);
			$stmt->bindParam(":password", md5($_POST['password']), PDO::PARAM_INT, 11);
			$stmt->execute();
		}catch(PDOException $e){
			echo $e->getMessage();
		}
		header("Location: register-likes.php");
	}
}
if ($time > $event_start){
	header ("Location: register-close");
}elseif ($time < $event_start){
	$title = "Register";
	get_header();
	?>	
    <form method="post" action="">
		<?php if (isset($_SESSION['error']) && $_SESSION['error'] !== "") {echo "<p class='error'>Please fix the following errors: <br/><ul>".$_SESSION['error']."</ul></p>";}?> 
        <?php $formKey->outputKey(); ?>
        <label for="email">Email</label>
            <input class="text" type="text" id="email" name="email" value="<?php if (isset($_SESSION['email'])) {echo $_SESSION['email'];}?>"/>
        <label for="password">Password</label>
            <input class="text" type="password" id="password" name="password"/>
        <label for="passwordConfirm">Retype Password</label>
            <input class="text" type="password" id="passwordConfirm" name="passwordConfirm"/>
        <label for="firstName">Given Name</label>
        <input type="text" class="text" id="firstName" name="firstName" value="<?php if (isset($_SESSION['firstName'])) {echo $_SESSION['firstName'];}?>"/>
        <label for="lastName">Surname</label>
        <input type="text" class="text" id="lastName" name="lastName" value="<?php if (isset($_SESSION['lastName'])) {echo $_SESSION['lastName'];}?>"/>
        <div class="actionable">
            <center><input type="submit" class="large awesome blue" type="submit" value="Register &rarr;"/></center>
        </div> 
    </form>
	<?php
	get_footer();
}
?>