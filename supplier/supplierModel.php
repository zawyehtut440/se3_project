<?php
require('../dbConfig.php');

function getItemList($id) {
    global $db;
    $sql = "SELECT productID, name, price, description FROM `products` where merchantID=?;";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt); // 執行SQL
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function addItem($userID, $name, $price, $description) {
    global $db;
    $sql = "INSERT INTO `products` (merchantID, name, price, description) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_bind_param($stmt, "isis", $userID, $name, $price, $description);
    mysqli_stmt_execute($stmt); // 執行SQL
    return true;
}

function getItem($id) {
    global $db;
    $sql = "SELECT * FROM `products` WHERE productID=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function updateItem($id, $name, $price, $description) {
    global $db;
    $sql = "UPDATE `products` SET name=?, price=?, description=? WHERE productID=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sisi", $name, $price, $description, $id);
    mysqli_stmt_execute($stmt);
    return true;
}

function delItem($id) {
    global $db;
    $sql = "DELETE FROM `products` WHERE productID=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return true;
}

function getOrders($id) {
    global $db;
    $sql = "SELECT orders.orderID, 
                   users.username, 
            CASE
                WHEN orders.orderStatus = 0 THEN '未處理'
                WHEN orders.orderStatus = 1 THEN '處理中'
                WHEN orders.orderStatus = 2 THEN '寄送中'
                WHEN orders.orderStatus = 3 THEN '已寄送'
                WHEN orders.orderStatus = 4 THEN '已送達'
                ELSE '其他狀態'
            END AS orderStatusText,
                   orders.rating, 
                   orders.orderStatus
            FROM orders
            INNER JOIN users ON orders.customerID = users.userID
            where merchantID = ?;";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt); // 執行SQL
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function getOrderItems($id) {
    global $db;
    $sql = "SELECT `orderitems`.`orderItemID`,
                   `users`.`userName`,
                   `products`.`name`,
                   `quantity`
            FROM `orderitems` 
            INNER JOIN `users` ON `orderitems`.`customerID` = `users`.`userID`
            INNER JOIN `products` ON `orderitems`.`productID` = `products`.`productID`
            WHERE `orderitems`.`orderID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function changeOrderStatus($id, $status) {
    global $db;
    if ($status == 0){
        $sql = "UPDATE `orders` SET `orderStatus`=1 WHERE orderID=?;";
    }
    else if ($status == 1){
        $sql = "UPDATE `orders` SET `orderStatus`=2 WHERE orderID=?;";
    }
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return true;
}
?>