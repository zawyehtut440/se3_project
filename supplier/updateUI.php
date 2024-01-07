<?php
$jsonData = json_decode(file_get_contents('php://input'), true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update item</title>
</head>
<body>
    <form id="myForm" name="myForm" method="post">
        商品名稱: <input name="productName" type="text" value="<?php echo $jsonData['productName'];?>" ><br>
        商品價格: <input name="productCost" type="number" value="<?php echo $jsonData['productCost']; ?>" ><br>
        商品資訊: <input name="productContent" type="text" value="<?php echo $jsonData['productContent']; ?>" ><br>
        商品數量: <input name="productNumber" type="number" value="<?php echo $jsonData['productNumber']; ?>" >
        <br>
        <input type="button" onclick="updateItem(<?php echo $jsonData['id']; ?>)" value="修改">
    </form>
</body>
</html>