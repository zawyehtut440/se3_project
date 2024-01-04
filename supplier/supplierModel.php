<?php
require('../dbConfig.php');

function getItemList($merchantID) {
    global $db;
    $sql = "SELECT * FROM `products` WHERE `merchantID`=?";
    $stmt = mysqli_prepare($db, $sql);  // precompile sql指令,建立statement物件,以便執行SQL
    mysqli_stmt_bind_param($stmt, "i", $merchantID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt); // 取得查詢結果

    $rows = array();
    while ($r = mysqli_fetch_assoc($result)) {
        $rows[] = $r;
    }
    return $rows;
}

function addItem($merchantID, $name, $description, $price) {
    global $db;
    $sql = "INSERT INTO `products` (`merchantID`, `name`, `description`, `price`) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $merchantID, $name, $description, $price);
    mysqli_stmt_execute($stmt);
    return true;
}

function getItem($id) {
    global $db;
    $sql = "SELECT * FROM `products` WHERE `productID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function updateItem($id, $name, $description, $price) {
    global $db;
    $sql = "UPDATE `products` SET `name`=?, `description`=?, `price`=? WHERE `productID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $name, $description, $price, $id);
    mysqli_stmt_execute($stmt);
    return true;
}

function delItem($id) {
    global $db;
    $sql = "DELETE FROM `products` WHERE `productID`=?;";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return true;
}

?>