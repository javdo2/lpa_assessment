<?php
$authChk = true;
$noScriptLoad = true;
require('app-lib.php');
/**
 * Called when an user clicks add to cart button and save in the cookies an
 * array with the id of the item and the quantity 
 * the Response is the message to show to the user
 */
if($_GET['action'] == 'addToCart')
{
    $id = $_POST['id'];
    $qty = $_POST['qty'];
    $name = $_POST['name'];
    $cart = isset($_COOKIE['cart']) && $_COOKIE['cart'] != null && $_COOKIE['cart'] != '' ? json_decode($_COOKIE['cart'], true) : [];
    if(isset($cart[$id])) {
        $cart[$id] = $qty;
        echo $qty . " x Item: " . $id . " - " . $name . " has been updated in your cart";
        lpa_log("User $displayName updated Item on cart: ".$id." X ".$qty);
    } 
    else {
        $cart[$id] = $qty;
        echo $qty . " x Item: " . $id . " - " . $name . " has been added to your cart";
        lpa_log("User $displayName added Item to cart: ".$id." X ".$qty);
    }
    setcookie('cart', json_encode($cart));
}
/**
 * Called when an user clicks remove in cart page and update in the cookies array 
 * deleting the item using the id 
 * the Response is the message to show to the user
 */
if($_GET['action'] == 'removeFromCart')
{
    $id = $_POST['id'];
    $cart = isset($_COOKIE['cart']) && $_COOKIE['cart'] != null && $_COOKIE['cart'] != '' ? json_decode($_COOKIE['cart'], true) : [];
    if(isset($cart[$id])) {
        unset($cart[$id]);
    }
    setcookie('cart', json_encode($cart));
    echo "Item: " . $id . " has been removed";
    lpa_log("User $displayName remove Item from cart: ".$id);
}
/**
 * Clear the cookie used to show the cart
 */
if($_GET['action'] == 'clearCart')
{
    setcookie('cart', null);
    setcookie('total', null);
}
/**
 * Save a cookie containing the total of the cart in order to be showed in the checkout payment
 */
if($_GET['action'] == 'setTotal')
{
    setcookie('total', $_POST['total']);
}