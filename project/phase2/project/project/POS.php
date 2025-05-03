<?php
session_start();
//var_dump($_SESSION["shopping_cart"]);
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];
//Add item to sales order
if (isset($_GET['clearsession'])) {
    unset($_SESSION["shopping_cart"]);
}
if (isset($_POST["Add_to_cart"])) {

    if (isset($_SESSION["shopping_cart"])) {
        $item_array_id = array_column($_SESSION["shopping_cart"], 'item_id');
        if (!in_array($_GET["itemID"], $item_array_id)) {
            $count = count($_SESSION["shopping_cart"]);
            $item_array = array(
                'item_id' => $_GET['itemID'],
                'item_name' => $_POST['hidden_name'],
                'item_desc' => $_POST['hidden_desc'],
                'item_price' => $_POST['hidden_price'],
                'item_qty' => $_POST['qty']
            );
            $_SESSION["shopping_cart"][$count] = $item_array;
        }
    } else {
        $item_array = array(
            'item_id' => $_GET['itemID'],
            'item_name' => $_POST['hidden_name'],
            'item_desc' => $_POST['hidden_desc'],
            'item_price' => $_POST['hidden_price'],
            'item_qty' => $_POST['qty']
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }
}
//Delete orders item
if (isset($_GET["action"])) {
    if ($_GET["action"] == "delete") {
        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            if ($value["item_id"] == $_GET["id"]) {

                unset($_SESSION["shopping_cart"][$key]);

            }
        }
    }
}

if (!empty($_POST["submitOrder"])) {
    if (!empty($_SESSION["shopping_cart"])) {
        extract($_POST);
        $custEmail = $_POST['custEmail'];
        $custName = $_POST['custName'];
        $phoneNo = $_POST['phoneNo'];
        require_once('conn.php');
        $sql = "SELECT * FROM Customer WHERE customerEmail ='{$custEmail}' ";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        if (mysqli_num_rows($rs) == 0) {
            $sql = "INSERT INTO Customer (customerEmail, customerName, phoneNumber) VALUES ('$custEmail','$custName','$phoneNo');";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
        }

        $sql = "SELECT max(CAST(orderID AS INT))+1 AS 'nextID' FROM Orders;";
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));
        $row = mysqli_fetch_array($rs);
        $orderID = $row['nextID'];
        // echo '<script>alert(" email: ' . $orderID . '");</script>';
        $date = date('Y-m-d H:i:s');
        $orderAmount = $_POST['orderAmount'];
        //$deliveryDate= !empty($deliveryDate) ? "'$deliveryDate'" : "";

        if (empty($deliveryDate)) {
            $sql = "INSERT INTO Orders (orderID, customerEmail, staffID, dateTime, deliveryAddress, deliveryDate, orderAmount)
            VALUES ('$orderID','$custEmail','$staffID','$date', NULL, NULL,'$orderAmount');";
        } else {
            $sql = "INSERT INTO Orders (orderID, customerEmail, staffID, dateTime, deliveryAddress, deliveryDate, orderAmount)
            VALUES ('$orderID','$custEmail','$staffID','$date','$deliveryAddress', '$deliveryDate','$orderAmount');";
        }
        $rs = mysqli_query($conn, $sql)
        or die(mysqli_error($conn));


        foreach (($_SESSION["shopping_cart"]) as $key => $values) {
            $itemName = $values["item_name"];
            $sql = "SELECT itemID   FROM item WHERE itemName = '$itemName';";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            $rc = mysqli_fetch_assoc($rs);
            extract($rc);
            $qty = $values["item_qty"];
            $soldPrice = $values["item_price"];
            $sql = "INSERT INTO ItemOrders (orderID, itemID, orderQuantity, soldPrice)
            VALUES ('$orderID','$itemID','$qty','$soldPrice');";
            $qty = (int)$qty;
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            $sql = "UPDATE item SET stockQuantity = stockQuantity - '$qty' WHERE itemID = '$itemID'";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
        }

        echo '<script>alert("Orders successful, order id: ' . $orderID . '");
        </script>';
        unset($_SESSION["shopping_cart"]);
    } else {
        echo '<script>alert("Please choose item")</script>';
    }
}

?>


<script type="text/javascript">
    // Select delivery
    function yesnoCheck() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'block';
            document.getElementById('address').required = true;
            document.getElementById('date').required = true;
        } else {
            document.getElementById('ifYes').style.display = 'none';
            document.getElementById('address').required = false;
            document.getElementById('date').required = false;

        }
    }

    // Show current
    function refreshTime() {
        const timeDisplay = document.getElementById("current_date");
        const dateString = new Date().toLocaleString();
        const formattedString = dateString.replace(", ", "  ");
        timeDisplay.textContent = formattedString;
    }

    document.getElementById("current_date").innerHTML = setInterval(refreshTime, 1000).toString();

    //Check phone no
    function onlyNumberKey(evt) {
        const ASCIICode = (evt.which) ? evt.which : evt.keyCode;
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) {
            return false;
        }
        return true;
    }
