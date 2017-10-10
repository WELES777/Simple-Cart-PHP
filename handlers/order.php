 <?php
 session_destroy();
session_start();
  require_once( "config.php" );
  require_once( "cart.php" );
  $cart = new cart();
  $cart->sendOrder();
  $cart->emptyCart();  

