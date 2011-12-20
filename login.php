<?php
require_once "resources/config.php";
$title = "Login";
get_header();
?>
	<img src="img/login.png" />
	<?php if(isset($_POST['submit'])){        
        //Check if the username or password boxes were not filled in
        if(empty($_POST['email']) || empty($_POST['password'])){
            //if not display an error message
            $error = "You need to fill in a <b>Username</b> and a <b>Password</b>.";
        }else{
			try {
				$stmt = $sdb->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
				$stmt->bindParam(":email", $_POST['email'], PDO::PARAM_INT, 11);
				$stmt->bindParam(":password", md5($_POST['password']), PDO::PARAM_INT, 11);
				$stmt->execute();
			}catch(PDOException $e){
				echo $e->getMessage();
			}
			
            //check if there was not a match
            if($stmt->rowCount == 0){
                //if not display error message
                $error = "The username or password you entered is incorrect.";
            }else{
				$stmt->setFetchMode(PDO::FETCH_OBJ);
				$user = $stmt->fetch();
				$_SESSION['uid'] = $user->uid;
				header('Location: index.php');
            }
        }
    }	
    ?>
    <form id="form-login" action="login.php" method="post">
        <?php if (isset($error) && $error !== "") {echo "<p class='error'>".$error."</p>";unset($error);}?> 
		<?php $formKey->outputKey(); ?>
        <label for="email">Email</label>
            <input type="text" name="email" class="text"/>
        <label for="password">Password</label>
            <input type="password" name="password" class="text"/>
        <input type="submit" value="Submit" name="submit"  class="super button"/>
        <div class="actionable">
            <center><input type="submit" class="large awesome blue" type="submit" value="Register &rarr;"/></center>
        </div> 
    </form>
<?php
get_footer();
?>