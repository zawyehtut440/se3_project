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
        // check shoppingcart's productID, customerID pair whether existed
        $row = getCartRow($productID, $customerID);
        // if exist
        if ($row != null) {
            // get quantity + $quantity
            updateCart($row['cartID'], $row['quantity'] + $quantity);
        } else { // else
            // addCart(productID, customerID, quantity)
            addCart($productID, $customerID, $quantity);
        }
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
    case "checkout":
        $customerID = (int)$_POST['customerID'];
        // find out how many merchant the user(customer) bought stuff with they.
        $merchantIDList = getMerchantIDList($customerID);
        // insert orders table
        foreach ($merchantIDList as $row) {
            $merchantID = $row['merchantID'];
            insertOrder($merchantID, $customerID);
        }
        // get shopping cart products information from customer
        $shoppingCartList = getShoppingCartList($customerID);
        // for each cartItem in shoppingCartList
        foreach($shoppingCartList as $cartItem) {
            // find which product merchantID
            $productID = $cartItem['productID'];
            // get (mID)merchantID from products
            $mID = getMerchantID($productID);
            // according to merchatID=mID, customerID=$ustomerID, orderStatus=0 to find out orderID
            $orderID = getOrderID($mID, $customerID);
            // add order item, including productID, customerID, quantity, orderID
            addOrderItem($productID, $customerID, $cartItem['quantity'], $orderID);
        }
        // delete cart's items of customerID
        deleteCart($customerID);
        return;
    case "viewOrderStatus":
        $customerID = (int)$_POST['customerID'];
        $customerOrders = getCustomerOrders($customerID);
        // orderID, merchantID, orderStauts
        echo json_encode($customerOrders);
        return;
    case "viewOrderDetail":
        $orderID = (int)$_GET['orderID'];
        // orderID, productID, product name, quantity, price
        $orderDetails = getOrderDetails($orderID);
        echo json_encode($orderDetails);
        return;
    case "rating":
        $orderID = (int)$_GET['orderID'];
        $ratingValue = (int)$_GET['ratingValue'];
        updateRating($orderID, $ratingValue);
        return;
}

?>