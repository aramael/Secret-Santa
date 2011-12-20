<?php
/**
 *Header Template
 *
 *All objects echoed in this file will appear on every page
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="refresh" content="1200;url=logout">
    <title><?php echo $title; ?> | <?php echo $project_name;?></title>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/font.css" type="text/css" charset="utf-8" />
    <link rel="stylesheet" href="css/topbar.css" type="text/css" charset="utf-8" />
    <?php if ($title == "Profile"){
		?>
		<script src="http://code.jquery.com/jquery-latest.js"></script>
        <script src="js/toggle.js"></script>
        <?
	}?>
</head>

<body>
<div class="topbar">
  <div class="fill">
    <div class="container">
      <h3><a href="#"><img src="img/santa_s_hat.png" /></a></h3>
      <ul class="nav">
        <li><a href="profile">Profile</a></li>
        <li><a href="rules">Rules</a></li>
        <?php if (isset($_SESSION['seniorUID'])){?>
	        <li><a href="logout">Log Out</a></li>
        <?php }?>
      </ul>
    </div>
  </div>
  <div class="candycane"></div>
</div>

<div id="floater">
	<div class="modal-top"></div>
    <div class="content">