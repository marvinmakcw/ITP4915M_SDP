<?php
session_start();
$staffID = $_SESSION['staffID'];
$staffName = $_SESSION['staffName'];
$position = $_SESSION['position'];
?>
<!DOCTYPE html>
<html lang="en">
<script
        src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/report.css">
</head>
<body>
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
<?php
require_once('conn.php');
$sql = "SELECT COUNT(orderID) AS orders, MONTH(dateTime) AS  month FROM orders GROUP BY month;";
$rs = mysqli_query($conn, $sql)
or die(mysqli_error($conn));
$mOrders = array();
$month = array();
$orders = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $mOrders[] = array($row['month'] => $row['orders']);
    $month[] = $row['month'];
    $orders[] = $row['orders'];
}
$sql = "SELECT staffID, staffName FROM staff WHERE POSITION = 'Staff';";
$rs = mysqli_query($conn, $sql)
or die(mysqli_error($conn));
$staffs = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $staffs[] = array($row['staffID'] => $row['staffName']);
}

?>
<h1 class="title">Monthly Report</h1>
<div id="chooseStaff">
    <label>Choose a staff:</label>
    <select name="staffs" id="staffs" onchange="staffChange();">
        <option value="All">All staff</option>
        <?php foreach ($staffs as $array) {
            foreach ($array as $key => $value) { ?>
                <option value="<?php echo "$key"; ?>"><?php echo "$key: $value"; ?></option>
            <?php }
        } ?>
    </select>
</div>
<table class="vertical-center">
    <tr>
        <div class="vertical-center">
            <button class="button-55" onclick="report('Order');"><img src="image/order_l.png"><br>
                <div>Order</div>
            </button>
            <button class="button-55" onclick="report('Sales');"><img src="image/sales_l.png"><br>
                <div>Sales</div>
            </button>
            <button class="button-55" onclick="report('Customer');"><img src="image/customer_l.png"><br>
                <div>Customer</div>
            </button>
        </div>
    </tr>
</table>
<script>
    let getStaff = "All staff";
    let getStaffID = "All";

    function staffChange() {
        const e = document.getElementById("staffs");
        getStaff = e.options[e.selectedIndex].text;
        getStaffID = document.getElementById("staffs").value;
    }

    function report(value) {
        document.getElementById("chart").innerHTML = "Line Chart: " + value;
        chart.options.title.text = value + " Report (" + getStaff + ")";
        chart.update();
    }

</script>
<?php /*
$staffID = "s0001";
$sql = "select orders.staffID, count(orderID) as orders, month(dateTime) as month from orders, staff WHERE staff.staffID = orders.staffID and orders.staffID = '$staffID' GROUP BY month;";
$rs = mysqli_query($conn, $sql)
or die(mysqli_error($conn));
$mOrders = array();
$month = array();
$orders = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $mOrders[] = array($row['month'] => $row['orders']);
    $month[] = $row['month'];
    $orders[] = $row['orders'];
}*/
/*SELECT staff.staffID,staff.staffName,Count(orders.staffID),Sum(orders.orderAmount)
FROM orders,staff
WHERE staff.staffID = Orders.staffID
GROUP BY staff.staffID,staff.staffName; */
?>
<div class="vertical-center" id="chart">Line Chart: Order</div>
<script type="text/javascript">
    let orders =<?php echo json_encode($orders); ?>;
    let month =<?php echo json_encode($month); ?>;

    function toMonthName(month) {
        const date = new Date();
        date.setMonth(month - 1);
        return date.toLocaleString('en-US', {
            month: 'long',
        });
    }

    for (let index = 0; index < month.length; index++) {
        month[index] = toMonthName(month[index]);
    }
</script>
<canvas id="myChart"></canvas>
<script>
    let chart = new Chart("myChart", {
        type: "line",
        data: {
            labels: month,
            datasets: [{
                fill: false,
                lineTension: 0,
                backgroundColor: "rgba(0,0,255,1.0)",
                borderColor: "rgba(0,0,255,0.1)",
                data: orders
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Orders per month'
                    },
                    ticks: {
                        stepSize: 1,
                        min: 0
                    }
                }],
                xAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }]
            },
            legend: {
                display: false
            },
            title: {
                display: true,
                text: 'Order Report (All staff)'
            },
        }
    });
</script>
</body>
</html>
