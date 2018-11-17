<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_include_path(".:/usr/share/php:/var/www/html");
echo "<p>TEstTest".get_include_path()."</p>";


require("header.php");

require("config.php");
require("functions.php");

$validid = pf_validate_number($_GET['id'],"redirect", $config_basedir);




$prodcatsql = "SELECT * FROM products WHERE cat_id = " . 
	$_GET['id'] . ";";
$prodcatres = mysqli_query($db,$prodcatsql);



$numrows = mysqli_num_rows($prodcatres);

if($numrows == 0){
	echo "<h1>No products</h1>";
	echo "There are no products in this category.";
	}
else{
	echo "";
	while($prodrow = mysqli_fetch_assoc($prodcatres)){
		echo "<table cellpadding=&quot;10&quot;>";
		echo "<tbody><tr>";
		echo "<h2>" . $prodrow["name"] . "</h2>";
		echo "" . $prodrow["description"]."<br />";
		if(empty($prodrow['image'])) {
			echo "<td><img src=/productimages/dummy.jpg alt=".$prodrow["name"]." /></td>";
			}
		else {
			echo "<td><img src=/productimages/".$prodrow["image"]." alt=".$prodrow["name"]." /></td>";
			}

		echo "<strong>OUR PRICE: £". sprintf('%.2f', $prodrow["price"]) . "</strong>";
		echo "[<a id= href=addtobasket.php?id=". $prodrow["id"]. ">buy</a>]";
		echo "</td></tr></tbody></table>";
		}
	}

require("footer.php");

?>

