<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];

if (!empty($_POST)) {
    extract($_POST);
    require_once('conn.php');
    $sql = "SELECT orderID FROM orders WHERE customerEmail = '$email';";
    $rs = mysqli_query($conn, $sql)
    or die(mysqli_error($conn));
    $cusOrders = array();
    while ($row = mysqli_fetch_assoc($rs)) {
        $cusOrders[] = $row['orderID'];
    }
    $cusOrders = array_unique($cusOrders);
    foreach ($cusOrders as $value) {
        $rs = mysqli_query($conn, "DELETE FROM  itemorders WHERE orderID = '$value';");
        $rs = mysqli_query($conn, "DELETE FROM  orders WHERE orderID = '$value';");
    }
    $sql = "DELETE FROM customer WHERE customerEmail = '$email';";
    $rs = mysqli_query($conn, $sql)
    or die(mysqli_error($conn));
    echo '<script>alert("Delete successful, customer email: ' . $email . ' and related data has been deleted!");
            window.location.href="customer.php";</script>';
} else {
    $_POST = array();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Customer List</title>
        <link rel="stylesheet" href="css/customer.css">

    </head>
    <body>
    <!--    menu-->
    <header>

        <label class="position">
            <a href="menu.php" class="band">Better Limited</a> &nbsp;&nbsp;
            <p> Name: <?php echo $staffName ?> &nbsp;&nbsp;
                Position: <?php echo $position ?></p>
        </label>

        <nav>
            <ul class="nav_links">
                <li><a href="item.php" class="menubtn">Item List</a></li>
                <li><a href="report.php" class="menubtn">Monthly Report</a></li>
                <li><a href="customer.php" class="menubtn">Customer</a></li>
            </ul>
        </nav>
        <a href="index.php">
            <button class="log">LOGOUT</button>
        </a>
    </header>
    <!--    item-->
    <h1 class="title">Customer Information</h1>
    <table>
        <tr>
            <th>Customer Email</th>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th>Action</th>
        </tr>
        <?php
        require_once('conn.php');
        $sql = "SELECT * FROM Customer";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        while ($rc = mysqli_fetch_assoc($rs)) {
            extract($rc);
            echo "
                    <tr>
                    <div>
                        <td>$customerEmail</td>
                        <td>$customerName</td>
                        <td>$phoneNumber</td>
                        <td><a href='customer.php?customerEmail=$customerEmail' class='btn'>Delete</td>
                    </div>
                    </tr>";
        }
        mysqli_free_result($rs);
        ?>
    </table>
    <?php
    if (!empty($_GET)) {
        require_once('conn.php');
        $sql = "SELECT * FROM customer WHERE customerEmail = '" . $_GET['customerEmail'] . "'";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        $row = mysqli_fetch_array($rs);
        $email = $row['customerEmail'];
        $name = $row['customerName'];
        $phone = $row['phoneNumber'];

        ?>
        <h2 class="title">Delete customer</h2>
        <form action="customer.php" method="post">
            <table>
                <tr>
                    <th>Customer Email</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                </tr>
                <tr>
                    <td><input type="text" name="email" value="<?php echo $email; ?>" readonly></td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" required></td>
                    <td><input type="text" name="phone" value="<?php echo $phone; ?>"></td>
                </tr>
            </table>
            <br>
            <input type="submit" class="button-11" name="submit" value="Delete">
        </form>
        <?php
    }
    ?>
    </body>
    </html>
    <?php
}
?>