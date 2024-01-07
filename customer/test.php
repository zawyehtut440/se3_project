<?php
require('customerModel.php');

$customerID = 1;
// find out how many merchant the user(customer) bought stuff with they.
$merchantIDList = getMerchantIDList($customerID);
echo $merchantIDList;
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


?>