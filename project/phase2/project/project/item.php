<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];

if (!empty($_POST)) {
    extract($_POST);
    require_once('conn.php');

    switch ($_POST['submit']) {
//     Update item list on database
        case "Edit":
            $sql = "UPDATE item SET itemName = '$name', itemDescription = '$desc', stockQuantity = '$qty', price = '$price' WHERE itemID = '$itemID';";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            echo '<script>alert("Update successful, item ID: ' . $itemID . '");
            window.location.href="item.php";</script>';
            break;
//      Insert  item on database
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
    <!-- menu-->
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
    <!--item list-->
    <h1 class="title">Item Information</h1>
    <button class="button-11" onclick="location.href='item.php'" type="button">New Item</button>
    <br>
    <div class="scroll">
        <table>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Stock Quantity</th>
                <th>Price</th>
                <th>Action</th>
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
                        <td><a href='item.php?editItemID=$itemID' class='btn'>Edit</td>
                    </div>
                    </tr>";
            }
            mysqli_free_result($rs);
            ?>
        </table>
    </div>
    <?php
    //edit item
    if (!empty($_GET) && !empty($_GET['editItemID'])) {
        require_once('conn.php');
        $sql = "SELECT * FROM item WHERE itemID = '" . $_GET['editItemID'] . "'";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        $row = mysqli_fetch_array($rs);
        $ID = $row['itemID'];
        $name = $row['itemName'];
        $desc = $row['itemDescription'];
        $qty = $row['stockQuantity'];
        $price = $row['price'];
        ?>
        <!--update item box-->
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
                    <td><input type="text" name="desc" value="<?php echo $desc; ?>"></td>
                    <td><input type="text" name="qty" onkeypress="return onlyNumberKey(event)"
                               value="<?php echo $qty; ?>" required></td>
                    <td><input type="text" id="price" name="price" onkeypress="return onlyNumberAndDotKey(event)"
                               value="<?php echo $price; ?>" required></td>
                </tr>
            </table>
            <br>
            <input type="submit" class="button-11" name="submit" value="Edit">
        </form>
        <?php
    } else {
        ?>
        <!--New item box-->
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
                    <td><input type="text" onkeypress="return onlyNumberKey(event)" name="qty"></td>
                    <td><input type="text" id="price" onkeypress="return onlyNumberAndDotKey(event)" name="price"></td>
                </tr>
            </table>
            <br>
            <input type="submit" name="submit" class="button-11" value="Insert">
        </form>
        <?php
    }
    ?>
    </body>
    <!--restrict input non-numeric-->
    <script>
        function onlyNumberAndDotKey(evt) {
            const ASCIICode = (evt.which) ? evt.which : evt.keyCode;
            const dot = document.getElementById('price').value;

            if (ASCIICode == 46) {
                const dot = document.getElementById('price').value;
                if (!(dot.indexOf(".") > -1)) {
                    return true;
                }
            }
            if (dot.charAt(dot.length - 2) == "."){
                return false;
            }
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
                return false;
            }
            return true;
        }

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