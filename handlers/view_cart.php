<?php
session_start();
require_once("config.php");
require_once("cart.php");
?>
<!DOCTYPE html>
<html lang = "en">
<title>Viewing cart</title>
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
			<td><span class = "removeFromCart" data-id = "<?php print $product->id; ?>">remove one element</span></td>
		</tr>
		<?php
	}
	?>

</table>
<br />
<a href = "../index.php" title = "go back to products">Go back to products</a>
<a href = "javascript:void(0);" class = "emptyCart " title = "empty cart">Empty cart</a>
<a href = "checkout.php" title = "Checkout" class = "checkord total">Checkout</a>
<script src = "../js/popper.js"></script>
<script src = "../js/jquery.js"></script>
<script src = "../js/script.js"></script>
<script src = "../js/script.js"></script>
</body>
</html>