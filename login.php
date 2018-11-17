<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require("config.php");
if(isset($_SESSION['SESS_LOGGEDIN']) == TRUE){
	header("Location: " . $config_basedir);
	}

if(isset($_POST['submit'])){

	$loginsql = "SELECT * FROM logins WHERE username = '" . 
		$_POST['userBox']. "' AND password = '" . 
		sha1($_POST['passBox']) . "'";

	$loginres = mysqli_query($db,$loginsql);
	$numrows = mysqli_num_rows($loginres);


	if($numrows == 1){
		$loginrow = mysqli_fetch_assoc($loginres);
		$_SESSION['SESS_LOGGEDIN'] = 1;
		$_SESSION['SESS_USERNAME'] = $loginrow['username'];
		$_SESSION['SESS_USERID'] = $loginrow['id'];
		$ordersql = "SELECT id FROM orders WHERE customer_id = " . 
			$_SESSION['SESS_USERID'] . " AND status < 2"; 
		$orderres = mysqli_query($db,$ordersql); 
		$orderrow = mysqli_fetch_assoc($orderres); 
		$_SESSION['SESS_ORDERNUM'] = $orderrow['id']; 
		header("Location: " . $config_basedir);
		}
	else{
		header("Location: http://" .$_SERVER['HTTP_HOST']. 
			$_SERVER['SCRIPT_NAME'] . "?error=1");
		}
	}
else{
	require("header.php");
?>

<h1>Customer Login</h1>

Please enter your username and password to log into the websites. 
If you do not have an account, you can get one for free by 
<a href="register.php">registering</a>.

<?php
if(isset($_GET['error'])){
	echo "<br /><strong>Incorrect username/password<br />" . 
	$_GET['error'] . "</strong>";
	}
?>

<form action="<?php $_SERVER['SCRIPT_NAME']; ?>" method="POST">
	<table>
	<tbody>
	<tr>
		<td>Username</td>
		<td><input type="textbox" name="userBox" /></td>
	</tr>
	<tr>
		<td>Password</td>
		<td><input type="password" name="passBox" /></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" value="Log in" /></td>
	</tr>
	</tbody>
	</table>
</form>

<?php
}
require("footer.php");
?>
