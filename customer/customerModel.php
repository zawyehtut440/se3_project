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

?>