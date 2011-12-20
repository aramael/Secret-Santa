<?php
/**
 *Logout of All Sessions
 */
session_start();
session_destroy();
get_header();
?>
<p>You have sucessfully Logged Out</p>
<?php
get_footer();
?>