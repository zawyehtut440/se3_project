<?php
require('supplierModel.php');

$act = $_REQUEST['act'];

switch($act) {
    case "listItem":
        $merchantID = (int)$_GET['id'];
        $merchant = getItemList($merchantID);
        echo json_encode($merchant);
        return;
    case "addItem":
        $userID = (int)$_POST['userID'];
        $itemName = $_POST['name'];
        $itemPrice = (int)$_POST['price'];
        $itemDescription = $_POST['description'];
        addItem($userID, $itemName, $itemPrice, $itemDescription);
        return;
    case "getItemInfo":
        $itemId = (int)$_GET['id'];
        $itemInfo = getItem($itemId);
        echo json_encode($itemInfo);
        return;
    case "updateItem":
        $itemId = (int)$_GET['id'];
        $itemName = $_POST['name'];
        $itemPrice = (int)$_POST['price'];
        $itemDescription = $_POST['description'];
        updateItem($itemId, $itemName, $itemPrice, $itemDescription);
        return;
    case "delItem":
        $itemId = (int)$_GET['id'];
        delItem($itemId);
        return;
    case "orders":
        $merchantID = (int)$_GET['id'];
        $items = getOrders($merchantID);
        echo json_encode($items);
        return;
    case "getOrderItemsInfo":
        $itemId = (int)$_GET['id'];
        $itemInfo = getOrderItems($itemId);
        echo json_encode($itemInfo);
        return;
    case "changeOrderStatus":
        $itemId = (int)$_GET['id'];
        $status = $_GET['status'];
        changeOrderStatus($itemId, $status);
        return;
}

?>