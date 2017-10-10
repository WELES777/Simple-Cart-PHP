<?php

class Cart
{
	
	private $dbConnection;
	
	function __construct()
	{
		$this->dbConnection = new mysqli(MYSQLSERVER, MYSQLUSER, MYSQLPASSWORD, MYSQLDB);
	}
	
	function __destruct()
	{
		$this->dbConnection->close();
	}
	
	
	public function getProducts()
	{
		$arr = array();
		$dbConnection = $this->dbConnection;
		$dbConnection->query("SET NAMES 'UTF8'");
		$statement = $dbConnection->prepare("SELECT id, product, price, rating FROM products ORDER BY product ASC");
		$statement->execute();
		$statement->bind_result($id, $product, $price, $rating);
		while ($statement->fetch())
		{
			$line = new stdClass;
			$line->id = $id;
			$line->product = $product;
			$line->price = $price;
			$line->rating = $rating;
			$arr[] = $line;
		}
		$statement->close();
		return $arr;
	}
	
	public function addToCart()
	{
		$id = intval($_GET["id"]);
		
		if ($id > 0)
		{
			if ($_SESSION['cart'] != "")
			{
				$cart = json_decode($_SESSION['cart'], true);
				
				$found = false;
				for ($i = 0; $i < count($cart); $i++)
				{
					if ($cart[$i]["product"] == $id)
					{
						$cart[$i]["count"] = $cart[$i]["count"] + 1;
						
						$count = $cart[$i]["count"] + 1;
						
						$found = true;
						break;
					}
				}
				if (!$found)
				{
					$line = new stdClass;
					$line->product = $id;
					$line->count = 1;
					$cart[] = $line;
				}
				$_SESSION['cart'] = json_encode($cart);
				getProductData($id);
			} else
			{
				$line = new stdClass;
				$line->product = $id;
				$line->count = 1;
				
				$cart[] = $line;
				$_SESSION['cart'] = json_encode($cart);
				
			}
		}
	}
	
	public function getCart()
	{
		$cartArray = array();
		if ($_SESSION['cart'] != "")
		{
			$cart = json_decode($_SESSION['cart'], true);
			for ($i = 0; $i < count($cart); $i++)
			{
				$lines = $this->getProductData($cart[$i]["product"]);
				$line = new stdClass;
				$line->id = $cart[$i]["product"];
				$line->count = $cart[$i]["count"];
				$line->product = $lines->product;
				$line->total = ($lines->price * $cart[$i]["count"]);
				$cartArray[] = $line;
			}
		}
		return $cartArray;
	}
	
	public function getProductData($id)
	{
		$dbConnection = $this->dbConnection;
		$dbConnection->query("SET NAMES 'UTF8'");
		$statement = $dbConnection->prepare("SELECT product, price, rating FROM products WHERE id = ? LIMIT 1");
		$statement->bind_param('i', $id);
		$statement->execute();
		$statement->bind_result($product, $price, $rating);
		$statement->fetch();
		$line = new stdClass;
		$line->product = $product;
		$line->price = $price;
		$line->rating = $rating;
		$statement->close();
		return $line;
	}
	
	public function removeFromCart()
	{
		$id = intval($_GET["id"]);
		if ($id > 0)
		{
			if ($_SESSION['cart'] != "")
			{
				$cart = json_decode($_SESSION['cart'], true);
				for ($i = 0; $i < count($cart); $i++)
				{
					if ($cart[$i]["product"] == $id)
					{
						$cart[$i]["count"] = $cart[$i]["count"] - 1;
						if ($cart[$i]["count"] < 1)
						{
							unset($cart[$i]);
						}
						break;
					}
				}
				$cart = array_values($cart);
				$_SESSION['cart'] = json_encode($cart);
			}
		}
	}
	
	public function emptyCart()
	{
		$_SESSION['cart'] = "";
	}
	
