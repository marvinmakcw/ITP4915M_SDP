<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];
?>
<!doctype html>
<html lang="en">
<head>
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
            <li><a href="" class="menubtn">Delivery</a></li>
        </ul>
    </nav>
    <a  href="index.php">
        <button class="log">LOGOUT</button>
    </a>
</header>


<form action="orderRecord2.php" method="post" >
    <h1 class="title">Order Record</h1>
    <table>
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
        <?php
        require_once('conn.php');
        $sql = "SELECT Orders.orderID, Orders.customerEmail, Customer.customerName, 
                Orders.staffID, Staff.staffName, Orders.dateTime 
                FROM Orders NATURAL JOIN Customer, Staff WHERE Staff.staffID = Orders.staffID;";
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
                        <td><a class='btn' href='orderRecord2.php?id=" .$orderID ."'> Delete</a></td>
                    </div>
                    </tr>";
        }
        mysqli_free_result($rs);
        ?>
        </table
        </form>
<?php
        if (!empty($_GET['orderID'])) {
            require_once('conn.php');
            $orderID = $_GET['orderID'];
            $sql = "SELECT ItemOrders.itemID, Item.itemName, ItemOrders.orderQuantity, ItemOrders.soldPrice
                FROM ItemOrders NATURAL JOIN Item WHERE ItemOrders.orderID = '$orderID';";
            $rs = mysqli_query($conn, $sql)
               or die(mysqli_error($conn));

        ?>

<div id="order">
        <h2 id="title2">Order Detail</h2>
            <div class="orderInfo">
            Order ID: <?php echo $orderID ?>

            Staff ID:
            Staff Name: <br>

            Date:<br>
            Customer Name:
            Email:
            Phone Number:

            </div>
        <table id="table2">
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Order Quantity</th>
                <th>Sold Price</th>
            </tr>
<?php

            while ($rc = mysqli_fetch_array($rs)) {
                extract($rc);
                echo"
                <tr>
                    <td>$itemID</td>
                    <td>$itemName</td>
                    <td>$orderQuantity</td>
                    <td>$soldPrice</td>
                </tr>
            
";
        }
?>
        </table>
</div>
<?php
}
?>




</body>
</html>