</script>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Point Of Sales</title>
    <link rel="stylesheet" href="css/POS.css">
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
            <li><a href="POS.php?clearsession=true" class="menubtn">POS</a></li>
            <li><a href="orderRecord.php?" class="menubtn">Order Record</a></li>
        </ul>
    </nav>
    <a href="index.php?">
        <button class="log">LOGOUT</button>
    </a>
</header>
<h1 class="title">Point Of Sales</h1>

<!--Select iten on Item List-->
<h3>Item</h3>
<div class="scroll">
    <table id="itemTable">
        <tr>
            <th>Item ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Add</th>
        </tr>
        <tr>
            <?php
            require_once('conn.php');
            $sql = "SELECT * FROM item WHERE stockQuantity > 0;";
            $rs = mysqli_query($conn, $sql)
            or die(mysqli_error($conn));
            while ($row = mysqli_fetch_assoc($rs)) {
            extract($row);
            ?>
            <form method="post" action="POS.php?action=add&itemID=<?php echo $itemID; ?>">
        <tr>
            <div>
                <td><?php echo $row['itemID']; ?></td>
                <td><?php echo $row['itemName']; ?></td>
                <td><?php echo $row['itemDescription']; ?></td>
                <td><?php echo $row['stockQuantity']; ?></td>
                <td>$<?php echo $row['price']; ?></td>
                <input type='hidden' name='hidden_name' value='<?php echo $row['itemName']; ?>'>
                <input type='hidden' name='hidden_desc' value='<?php echo $row['itemDescription']; ?>'>
                <input type='hidden' name='hidden_price' value='<?php echo $row['price']; ?>'>
                <td><input type='number' min='1' name='qty' value='1'></td>
                <td><input type='submit' name='Add_to_cart' class='btn' value='Add'></td>
            </div>
        </tr>
        </form>
        <?php
        }
        ?>

    </table>
</div>

<!--Create Order-->
<h3>Sales Order</h3>
<form action="POS.php" method="post">
    <div id="current_date"></p>
    </div>
    <br>
    <div class="custInfo">
        Customer Name : <input type="text" name="custName" required>
        Email : <input type="email" name="custEmail" required>
        Phone Number : <input type="tel" name="phoneNo" onkeypress="return onlyNumberKey(event)" minlength="8"
                              maxlength="8"">
    </div>

    <table id="order">
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Delete</th>
        </tr>
        <?php
        if (!empty($_SESSION["shopping_cart"])) {
            $total = 0;
            foreach ($_SESSION["shopping_cart"] as $key => $values) {
                ?>
                <tr>
                    <td><?php echo $values["item_name"]; ?></td>
                    <td><?php echo $values["item_desc"]; ?></td>
                    <td><?php echo $values["item_price"]; ?></td>
                    <td><?php echo $values["item_qty"]; ?></td>
                    <td><?php echo number_format($values["item_qty"] * $values["item_price"], 2); ?></td>
                    <td><a href="POS.php?action=delete&id=<?php echo $values["item_id"]; ?>" class='btn' value="delete">Delete</a>
                    </td>
                </tr>
                <?php
                $total = $total + ($values["item_qty"] * $values["item_price"]);
            }
            if (!extension_loaded("curl")) {
                die("enable library curl first");
            }

            $url = "http://127.0.0.1:8080/api/discountCalculator/$total";   # URL is to make GET request to Python RESTful API

            // Initializes a new cURL session
            $curl = curl_init($url);   # Initialize a cURL session
            // to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            // Assume response data is in JSON format
            $data = json_decode($response, true);
            $discountTotal = $data["discount"];

            $orderAmount = $discountTotal;
            ?>
            <tr>
                <td colspan="4">TotalAmount:</td>
                <td colspan="2">$<?php echo number_format($total, 2); ?></td>
            </tr>

            <tr>
                <td colspan="4">TotalAmount(After discount):</td>
                <td colspan="2">$<?php echo number_format($orderAmount, 2); ?></td>
                <input type='hidden' name='orderAmount' value='<?php echo $orderAmount; ?>'>
            </tr>
            <?php
        }
        ?>

    </table>
    <div id="YNradio">
        Delivery :
        <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"> Yes
        <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck" checked="checked">No<br>
    </div>
    <div id="ifYes">
        Delivery Address : <input type="text" name="deliveryAddress" id="address"> &nbsp;&nbsp;
        Delivery Date : <input type="date" name="deliveryDate" id="date">

    </div>
    <br>
    <input type="submit" class="submit" value="Submit" name="submitOrder">

</form>
</body>
</html>
