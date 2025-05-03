<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Menu</title>
    <link rel="stylesheet" href="css/menu.css">

</head>
<body>
<header>
    <label class="position">
        <a href="menu.php" class="band">Better Limited</a> &nbsp;&nbsp;
        <p> Name: <?php echo $staffName ?> &nbsp;&nbsp;
            Position: <?php echo $position ?></p>
    </label>
<!--    <h3>Menu</h3>-->
    <nav>
        <ul class="nav_links">
            <li><a  href="index.php">
                    <button class="log">LOGOUT</button>
                </a></li>
        </ul>
    </nav>
</header>

<h1 class="title">Function for <?php echo $position ?></h1>
<?php
if ($position == "Staff") {
    ?>
    <div class="vertical-center">
        <a href="POS.php"><button class="button-55"><img src="image/order.png"><br><div class="menu_font">Place Sales Order</div></button></a>
        <a href="orderRecord.php"><button class="button-55"><img src="image/customer.png"><br><div class="menu_font">Customer Order Record</div></button></a>
<!--        <a href=""><button class="button-55"><img src="image/delivery.png"><br><div class="menu_font">Delivery</div></button></a>-->
    </div>

    <?php
} else {
    ?>

        <div class="vertical-center">
            <a href="item.php"><button class="button-55"><img src="image/item.png"><br><div class="menu_font">Item Information</div></button></a>
            <a href="report.php"><button class="button-55"><img src="image/report.png"><br><div class="menu_font">Monthly Report</div></button></a>
            <a href="customer.php"><button class="button-55"><img src="image/customer.png"><br><div class="menu_font">Customer Record</div></button></a>
        </div>

    <?php
}
?>
</body>
</html>
