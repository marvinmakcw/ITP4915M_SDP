<?php
require_once('conn.php');

if(isset($_GET['id'])) {
    extract($_POST);
    $id = $_GET['id'];
    $sql = "DELETE FROM `Orders` WHERE orderID = '$id';";
    $del = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if ($del) {
        header('Location: orderRecord.php');
    } else {
        // redirect to show with error
        die('No this order');
    }
}
?>

