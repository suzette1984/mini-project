<?php

session_start(); // important function to allow session variables  

if ($_SESSION["loggedIn"] != "true") {

    header("Location: ./login.php"); //send them to the form to login

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" type="image/png" href="favicon.png" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Yusei+Magic&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />

    <title>My Account - PastelTech</title>
</head>

<body>
    <?php include_once('./includes/navbar.php') ?>
    <div id="content">

        <?php
        if (isset($_GET["OrderID"]) && $_GET["Intent"] === "edit") {
            echo '<main>';
            require_once("./includes/editOrderLogic.php");
            echo '</main>';
        } elseif (isset($_GET["OrderID"]) && $_GET["Intent"] === "cancel") {
            echo '<main>';
            require_once("./includes/cancelOrderLogic.php");
            echo '</main>';
        } else {
            include_once("./includes/my-orders.php");
        }
        ?>

    </div>
    <script src="js/app.js"></script>
</body>

</html>