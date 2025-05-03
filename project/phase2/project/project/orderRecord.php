<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];
//Update delivery
if (!empty($_POST["updateDelivery"])) {
    $deliveryAddress = $_POST["address"];
    $deliveryDate = $_POST["date"];
    $orderID = $_GET['orderID'];

    require_once('conn.php');
    if (empty($deliveryDate)&&empty($deliveryAddress)) {
        $sql = "UPDATE Orders SET deliveryDate = NULL, deliveryAddress = NULL WHERE orderID = '$orderID';";
        echo '<script>alert("Update successful, order ID: ' .$orderID.  '");
            window.location.href="orderRecord.php";</script>';
    } else if(!empty($deliveryDate)&&!empty($deliveryAddress)){
        $sql = "UPDATE Orders SET deliveryDate = '$deliveryDate', deliveryAddress = '$deliveryAddress' WHERE orderID = '$orderID';";
        echo '<script>alert("Update successful, order ID: ' .$orderID.  '");
            window.location.href="orderRecord.php";</script>';
    }else{
        echo '<script>alert("Update insuccessful, order ID: ' .$orderID.  ', Please fill all information");
            window.location.href="orderRecord.php";</script>';
    }
    $rs = mysqli_query($conn, $sql)
    or die(mysqli_error($conn));

}
?>
<!doctype html>
<html lang="en">
<head>
    <script>
        function showMsg(){
            alert("Order Records Deleted");
        }
    </script>
    <meta charset="UTF-8">
    <title>Order Record</title>
    <link rel="stylesheet" href="css/orderRecord.css">
</head>
<body>
<!--menu-->
<header>

    <label class="position">
        <a href="menu.php" class="band">Better Limited</a> &nbsp;&nbsp;
        <p> Name: <?php echo $staffName ?> &nbsp;&nbsp;
            Position: <?php echo $position ?></p>
    </label>

    <nav>
        <ul class="nav_links">
            <li><a href="POS.php" class="menubtn">POS</a></li>
            <li><a href="orderRecord.php" class="menubtn">Order Record</a></li>
            <!--            <li><a href="" class="menubtn">Delivery</a></li>-->
        </ul>
    </nav>
    <a href="index.php">
        <button class="log">LOGOUT</button>
    </a>
</header>

<!--Show order record-->
<form method="post">
    <h1 class="title">Order Record</h1>
    <div class="scroll">
    <table>
        <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer Email</th>
            <th>Customer Name</th>
            <th>Staff ID</th>
            <th>Staff Name</th>
            <th>Date & Time</th>
            <th>Detail..</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody></tbody>
        <?php
        require_once('conn.php');
        $sql = "SELECT Orders.orderID, Orders.customerEmail, Customer.customerName, 
                Orders.staffID, Staff.staffName, Orders.dateTime 
                FROM Orders NATURAL JOIN Customer, Staff WHERE Staff.staffID = Orders.staffID ORDER BY CAST(orderID AS INT) ASC;";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        while ($rc = mysqli_fetch_array($rs)) {
            extract($rc);
            $orderID = $rc[0];
            $customerEmail = $rc[1];
            $customerName = $rc[2];
            $staffID = $rc[3];
            $staffName = $rc[4];
            $dataTime = $rc[5];


            echo "
                    <tr>
                    <div>
                        <td>$orderID</td>
                        <td>$customerEmail</td>
                        <td>$customerName</td>
                        <td>$staffID</td>
                        <td>$staffName</td>
                        <td>$dataTime</td>
                        <td><a class='btn' href='orderRecord.php?orderID=$orderID' >Detail</td>
                        <td><a class='btn' href='orderRecord2.php?id=" . $orderID . "' onclick='showMsg();'>Delete</a></td>
                    </div>
                    </tr>";
        }
        mysqli_free_result($rs);
        ?>
    </table>
    </div>
</form>
<?php
if (!empty($_GET['orderID'])) {
    require_once('conn.php');
    $orderID = $_GET['orderID'];
    $sql = "SELECT Orders.staffID, Staff.staffName, Orders.customerEmail, Customer.customerName, Customer.phoneNumber
                    ,Orders.dateTime, Orders.deliveryAddress, Orders.deliveryDate 
                    FROM Orders NATURAL JOIN Staff NATURAL JOIN Customer WHERE Orders.orderID = '$orderID';";
    $rs = mysqli_query($conn, $sql)
    or die(mysqli_error($conn));
    $row = mysqli_fetch_array($rs);
    $staffID = $row[0];
    $staffName = $row[1];
    $custEmail = $row[2];
    $custName = $row[3];
    $phoneNo = $row[4];
    $date = $row[5];
    $deliveryAddress = $row[6];
    $deliveryDate = $row[7];
    ?>
<!--Show order detail-->
    <div id="order">
        <h2 id="title2">Order Detail</h2>
        <div class="orderInfo">
            Order ID: &nbsp<?php echo $orderID ?>&nbsp;&nbsp;&nbsp;
            Staff ID: &nbsp;&nbsp;<?php echo $staffID ?>&nbsp;&nbsp;&nbsp;
            Staff Name: &nbsp<?php echo $staffID ?>&nbsp;&nbsp;&nbsp; <br>

            Date:<br>
            Customer Name: <?php echo $custName ?>&nbsp;&nbsp;&nbsp;
            Email: <?php echo $custEmail ?>&nbsp;
            Phone Number: <?php echo $phoneNo ?>&nbsp;&nbsp;&nbsp;&nbsp;

        </div>
        <table id="table2">
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Order Quantity</th>
                <th>Sold Price</th>
            </tr>
            <?php
            $sql = "SELECT itemOrders.itemID, item.itemName, itemOrders.orderQuantity, itemOrders.soldPrice 
                    FROM item NATURAL JOIN itemOrders WHERE orderID = '$orderID' ORDER BY item.itemName DESC;";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            while ($rc = mysqli_fetch_assoc($rs)) {
                extract($rc);
                echo "
                <tr>
                    <td>$itemID</td>
                    <td>$itemName</td>
                    <td>$orderQuantity</td>
                    <td>$soldPrice</td>
                </tr>";
            }
            ?>
        </table>
<!--        Update delivery-->
        <form method="post" action="orderRecord.php?orderID=<?php echo $orderID ?>">
            <h2 id="title2">Delivery Information Update </h2><br>
            <table>
                <tr>
                    <th>Delivery Address</th>
                    <th>Delivery Data</th>
                </tr>
                <tr>
                    <td><input type="text" value="<?php echo $deliveryAddress ?>" class="deliveryInfo" name="address"></td>
                    <td><input type="date" value="<?php echo $deliveryDate ?>" class="deliveryInfo" name="date"></td>
                </tr>
            </table>

            <input type="submit" class="btn2" value="Save" name="updateDelivery">
            <input type="reset" class="btn2">
            <a href="orderRecord.php?" class="btn2">Cancel</a>

        </form>
    </div>
    <?php
}
?>


</body>
</html>