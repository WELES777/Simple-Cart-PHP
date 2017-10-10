<?php
session_start();
require_once("handlers/config.php");
require_once("handlers/cart.php");
?>
<!DOCTYPE html>
<html lang = "en">
<head>
	<title>Testing Cart</title>
	<meta http-equiv = "Content-Type" content = "text/html; charset=utf-8" />
	<!-- <link rel="stylesheet" href="css/reset.css"> -->
	<link rel = "stylesheet" href = "css/style.css">
</head>
<body>
<h1>Products list</h1>
<?php
$cart = new cart();
$products = $cart->getProducts();
?>
<table cellpadding = "5" cellspacing = "0" border = "0" class = "table-row">
	<tr>
		<td><b>Product</b></td>
		<td><b>Rate</b></td>
		<td><b>Rating</b></td>
		<td><b>Price</b></td>
		<td><b>Add Product</b></td>
	</tr>
	<?php
	foreach ($products as $product) {
		?>
		<tr>
			<td><?php print HtmlSpecialChars($product->product); ?></td>
			<td>
				<form id = "rating">
					<label for = "select1">1</label>
					<input type = "radio" name = "select" value = "1">
					<label for = "select2">2</label>
					<input type = "radio" name = "select" value = "2">
					<label for = "select3">3</label>
					<input type = "radio" name = "select" value = "3">
					<label for = "select4">4</label>
					<input type = "radio" name = "select" value = "4">
					<label for = "select5">5</label>
					<input type = "radio" name = "select" value = "5">
					<input type = "text" name = "action" value = "ratings" hidden />
				</form>
			</td>
			<td><?php print HtmlSpecialChars($product->rating); ?></td>
			
			<td>$<?php print $product->price; ?></td>
			<td><span class = "addToCart" data-id = "<?php print $product->id; ?>">add to cart</span></td>
		</tr>
		<?php
	}
	?>
</table>
<br /><a href = "handlers/view_cart.php" title = "go to cart">Go to cart</a>
<button class = "top">Save rating</button>
<script src = "js/popper.js"></script>
<script src = "js/jquery.js"></script>
<script src = "js/script.js"></script>
</body>
</html>