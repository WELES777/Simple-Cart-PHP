<?php
session_start();
require_once("config.php");
require_once("cart.php");
$cart = new cart();
$action = strip_tags($_GET["action"]);
switch ($action)
{
	case "add":
		$cart->addToCart();
		break;
	case "remove":
		$cart->removeFromCart();
		break;
	
	case "empty":
		$cart->emptyCart();
		break;
	
	case "orders":
		$cart->sendOrder();
		$cart->emptyCart();
		break;
	
	case "ratings":
		$cart->setRating();
		break;
	
	case "summ":
		$cart->summing();
		break;
	
	
}
?>