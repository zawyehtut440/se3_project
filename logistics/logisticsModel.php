<?php
require('../dbConfig.php');

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
            INNER JOIN users ON orders.customerID = users.userID
            WHERE orders.orderStatus = 2 or orders.orderStatus = 3;";
    $stmt = mysqli_prepare($db, $sql); // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_execute($stmt); // 執行SQL
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function changeOrderStatus($id, $status) {
    global $db;
    if ($status == 2){
        $sql = "UPDATE `orders` SET `orderStatus`=3 WHERE orderID=?;";
    }
    else if ($status == 3){
        $sql = "UPDATE `orders` SET `orderStatus`=4 WHERE orderID=?;";
    }
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return true;
}
?>