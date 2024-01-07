<?php
require("../dbConfig.php");

function getAllProducts() {
    global $db;
    $sql = "SELECT * FROM `products`;";
    $stmt = mysqli_prepare($db, $sql);  // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function addCart($productID, $customerID, $quantity) {
    global $db;
    $sql = "INSERT INTO `shoppingcart` (`productID`, `customerID`, `quantity`) VALUES (?, ?, ?);";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $productID, $customerID, $quantity);
    mysqli_stmt_execute($stmt);
    return true;
}

function loadCart($customerID) {
    // information that I need: productID, product name, product price, quantity
    global $db;
    $sql = "SELECT `shoppingcart`.`cartID` ,`shoppingcart`.`productID`, `products`.`name`, `products`.`price`, `shoppingcart`.`quantity` FROM `shoppingcart` INNER JOIN `products` ON `shoppingcart`.`productID` = `products`.`productID` WHERE `shoppingcart`.`customerID` = ?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function delCartProduct($cartID) {
    global $db;
    $sql = "DELETE FROM `shoppingcart` WHERE `cartID`=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $cartID);
    mysqli_stmt_execute($stmt);
    return true;
}

function getMerchantIDList($customerID) {
    global $db;
    $sql = "SELECT DISTINCT `products`.`merchantID` FROM `shoppingcart` INNER JOIN `products` ON `shoppingcart`.`productID` = `products`.`productID` WHERE `shoppingcart`.`customerID` = ?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $rows;
}

function insertOrder($merchantID, $customerID) {
    global $db;
    $sql = "INSERT INTO `orders` (`merchantID`, `customerID`, `orderStatus`, `rating`) VALUES (?, ?, 0, 0);";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $merchantID, $customerID);
    mysqli_stmt_execute($stmt);
    return true;
}

function getShoppingCartList($customerID) {
    global $db;
    $sql = "SELECT `productID`, `quantity` FROM `shoppingcart` WHERE `customerID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $rows;
}

function getMerchantID($productID) {
    global $db;
    $sql = "SELECT `merchantID` FROM `products` WHERE `productID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $productID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $merchantID = $row['merchantID'];
    } else {
        $merchantID = null;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    return $merchantID;
}

function getOrderID($merchantID, $customerID) {
    global $db;
    $sql = "SELECT `orderID` FROM `orders` WHERE `merchantID`=? AND `customerID`=? AND `orderStatus`=0 ORDER BY `orderID` DESC LIMIT 1";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $merchantID, $customerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $orderID = $row['orderID'];
    } else {
        $orderID = null;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);

    return $orderID;
}

function addOrderItem($productID, $customerID, $quantity, $orderID) {
    global $db;
    $sql = "INSERT INTO `orderitems` (`productID`, `customerID`, `quantity`, `orderID`) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "iiii", $productID, $customerID, $quantity, $orderID);
    mysqli_stmt_execute($stmt);
    return true;
}

function deleteCart($customerID) {
    global $db;
    $sql = "DELETE FROM `shoppingcart` WHERE `customerID`=?";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customerID);
    mysqli_stmt_execute($stmt);
    return true;
}

function getCustomerOrders($customerID) {
    global $db;
    $sql = "SELECT `orderID`, `merchantID`, `orderStatus` FROM `orders` WHERE `customerID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $customerID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $rows;
}

function getOrderDetails($orderID) {
    global $db;
    $sql = "SELECT `orderitems`.`orderID`, `orderitems`.`productID`, `products`.`name`, `orderitems`.`quantity`, `products`.`price` FROM `orderitems` JOIN `products` ON `orderitems`.`productID`=`products`.`productID` WHERE `orderitems`.`orderID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $orderID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    mysqli_free_result($result);
    mysqli_stmt_close($stmt);
    return $rows;
}

?>