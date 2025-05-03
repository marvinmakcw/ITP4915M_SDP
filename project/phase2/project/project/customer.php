<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];

if (!empty($_POST)) {
    extract($_POST);
    require_once('conn.php');

    switch ($_POST['submit']) {
//    Update customer on database
        case "Edit":
            $sql = "UPDATE customer SET customerEmail = '$email', customerName = '$name', phoneNumber = '$phone' WHERE customerEmail = '$email';";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            echo '<script>alert("Update successful, customer email: ' . $email . '");
            window.location.href="customer.php";</script>';
            break;
        case "Add":
//    Insert new customer on database
            $sql = "INSERT INTO customer (customerEmail, customerName, phoneNumber) VALUES ('$email', '$name', '$phone')";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            echo '<script>alert("Insert successful, new customer email: ' . $email . '");
            window.location.href="customer.php";</script>';
            break;
        case "Delete":
//    Delete customer on database
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
            break;
        default:
            echo "Wrong action.";
    }
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
    <!--menu-->
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
    <!--Customer list-->
    <h1 class="title">Customer Information</h1>
    <button class="button-11" onclick="location.href='customer.php'" type="button">New Customer</button>
    <br>

    <div class="scroll">
    <table >
        <tr>
            <th>Customer Email</th>
            <th>Customer Name</th>
            <th>Phone Number</th>
            <th colspan="2">Action</th>
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
                        <td><a href='customer.php?editCustomer=$customerEmail' class='btn'>Edit</td>
                        <td><a href='customer.php?deleteCustomer=$customerEmail' class='btn'>Delete</td>
                    </div>
                    </tr>";
        }
        mysqli_free_result($rs);
        ?>

    </table>
    </div>

    <?php
    //edit customer
    if (!empty($_GET) && !empty($_GET['editCustomer'])) {
        require_once('conn.php');
        $sql = "SELECT * FROM customer WHERE customerEmail = '" . $_GET['editCustomer'] . "'";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        $row = mysqli_fetch_array($rs);
        $email = $row['customerEmail'];
        $name = $row['customerName'];
        $phone = $row['phoneNumber'];

        ?>
        <!--show edit customer-->
        <h2 class="title">Edit customer</h2>
        <form action="customer.php" method="post">
            <div class="overflow">
            <table>
                <tr>
                    <th>Customer Email</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                </tr>
                <tr>
                    <td><input type="email" name="email" value="<?php echo $email; ?>" readonly></td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" required></td>
                    <td><input type="text" name="phone" onkeypress="return onlyNumberKey(event)" minlength="8" maxlength="8" value="<?php echo $phone; ?>"></td>
                </tr>
            </table>
            </div>
            <br>
            <input type="submit" class="button-11" name="submit" value="Edit">
        </form>
        <?php
        //delete customer
    } else if (!empty($_GET) && !(empty($_GET['deleteCustomer']))) {
        require_once('conn.php');
        $sql = "SELECT * FROM customer WHERE customerEmail = '" . $_GET['deleteCustomer'] . "'";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        $row = mysqli_fetch_array($rs);
        $email = $row['customerEmail'];
        $name = $row['customerName'];
        $phone = $row['phoneNumber'];

        ?>
        <!--show delete customer-->
        <h2 class="title">Delete customer</h2>
        <form action="customer.php" method="post">
            <table>
                <tr>
                    <th>Customer Email</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                </tr>
                <tr>
                    <td><input type="email" name="email" value="<?php echo $email; ?>" readonly></td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" readonly></td>
                    <td><input type="text" name="phone" value="<?php echo $phone; ?>" readonly></td>
                </tr>
            </table>
            <br>
            <input type="submit" class="button-11" name="submit" value="Delete">
        </form>
        <?php
    } else {
        ?>
        <!--show insert customer-->
        <h2 class="title">Add customer</h2>
        <form action="customer.php" method="post">
            <table>
                <tr>
                    <th>Customer Email</th>
                    <th>Customer Name</th>
                    <th>Phone Number</th>
                </tr>
                <tr>
                    <td><input type="email" name="email" required></td>
                    <td><input type="text" name="name" required></td>
                    <td><input type="text" name="phone" onkeypress="return onlyNumberKey(event)" minlength="8" maxlength="8"></td>
                </tr>
            </table>
            <br>
            <input type="submit" name="submit" class="button-11" value="Add">
        </form>
        <?php
    }
    ?>
    </body>
    <script>
        function onlyNumberKey(evt) {
            const ASCIICode = (evt.which) ? evt.which : evt.keyCode;
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
                return false;
            }
            return true;
        }
    </script>
    </html>
    <?php
}
?>