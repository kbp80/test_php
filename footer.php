<!-- closing id="container" div -->
</div>

<?php
echo "<div id=\"footer\"><em>All content on this site is &copy; ". $config_sitename . "!!!</em>";

if(@$_SESSION['SESS_ADMINLOGGEDIN'] == 1){
	echo "[<a href=" . $config_basedir . "adminorders.php>admin</a>][<a href=" . $config_basedir . "adminlogout.php>admin logout</a>]";
	}
echo "</div>"
$db->disconnect();
?>
<body>
</html>
