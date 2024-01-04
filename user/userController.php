<?php
require('userModel.php');

$act = $_REQUEST['act'];

switch ($act) {
    case "login":
        $username = $_POST["username"];
        $password = $_POST['password'];
        $result = login($username, $password);
        echo json_encode($result);
        return;
    case "register":
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        addUser($username, $password, $role);
        return;
}

?>