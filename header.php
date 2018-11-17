<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_SESSION['SESS_CHANGEID']) == TRUE) {
	session_unset();
	session_regenerate_id();
	}

require("functions.php");

$db = new Database();
$db->connect();
?>

<html>
	<head>
		<link href="/CSS/stylesheet.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="header">
			<h1><?php echo $config_sitename; ?></h1>
		</div>
		<div id="menu">
			<a href="<?php echo $config_basedir; ?>">Home</a> -
			<a href="<?php echo $config_basedir; ?>showcart.php">View Basket/Checkout</a>
		</div>
		<div id="container">
			<?php require("bar.php"); ?>

<!-- html closes in the footer -->

