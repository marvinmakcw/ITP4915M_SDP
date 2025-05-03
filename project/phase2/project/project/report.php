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
//get staffs details
require_once('conn.php');
$sql = "SELECT staffID, staffName FROM staff WHERE POSITION = 'Staff';";
$rs = mysqli_query($conn, $sql)
or die(mysqli_error($conn));
$staffs = array();
while ($row = mysqli_fetch_assoc($rs)) {
    $staffs[] = array($row['staffID'] => $row['staffName']);
}
mysqli_free_result($rs);
?>
<h1 class="title">Monthly Report</h1>
<div id="chooseStaff"><br><br>
    <label>Choose a staff:</label>
    <select name="staffs" id="mySelect" onchange="staffChange(this.value);">
        <option value="All">All staff</option>
        <?php foreach ($staffs as $array) {
            foreach ($array as $key => $value) { ?>
                <option value="<?php echo "$key"; ?>"><?php echo "$key: $value"; ?></option>
            <?php }
        } ?>
    </select>
    <br><br>
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
        </div>
    </tr>
</table>
<br>
<script>
    let getStaff = "All staff";
    let getStaffID = "All";
    let getReport = "";

    function staffChange(id) {
        const e = document.getElementById("mySelect");
        getStaff = e.options[e.selectedIndex].text;
        getStaffID = id;
    }

    function report(value) {
        <!--show order report-->
        if (value == "Order") {
            window.location.href = "report.php?report=order&id=" + getStaffID;
            <?php
            $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders GROUP BY month;";
            if (!empty($_GET['id'])) {
                $staffID = $_GET['id'];
                if ($staffID == "All") {
                    $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders GROUP BY month;";
                } else if ($staffID == "s0001") {
                    $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders WHERE staffID = 's0001' GROUP BY month;";

                } else if ($staffID == "s0003") {
                    $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders WHERE staffID = 's0003' GROUP BY month;";
                }
                require_once('conn.php');
                $rs = mysqli_query($conn, $sql)
                or die(mysqli_error($conn));
                $mOrders = array();
                $month = array();
                $orders = array();
                $sales = array();
                while ($row = mysqli_fetch_assoc($rs)) {
                    $mOrders[] = array($row['month'] => $row['orders']);
                    $month[] = $row['month'];
                    $orders[] = $row['orders'];
                    $sales[] = $row['sales'];
                }
                mysqli_free_result($rs);
            }
            ?>
            <!--show sales report-->
        } else if (value == "Sales") {
            window.location.href = "report.php?report=sales&id=" + getStaffID;

            <?php
            $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders GROUP BY month;";
            if (!empty($_GET['id'])) {
                $staffID = $_GET['id'];
                if ($staffID == "All") {
                    $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders GROUP BY month;";
                } else if ($staffID == "s0001") {
                    $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders WHERE staffID = 's0001' GROUP BY month;";

                } else if ($staffID == "s0003") {
                    $sql = "SELECT COUNT(orderID) AS orders, SUM(orderAmount) AS sales, MONTH(dateTime) AS  month FROM orders WHERE staffID = 's0003' GROUP BY month;";
                }
                require_once('conn.php');
                $rs = mysqli_query($conn, $sql)
                or die(mysqli_error($conn));
                $mOrders = array();
                $month = array();
                $orders = array();
                $sales = array();
                while ($row = mysqli_fetch_assoc($rs)) {
                    $mOrders[] = array($row['month'] => $row['orders']);
                    $month[] = $row['month'];
                    $orders[] = $row['orders'];
                    $sales[] = $row['sales'];
                }
                mysqli_free_result($rs);
            }
            ?>
        }
    }
</script>
<!--canvas line chart-->
<canvas id="myChart" style="display: "></canvas>
<canvas id="myChart2" style="display: "></canvas>

<div class="vertical-center" id="chart"></div>
<script type="text/javascript">
    let orders =<?php echo json_encode($orders); ?>;
    let sales =<?php echo json_encode($sales); ?>;
    let month =<?php echo json_encode($month); ?>;
    getStaff = <?php echo json_encode($_GET['id']); ?>;
    getReport = <?php echo json_encode($_GET['report']); ?>;

    console.log(getReport);
    if (getReport == "order") {
        document.getElementById("myChart2").style.display = "none";
    }
    if (getReport == "sales") {
        document.getElementById("myChart").style.display = "none";
    }
    if (getStaff == "All") {
        document.getElementById("mySelect").selectedIndex = 0;
        const e = document.getElementById("mySelect");
        getStaffID = e.value;
    }
    if (getStaff == "s0001") {
        document.getElementById("mySelect").selectedIndex = 1;
        const e = document.getElementById("mySelect");
        getStaffID = e.value;
    }
    if (getStaff == "s0003") {
        document.getElementById("mySelect").selectedIndex = 2;
        const e = document.getElementById("mySelect");
        getStaffID = e.value;
    }
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
    console.log(showOrder);
    console.log(showSales);
</script>

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
                text: 'Order Report (' + getStaff + ')'
            },
        }
    });
</script>


<script>
    let chart2 = new Chart("myChart2", {
        type: "line",
        data: {
            labels: month,
            datasets: [{
                fill: false,
                lineTension: 0,
                backgroundColor: "rgba(0,0,255,1.0)",
                borderColor: "rgba(0,0,255,0.1)",
                data: sales
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'Sales per month'
                    },
                    ticks: {
                        stepSize: 100000,
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
                text: 'Sales Report (' + getStaff + ')'
            },
        }
    });
</script>
</body>
</html>
