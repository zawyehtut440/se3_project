<?php
require('customerModel.php');

$act = $_REQUEST['act'];

switch ($act) {
    case "loadProduct":
        // select all information from products
        $products = getAllProducts();
        echo json_encode($products);
        return;
    case "addCart":
        // buggy here, if product id had been added, it will append the new row to the database
        $productID = (int)$_GET['productID']; // get productID
        $customerID = (int)$_POST['customerID']; // get customerID
        $quantity = (int)$_GET['quantity']; // get quantity
        addCart($productID, $customerID, $quantity);
        return;
    case "loadCart":
        // select all cart item from customerID
        $customerID = (int)$_POST['customerID']; // get customerID
        $cartProducts = loadCart($customerID);
        echo json_encode($cartProducts);
        return;
    case "delCartProduct":
        $cartID = (int)$_GET['cartID']; // get cartID
        // delete this cartID
        delCartProduct($cartID);
        return;
}

?>