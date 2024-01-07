<?php
require('supplierModel.php');

$act = $_REQUEST['act'];

switch($act) {
    case "listItem":
        $items = getItemList();
        echo json_encode($items);
        return;
    case "addItem":
        $itemName = $_POST['productName'];
        $itemPrice = (int)$_POST['productCost'];
        $itemDescription = $_POST['productContent'];
        $itemNumber = (int)$_POST['productNumber'];
        addItem($itemName, $itemPrice, $itemDescription, $itemNumber);
        return;
    case "getItemInfo":
        $itemId = (int)$_GET['id'];
        $itemInfo = getItem($itemId);
        echo json_encode($itemInfo);
        return;
    case "updateItem":
        $itemId = (int)$_GET['id'];
        $itemName = $_POST['productName'];
        $itemPrice = (int)$_POST['productCost'];
        $itemDescription = $_POST['productContent'];
        $itemNumber = (int)$_POST['productNumber'];
        updateItem($itemId, $itemName, $itemPrice, $itemDescription, $itemNumber);
        return;
    case "delItem":
        $itemId = (int)$_GET['id'];
        delItem($itemId);
        return;
    case "orders":
        $items = getOrders();
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