<?php
require('supplierModel.php');

$act = $_REQUEST['act'];

switch ($act) {
    case "listItem":
        $merchantID = (int)$_POST['supplierID'];
        $items = getItemList($merchantID);
        echo json_encode($items);
        return;
    case "addItem":
        $merchantID = (int)$_POST['supplierID'];
        $name = $_POST['productName'];
        $description = $_POST['productDescription'];
        $price = (int)$_POST['productPrice'];
        addItem($merchantID, $name, $description, $price);
        return;
    case "getItemInfo":
        $itemID = (int)$_GET['id'];
        $itemInfo = getItem($itemID);
        echo json_encode($itemInfo);
        return;
    case "updateItem":
        $itemId = (int)$_GET['id'];
        $itemName = $_POST['productName'];
        $itemDescription = $_POST['productDescription'];
        $itemPrice = (int)$_POST['productPrice'];
        updateItem($itemId, $itemName, $itemDescription, $itemPrice);
        return;
    case "delItem":
        $itemID = (int)$_GET['id'];
        echo $itemID;
        delItem($itemID);
        return;
}

?>