
<div id="bar">
<?php
	$db->select('categories');
	$res = $db->getResult();

	for($index=0; $index < sizeof($res); ++$index){
		echo "<a id=\"\" href=" . $config_basedir. "products.php?id=" . $res['id'] . ">" . $res['name'] . "</a><br />";
		}
?>
<hr />


<?php
echo "<a href=" . $config_basedir . "index.php>Home</a><br />";
echo "<a href=" . $config_basedir . "products>Products</a><br /><br />";

	#echo var_dump($_SESSION);
	if(isset($_SESSION['SESS_LOGGEDIN']) == TRUE){
		echo "Logged in as <strong>" . $_SESSION['SESS_USERNAME']. "</strong>[<a href=" . $config_basedir. "logout.php>logout</a>]";
	}
	else{
		echo "<a href=" . $config_basedir . "login.php>Login</a>";
	}
?>
</div>