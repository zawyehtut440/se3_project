<?php
require('../dbConfig.php');

function login($username, $password) {
    global $db;
    $sql = "SELECT `userID`, `role` FROM `users` WHERE `userName`=? AND `password`=?;";
    $stmt = mysqli_prepare($db, $sql );
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    mysqli_stmt_execute($stmt); // 執行sql
    $result = mysqli_stmt_get_result($stmt); //取得查詢結果
    return mysqli_fetch_assoc($result);
}

function addUser($username, $password, $role) {
    global $db;
    $sql = "INSERT INTO `users` (`userName`, `password`, `role`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $role);
    mysqli_stmt_execute($stmt);
    return true;
}


?>