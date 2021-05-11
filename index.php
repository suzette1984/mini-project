<?php

session_start(); // important function to allow session variables  

if ($_SESSION["loggedIn"] != "true") {

    header("Location: ./login.php"); //send them to the form to login

}
// echo '<pre>';
// var_dump($_SESSION);
// echo '<pre>';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PastelTech</title>
    <link rel="shortcut icon" type="image/png" href="favicon.png" />
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Yusei+Magic&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

    <div id="content">

        <!-- This is the Page Header with related links -->
        <div class="welcomeDiv">
            <header class="page-title">
                <h1><?php echo $_SESSION["FName"] ?>, you can place your new order below! </h1>
                <p>From this page you can place a new order.</p>
            </header>

            <div class="pageOptions flex">
                <!-- <button onclick="newOrder()">+ New Order</button> -->
            </div>
        </div>

        <div id="errorMessage" class="error" hidden>
            There are errors with your order!
        </div>

        <?php include_once("./products.php"); ?>
    </div>
    <script src="js/app.js"></script>
</body>

</html