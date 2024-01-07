<?php
require('../dbConfig.php');

function getItemList() {
    global $db;
    $sql = "SELECT * FROM `product`;";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_execute($stmt); // 執行SQL
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    if (!$result) {
        // 如果查詢失敗，可以輸出錯誤資訊以便於除錯
        die('資料庫查詢錯誤: ' . mysqli_error($db));
    }

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function addItem($name, $price, $description, $remainNum) {
    global $db;
    $sql = "INSERT INTO `product` (productName, productCost, productContent, productNumber) VALUES (?, ?, ?, ?);";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_bind_param($stmt, "sisi", $name, $price, $description, $remainNum);
    mysqli_stmt_execute($stmt); // 執行SQL
    return true;
}

function getItem($id) {
    global $db;
    $sql = "SELECT * FROM `product` WHERE id=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function updateItem($id, $name, $price, $description, $remainNum) {
    global $db;
    $sql = "UPDATE `product` SET productName=?, productCost=?, productContent=?, productNumber=? WHERE id=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sisii", $name, $price, $description, $remainNum, $id);
    mysqli_stmt_execute($stmt);
    return true;
}

function delItem($id) {
    global $db;
    $sql = "DELETE FROM `product` WHERE id=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return true;
}

function getOrders() {
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
                   orders.orderStatus
            FROM orders
            INNER JOIN users ON orders.customerID = users.userID;";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_execute($stmt); // 執行SQL
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    if (!$result) {
        // 如果查詢失敗，可以輸出錯誤資訊以便於除錯
        die('資料庫查詢錯誤: ' . mysqli_error($db));
    }

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
                   `product`.`productName`,
                   `quantity`
            FROM `orderitems` 
            INNER JOIN `users` ON `orderitems`.`customerID` = `users`.`userID`
            INNER JOIN `product` ON `orderitems`.`productID` = `product`.`id`
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