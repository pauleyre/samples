<?php
// Include MySQL class
require_once('inc/mysql.class.php');
// Include database connection
require_once('inc/global.inc.php');
// Include functions
require_once('inc/functions.inc.php');
// Start the session
session_start();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>PHP Shopping Cart Demo &#0183; Bookshop</title>
	<link rel="stylesheet" href="css/styles.css" />
</head>

<body>

<div id="shoppingcart">

<h1>Your Shopping Cart</h1>

<?php
echo writeShoppingCart();
?>

</div>

<div id="booklist">

<h1>Books In Our Store</h1>

<?php
$sql = 'SELECT * FROM books ORDER BY id';
$result = $db->query($sql);
$output[] = '<ul>';
while ($row = $result->fetch()) {
	$output[] = '<li>"'.$row['title'].'" by '.$row['author'].': &pound;'.$row['price'].'<br /><a href="cart.php?action=add&id='.$row['id'].'">Add to cart</a></li>';
}
$output[] = '</ul>';
echo join('',$output);
?>

</div>

</body>
</html>