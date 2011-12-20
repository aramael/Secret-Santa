<?php
session_start();
$title = "Rules";
require_once "resources/config.php";
get_header();
?>
<h2>Official Rules</h2>
<?php if (isset($_SESSION['registered']) && $_SESSION['registered'] == 1){?><p class="intro">Please read the rules below agree to them before you can join the game.</p><?php }?>
<img src="img/rules.png" />
<?php if (isset($_SESSION['registered']) && $_SESSION['registered'] == 1){?>
    <h2 style="margin:20px 0 10px 0;">Still Want to Join?</h2>
    <div class="actionable">
        <center><a class="large awesome red" type="submit" href="http://auth.hmseniors.com/logout">Nevermind, I dont want to join.</a> &nbsp;&nbsp;&nbsp;<a class="large awesome blue" type="submit" href="register-likes.php">Yes! Get on with it!</a></center>
    </div>
<?php
}
get_footer();
?>