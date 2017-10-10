<?php
session_start();
require_once("config.php");
require_once("cart.php");
?>
<html lang = "en">
<head>
	<title>Check order</title>
	<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
	<link rel = "stylesheet" href = "../css/style.css">
</head>
<body leftmargin = "0" marginwidth = "0" topmargin = "0" marginheight = "0">
<h1>Show cart</h1>
<?php
$cart = new cart();
$products = $cart->getCart();
?>
<table cellpadding = "5" cellspacing = "0" border = "0" class = "table-row">
	<tr>
		<td><b>Product</b></td>
		<td><b>Count</b></td>
		<td><b>Total</b></td>
	
	
	</tr>
	<?php
	foreach ($products as $product)
	{
		?>
		<tr>
			<td><?php print HtmlSpecialChars($product->product); ?></td>
			<td><?php print $product->count; ?></td>
			<td>$<?php print $product->total; ?></td>
		</tr>
		<?php
	}
	?>


</table>
<h2>Insert your contact and submit to confirm your order</h2>
<form class = "total">
	<tr>
		<td><b>Transport </b></td>
		<td>
			<label for = "transp">Pick Up</label>
			<input type = "radio" name = "transp" value = "0">
			<label for = "transp">UPS</label>
			<input type = "radio" name = "transp" value = "5">
		</td>
	
	</tr>
</form>
<form id = "toPost">
	<table cellpadding = "8" cellspacing = "0" border = "0" class = "table-check">
		<tr>
			<td><b>eWallet</b></td>
			<td><b class = "ewall"></b></td>
		
		
		</tr>
		<tr>
			<td><b>Total pay</b></td>
			<td><b class = "total_pay"> </b></td>
		
		</tr>
		<tr>
			<td>Your name</td>
			<td><input type = "text" name = "uname" /></td>
			<td>Email</td>
			<td><input type = "text" name = "email" /></td>
			<td><input type = "text" name = "action" value = "orders" hidden /></td>
			<td><input type = "submit" class = "sendval" value = "Buy" /></td>
		</tr>
		<div id = "response">
			<pre></pre>
			</br>
		</div>
	</table>
</form>


<br /><a href = "view_cart.php" title = "go to cart">Go to cart</a>

<script src = "../js/popper.js"></script>
<script src = "../js/jquery.js"></script>
<script src = "../js/script.js"></script>
</body>
</html>