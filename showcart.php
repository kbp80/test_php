<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

#session_start();
require("functions.php");
require("header.php");


echo "<h1>Your shopping cart</h1>";
#showcart();

if(($_SESSION['SESS_ORDERNUM']) !== NULL){
	$sess_ordernum=$_SESSION['SESS_ORDERNUM'];
	$sql = "SELECT * FROM orderitems WHERE order_id =$sess_ordernum";
	$result = mysqli_query($sql) or die(mysqli_error());
	$numrows = mysqli_num_rows($result);
	if($numrows >= 1) {
		echo "<h2><a href='checkout-address.php'>Go to the checkout</a></h2>";
		}
	}
	
require("footer.php");
?>
