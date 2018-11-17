<?php

require("config.php");

class Database{
	private $db_host = "localhost"; 
	private $db_user = "tombola-admin";
	private $db_pass = "Tombola2018"; 
	private $db_name = "lp_tombola";
	
	private $result = array(); 
 
	private function tableExists($table){
        $tablesInDb = @mysqli_query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
        if($tablesInDb){
            if(mysqli_num_rows($tablesInDb)==1){
                return true; 
			}
            else{ 
                return false; 
			}
		}
	}
	
    public function connect(){
        if(!$this->con){
            $myconn = @mysqli_connect($this->db_host,$this->db_user,$this->db_pass);
            if($myconn){
                $seldb = @mysqli_select_db($this->db_name,$myconn);
                if($seldb){
                    $this->con = true; 
                    return true; 
				} 
				else{
                    return false; 
				}
			}
			else{
                return false; 
			}
		}
		else{
            return true; 
		}
	}
	
    public function disconnect(){
		if($this->con){
			if(@mysqli_close()){
				$this->con = false; 
				return true; 
			}
			else{
				return false; 
			}
		}
	}
    
	public function select($table, $rows = '*', $where = null, $order = null){
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($order != null)
            $q .= ' ORDER BY '.$order;
        if($this->tableExists($table)){
			$query = @mysqli_query($q);
			if($query){
				$this->numResults = mysqli_num_rows($query);
				for($i = 0; $i < $this->numResults; $i++){
					$r = mysqli_fetch_array($query);
					$key = array_keys($r); 
					for($x = 0; $x < count($key); $x++){
						// Sanitizes keys so only alphavalues are allowed
						if(!is_int($key[$x])){
							if(mysqli_num_rows($query) > 1)
								$this->result[$i][$key[$x]] = $r[$key[$x]];
							else if(mysqli_num_rows($query) < 1)
								$this->result = null; 
							else
								$this->result[$key[$x]] = $r[$key[$x]]; 
						}
				}
			}            
				return true; 
			}
			else{
				return false; 
			}
		}
		else
			return false; 
	}
		
    public function insert($table,$values,$rows = null){
        if($this->tableExists($table)){
            $insert = 'INSERT INTO '.$table;
            if($rows != null){
                $insert .= ' ('.$rows.')'; 
			}
 
            for($i = 0; $i < count($values); $i++){
                if(is_string($values[$i]))
                    $values[$i] = '"'.$values[$i].'"';
			}
            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';
            $ins = @mysqli_query($insert);            
            if($ins){
                return true; 
			}
            else{
                return false; 
			}
		}
	}
		
    public function delete($table,$where = null){
        if($this->tableExists($table)){
            if($where == null){
                $delete = 'DELETE '.$table; 
			}
            else{
                $delete = 'DELETE FROM '.$table.' WHERE '.$where; 
			}
            $del = @mysqli_query($delete);
 
            if($del){
                return true; 
			}
            else{
               return false; 
			}
		}
        else{
            return false; 
		}
	}
		
    public function update($table,$rows,$where){
        if($this->tableExists($table)){
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row
            for($i = 0; $i < count($where); $i++){
                if($i%2 != 0){
                    if(is_string($where[$i])){
                        if(($i+1) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
					}
				}
			}
            $where = implode('=',$where);
             
             
            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows); 
            for($i = 0; $i < count($rows); $i++){
                if(is_string($rows[$keys[$i]])){
                    $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
				}
                else{
                    $update .= $keys[$i].'='.$rows[$keys[$i]];
				}
                 
                // Parse to add commas
                if($i != count($rows)-1){
                    $update .= ','; 
				}
			}
				
            $update .= ' WHERE '.$where;
            $query = @mysqli_query($update);
            if($query){
                return true; 
			}
            else{
                return false; 
			}
		}
        else{
            return false; 
		}
	}
}


#function db_call{
#$db = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbdatabase);
#if (!$db) {
#    die('Not connected : ' . mysql_error());
#}
#$db_selected = mysqli_select_db($db,$dbdatabase);
#if (!$db_selected) {
#    die ('Can\'t use '.$dbdatabase.' : ' . mysql_error());
#}
#}

