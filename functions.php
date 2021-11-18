<?php
$authChk = true;
$noScriptLoad = true;
require('app-lib.php');
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

if($_GET['action'] == 'clearCart')
{
    setcookie('cart', null);
    setcookie('total', null);
}
if($_GET['action'] == 'setTotal')
{
    setcookie('total', $_POST['total']);
}