	public function summing()
	{
		if ($_SESSION['cart'] != "")
		{
			$cart = json_decode($_SESSION['cart'], true);
			$transfee = strip_tags($_GET['transp']);
			$total = 0;
			
			$cart = $this->getCart();
			
			for ($i = 0; $i < count($cart); $i++)
			{
				$total += $cart[$i]->total;
			}
			$total = $transfee + $total;
			
			$arr = array();
			$dbConnection = $this->dbConnection;
			$dbConnection->query("SET NAMES 'UTF8'");
			$statement1 = $dbConnection->prepare("SELECT money FROM eWallet WHERE  id=1");
			$statement1->execute();
			$statement1->bind_result($wallet) or trigger_error($mysqli->error, E_USER_ERROR);
			$statement1->fetch() or trigger_error($mysqli->error, E_USER_ERROR);
			$line = new stdClass;
			$line->wallet = $wallet;
			$arr[] = $line;
			$statement1->close() or trigger_error($mysqli->error, E_USER_ERROR);
			
			echo $total . " " . $line->wallet;
		}
	}
	
	public function sendOrder()
	{
		$arr = array();
		$name = strip_tags($_GET['uname']);
		$email = strip_tags($_GET['email']);
		$transfee = strip_tags($_GET['transp']);
		$total = 0;
		
		$cart = $this->getCart();
		
		for ($i = 0; $i < count($cart); $i++)
		{
			$total += $cart[$i]->total;
		}
		$total = $transfee + $total;
		
		$dbConnection = $this->dbConnection;
		$dbConnection->query("SET NAMES 'UTF8'");
		
		
		$arr = array();
		$dbConnection = $this->dbConnection;
		$dbConnection->query("SET NAMES 'UTF8'");
		$statement1 = $dbConnection->prepare("SELECT money FROM eWallet WHERE  id=1");
		$statement1->execute();
		$statement1->bind_result($wallet) or trigger_error($mysqli->error, E_USER_ERROR);
		$statement1->fetch() or trigger_error($mysqli->error, E_USER_ERROR);
		$line = new stdClass;
		$line->wallet = $wallet;
		$arr[] = $line;
		$statement1->close() or trigger_error($mysqli->error, E_USER_ERROR);
		$result = $line->wallet - $total;
		
		$statement = $dbConnection->prepare("UPDATE eWallet SET money=? WHERE  id=1;") or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->bind_param('d', $result) or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->execute() or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->close();
		
		
		$statement = $dbConnection->prepare("INSERT INTO orders( name, email, transport, total) VALUE(?, ?, ?, ?)") or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->bind_param('ssdd', $name, $email, $transfee, $total) or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->execute() or trigger_error($mysqli->error, E_USER_ERROR);
		$newid = $statement->insert_id;
		
		$statement->close() or trigger_error($mysqli->error, E_USER_ERROR);
		
		
		for ($i = 0; $i < count($cart); $i++)
		{
			$statement = $dbConnection->prepare("insert into order_items( order_id, product_id, nitem, total) value($newid, ?, ?, ?)") or trigger_error($mysqli->error, E_USER_ERROR);
			$statement->bind_param('iid', $cart[$i]->id, $cart[$i]->count, $cart[$i]->total) or trigger_error($mysqli->error, E_USER_ERROR);
			$statement->execute() or trigger_error($mysqli->error, E_USER_ERROR);
			$statement->close();
		}
		echo $total . " " . $line->wallet;
	}
	
	public function setRating()
	{
		
		$itemRating = strip_tags($_GET['select']);
		$dbConnection = $this->dbConnection;
		$dbConnection->query("SET NAMES 'UTF8'") or trigger_error($mysqli->error, E_USER_ERROR);
		$statement = $dbConnection->prepare("SELECT ratecounter rating FROM products WHERE id = ? LIMIT 1") or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->bind_param('i', $id) or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->execute();
		$statement->bind_result($ratecounter, $rating);
		$statement->fetch() or trigger_error($mysqli->error, E_USER_ERROR);
		$line = new stdClass;
		$line->rating = $rating;
		$line->ratecounter = $ratecounter;
		
		$statement->close();
		return $line;
		
		
		++$ratecounter;
		$rating = ($itemRating + $rating + 5 * ($ratecounter - 2)) / 2;
		$statement = $dbConnection->prepare("INSERT INTO products( ratecounter, rating) VALUE(?)") or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->bind_param('dd', $ratecounter, $rating) or trigger_error($mysqli->error, E_USER_ERROR);
		$statement->execute() or trigger_error($mysqli->error, E_USER_ERROR);
		$newid = $statement->insert_id;
		
		$statement->close() or trigger_error($mysqli->error, E_USER_ERROR);
		
		
	}
	
	
}

?>