function pf_validate_number($value, $function, $redirect) {
	if(isset($value) == TRUE) {
		if(is_numeric($value) == FALSE) {
			$error = 1;
			}
		if(@$error == 1) {
			header("Location: " . $redirect);
			}
		else {
			$final = $value;
			}
		}
	else {
		if($function == 'redirect') {
			header("Location: " . $redirect);
			}
		if($function == "value") {
			$final = 0;
			}
		}
return $final;
}


function showcart(){
	$itemnumrows = 0;
	if(isset($_SESSION['SESS_ORDERNUM'])){
		if(isset($_SESSION['SESS_LOGGEDIN'])){
			$custsql = "SELECT id, status from orders WHERE customer_id = ". 
				$_SESSION['SESS_USERID']. " AND status < 2;";
			$custres = mysqli_query($custsql)or die(mysqli_error());
			$custrow = mysqli_fetch_assoc($custres);

			$itemssql = "SELECT products.*, orderitems.*, orderitems.id AS ".
				"itemid FROM products, orderitems WHERE orderitems.product_id".
				"=products.id AND order_id = " . $custrow['id'];
			$itemsres = mysqli_query($itemssql)or die(mysqli_error());
			$itemnumrows = mysqli_num_rows($itemsres);
			}
		else{
			$custsql = "SELECT id, status from orders WHERE session = '" . 
				session_id(). "' AND status < 2;";
			$custres = mysqli_query($custsql)or die(mysqli_error());
			$custrow = mysqli_fetch_assoc($custres);
			$itemssql = "SELECT products.*, orderitems.*, orderitems.id AS ".
				"itemid FROM products, orderitems WHERE orderitems.product_id = ".
				"products.id AND order_id = " . $custrow['id'];
			$itemsres = mysqli_query($itemssql)or die(mysqli_error());
			$itemnumrows = mysqli_num_rows($itemsres);
			}
		}

#	else{
#		$itemnumrows = 0;
#		}
	if($itemnumrows == 0){
		echo "You have not added anything to your shopping cart yet.";
		}
	else{
		echo "<table cellpadding='10'>";
		echo "<tr>";
		echo "<td></td>";
		echo "<td><strong>Item</strong></td>";
		echo "<td><strong>Quantity</strong></td>";
		echo "<td><strong>Unit Price</strong></td>";
		echo "<td><strong>Total Price</strong></td>";
		echo "<td></td>";
		echo "</tr>";
		while($itemsrow = mysqli_fetch_assoc($itemsres)){
			$quantitytotal = $itemsrow['price'] * $itemsrow['quantity'];
			echo "<tr>";
			if(empty($itemsrow['image'])) {
				echo "<td><img src='productimages/dummy.jpg' width='50' alt='" . $itemsrow['name'] . "'></td>";
				}
			else {
				echo "<td><img src='productimages/" .$itemsrow['image'] . "' width='50' alt='". $itemsrow['name'] . "'></td>";
				}
			echo "<td>" . $itemsrow['name'] . "</td>";
			echo "<td>" . $itemsrow['quantity'] . "</td>";
			echo "<td><strong>&pound;" . sprintf('%.2f', $itemsrow['price']) . "</strong></td>";
			echo "<td><strong>&pound;". sprintf('%.2f', $quantitytotal) . "</strong></td>";
			echo "<td>[<a href='delete.php?id=". $itemsrow['itemid'] . "'>X</a>]</td>";
			echo "</tr>";
			@$total = $total + $quantitytotal;
			$totalsql = "UPDATE orders SET total = ". $total . " WHERE id = ". $_SESSION['SESS_ORDERNUM'];
			$totalres = mysql_query($totalsql)or die(mysql_error());;
			}
			
		echo "<tr>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td>TOTAL</td>";
		echo "<td><strong>&pound;". sprintf('%.2f', $total) . "</strong></td>";
		echo "<td></td>";
		echo "</tr>";
		echo "</table>";
		echo "<p><a href='checkout-address.php'>Go to the checkout</a></p>";
		}
	}
?>
