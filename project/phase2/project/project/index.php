<?php
if (empty($_POST)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <link rel="stylesheet" href="css/index.css">
    </head>
    <body>
    <!--login function-->
    <form class="box" action="index.php" method="post">
        <h1>Better Limited</h1>
        <h2>Login</h2>
        <input type="text" name="id" placeholder="Staff ID" required>
        <input type="password" name="pwd" placeholder="Password" required>
        <input type="submit" name="submit" value="Login">
    </form>
    </body>
    </html>
    <?php
} else {
//    check login successful or not
    extract($_POST);
    require_once('conn.php');
    $sql = "SELECT * FROM staff where staffID = '$id' and password = '$pwd'";
    $rs = mysqli_query($conn, $sql)
    or die(mysqli_error($conn));
    $row = mysqli_fetch_array($rs);
    if (mysqli_num_rows($rs) > 0) {
        session_start();
        $_SESSION['staffID'] = $row['staffID'];
        $_SESSION['staffName'] = $row['staffName'];
        $_SESSION['position'] = $row['position'];
        echo '<script>alert("Login successful, welcome ' . $id . '"); 
                window.location.href="menu.php";</script>';
    } else {
        echo '<script>alert("Login failed."); 
                window.history.back();</script>';
    }
    mysqli_free_result($rs);
    mysqli_close($conn);
}
?>