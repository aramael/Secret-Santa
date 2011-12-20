<?php
/**
 *Functions
 *
 *Includes all required public functions
 */

function get_header( $name = null ){
	global $title , $project_name;
	include_once "header.php";	
}
function get_footer( $name = null ){
	include_once "footer.php";
}
function facebook_info($fbid, $section, $access_token){
	$url = "https://graph.facebook.com/".$fbid."/".$section."?access_token=".$access_token;
	$info = json_decode(file_get_contents($url));
	return $info;	
}
?>