
<?php
require_once('conn.php');
//delete order records on database
if(isset($_GET['id'])) {
    extract($_POST);
    $id = $_GET['id'];
    $sql = "DELETE FROM `itemOrders` WHERE orderID = '$id';";
    $delOrdersItem = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $sql = "DELETE FROM `Orders` WHERE orderID = '$id';";
    $delOrders = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    if ($delOrdersItem && $delOrders) {
        header('Location: orderRecord.php');

    } else {
        // redirect to show with error
        die('No this order');
    }
}
?>

