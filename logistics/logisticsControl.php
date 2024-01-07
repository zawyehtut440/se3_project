<?php
require('logisticsModel.php');

$act = $_REQUEST['act'];

switch($act) {
    case "orders":
        $items = getOrders();
        echo json_encode($items);
        return;
    case "changeOrderStatus":
        $itemId = (int)$_GET['id'];
        $status = $_GET['status'];
        changeOrderStatus($itemId, $status);
        return;
}

?>