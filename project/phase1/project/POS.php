<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];
?>
<script type="text/javascript">

    function yesnoCheck() {
        if (document.getElementById('yesCheck').checked) {
            document.getElementById('ifYes').style.display = 'block';
        }
        else document.getElementById('ifYes').style.display = 'none';

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
            <li><a href="POS.php" class="menubtn">POS</a></li>
            <li><a href="orderRecord.php" class="menubtn">Order Record</a></li>
<!--            <li><a href="" class="menubtn">Delivery</a></li>-->
        </ul>
    </nav>
    <a  href="index.php">
        <button class="log">LOGOUT</button>
    </a>
</header>
<h1 class="title">Point Of Sales</h1>
<form>
    <div id="current_date"></p>
        <script>
            document.getElementById("current_date").innerHTML = Date();
        </script>
    </div>
    <div class="custInfo">
    Customer Name : <input type="text" name="customerName" required>
    Email : <input type="email" name="customerEmail" required>
    Phone Number : <input type="tel" name="phoneNumber" pattern="[0-9]{8}">

    <br><br>
        <select>
            <option selected disabled>Select Product</option>
        </select>
        Quantity : <input type="number" min="1" required>
        <button>+ ADD</button>
    </div>

    <table>
        <tr class="border_bottom">
            <th>Item ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Delete</th>
            <th>Discount</th>
            <th>Total Price</th>


        </tr>
        <tr class="border_bottom">
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td><button>Delete</button></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr class="border_bottom">
            <td colspan="6">Total: $</td>
            <td>1</td>
            <td>1</td>
        </tr>
    </table>
    <div id="YNradio">
    Delivery :
    <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="yesCheck"> Yes
    <input type="radio" onclick="javascript:yesnoCheck();" name="yesno" id="noCheck">No<br>
    </div>
    <div id="ifYes" >
            Delivery Address : <input type="text" name="deliveryAddress"> &nbsp;&nbsp;
            Delivery Date : <input type="date" name="deliveryDate">

    </div>
    <br>
    <input type="submit" class="submit" value="Submit">
    <input type="reset" class="clear" value="Clear">


</form>
</body>
</html>