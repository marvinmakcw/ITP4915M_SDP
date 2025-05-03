<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];

if (!empty($_POST)) {
    extract($_POST);
    require_once('conn.php');

    switch ($_POST['submit']) {
        case "Edit":
            $sql = "UPDATE item SET itemName = '$name', itemDescription = '$desc', stockQuantity = '$qty', price = '$price' WHERE itemID = '$itemID';";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            echo '<script>alert("Update successful, item ID: ' . $itemID . '");
            window.location.href="item.php";</script>';
            break;
        case "Insert":
            $sql = "SELECT IFNULL(max(itemID), 0)+1 AS 'nextID' FROM item;";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            $row = mysqli_fetch_array($rs);
            $itemID = $row['nextID'];
            $sql = "INSERT INTO item (itemID, itemName, itemDescription, stockQuantity, price) VALUES ('$itemID', '$name', '$desc', '$qty', '$price');";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            echo '<script>alert("Insert successful, new item ID: ' . $itemID . '");
            window.location.href="item.php";</script>';
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
        <title>Item List</title>
        <link rel="stylesheet" href="css/item.css">

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
        <a  href="index.php">
            <button class="log">LOGOUT</button>
        </a>
    </header>
<!--    item-->
    <h1 class="title">Item Information</h1>
    <table>
        <tr>
            <th>Item ID</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Stock Quantity</th>
            <th>Price</th>
            <th>Edit</th>
        </tr>
        <?php
        require_once('conn.php');
        $sql = "SELECT * FROM Item";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        while ($rc = mysqli_fetch_assoc($rs)) {
            extract($rc);
            echo "
                    <tr>
                    <div>
                        <td>$itemID</td>
                        <td>$itemName</td>
                        <td>$itemDescription</td>
                        <td>$stockQuantity</td>
                        <td>$price</td>
                        <td><a href='item.php?itemID=$itemID' >Edit</td>
                    </div>
                    </tr>";
        }
        mysqli_free_result($rs);
        ?>
    </table>
    <?php
    if (!empty($_GET)) {
        require_once('conn.php');
        $sql = "SELECT * FROM item WHERE itemID = '" . $_GET['itemID'] . "'";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        $row = mysqli_fetch_array($rs);
        $ID = $row['itemID'];
        $name = $row['itemName'];
        $desc = $row['itemDescription'];
        $qty = $row['stockQuantity'];
        $price = $row['price'];

        ?>
        <h2 class="title">Edit item</h2>
        <form action="item.php" method="post">
            <table>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Stock Quantity</th>
                    <th>Price</th>
                </tr>
                <tr>
                    <td><input type="text" name="itemID" value="<?php echo $ID; ?>" readonly></td>
                    <td><input type="text" name="name" value="<?php echo $name; ?>" required></td>
                    <td><input type="text" name="desc" value="<?php echo $desc; ?>" required></td>
                    <td><input type="text" name="qty" value="<?php echo $qty; ?>"></td>
                    <td><input type="text" name="price" value="<?php echo $price; ?>"></td>
                </tr>
            </table>
            <br>
            <input type="submit" class="button-11" name="submit" value="Edit" >
        </form>
        <?php
    } else {
        ?>
        <h2 class="title">Insert item</h2>
        <form action="item.php" method="post">
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Stock Quantity</th>
                    <th>Price</th>
                </tr>
                <tr>
                    <td><input type="text" name="name" required></td>
                    <td><input type="text" name="desc" required></td>
                    <td><input type="text" name="qty"></td>
                    <td><input type="text" name="price"></td>
                </tr>
            </table>
            <br>
            <input type="submit" name="submit" class="button-11"  value="Insert">
        </form>
        <?php
    }
    ?>
    </body>
    </html>
    <?php
}
?>