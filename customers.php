<?php

session_start(); // important function to allow session variables  

if ($_SESSION["loggedIn"] != "true") { // if not logged in

    header("Location: ./login.php"); // send them to the form to login

}

if ($_SESSION["Role"] != "admin") { // If not an admin user

    header("Location: ./my-account.php"); // send them to their my-account area

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

    <title>Davids Doughnuts</title>
</head>

<body>

    <div id="content">
        <!-- This is the Page Header with related links -->
        <div class="welcomeDiv">
            <header class="page-title">
                <h1>View Customers</h1>
            </header>

            <div class="pageOptions">
                <div class="pageLinks">
                    <button onclick="newOrder()">+ New Order</button>
                    <button onclick="newCustomer()">+ New Customer</button>
                    <button onclick="viewCustomers()">View Customers</button>
                </div>
            </div>
        </div>
        <main>
            <?php if (isset($_GET['CustomerID'])) {
                $Customer = $_GET['CustomerID'];
                require_once 'dbConfig.php';

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


                    //Selecting multiple rows from a MySQL database using the PDO::query function.
                    $sql = "SELECT * FROM users WHERE ID=$Customer";

                    //For each result that we return, loop through the result and perform the echo statements.
                    //$row is an array with the fields for each record returned from the SELECT
                    foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {
                        echo '<div class="customer-info">';
                        echo "User ID: " . $row['ID'] . "<br>";
                        echo "<strong>First Name: </strong>" . $row['user_firstname'] . "<br>";
                        echo "<strong>Last Name: </strong>" . $row['user_lastname'] . "<br>";
                        echo "<strong>Email: </strong>" . $row['user_email'] . "<br>";
                        echo "<strong>Admin User? </strong>";

                        if ($row['userType'] == "admin") {
                            echo "Yes <button>Make Standard User</button>";
                        } else {
                            echo "No <button>Make Admin</button>";
                        };

                        echo "<br>";
                        echo "<strong>No. of Orders: </strong>" . $row['user_id'] . "<br>";
                        echo '</div>';
                    }
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage(); //Throw an error if this fails
                }

                echo '
            <div class="customer-orders" id="customer-orders">
            <p>Customers Orders</p>
                <table>
                    <tr>
                        <th>Order ID/th>
                        <th>Order Total</th>
                        <th>Order Date</th>
                        <th>Customer ID</th>
                       
                    </tr>';

                require_once 'dbConfig.php';

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    //Selecting multiple rows from a MySQL database using the PDO::query function.
                    $sql = "SELECT order_id, order_total, order_date, user_id
                    FROM orders WHERE user_id=$Customer";

                    //For each result that we return, loop through the result and perform the echo statements.
                    //$row is an array with the fields for each record returned from the SELECT
                    foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {
                        echo "<tr>";

                        echo "<td>" . $row['order_id'] . "</td>";
                        echo "<td> £" . number_format($row['order_total'], 2) . "</td>";
                        echo "<td>" . $row['order_date'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "</tr>";
                    }
                    echo '</table></div>';
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage(); //Throw an error if this fails
                }
            } else {
                echo '
            <div class="customer-list" id="customer-list">
            <p>All Customers</p>
                <table>
                    <tr>
                        <th>Customer ID</th>
                        <th>Email</th>
                        <th>First Name</th>
                        <th>Last Name</th>                        
                        <th>Type</th>
                        <th>Address</th>
                        <th>Address</th>
                        <th>Address</th>
                        <th>Postcode</th>
                    </tr>';

                require_once 'dbConfig.php';

                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    //Selecting multiple rows from a MySQL database using the PDO::query function.
                    $sql = "SELECT user_id, user_email, user_password, user_firstname, user_lastname, userType, user_add1, user_add2, user_postcode
                    FROM users";

                    //For each result that we return, loop through the result and perform the echo statements.
                    //$row is an array with the fields for each record returned from the SELECT
                    foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['user_email'] . "</td>";
                        echo "<td>" . $row['user_firstname'] . "</td>";
                        echo "<td>" . $row['user_lastname'] . "</td>";
                        echo "<td>" . $row['user_type'] . "</td>";
                        echo "<td>" . $row['user_add1'] . "</td>";
                        echo "<td>" . $row['user_add2'] . "</td>";
                        echo "<td>" . $row['user_postcode'] . "</td>";
                        echo '<td> <a href="./customers.php?user_id=' . $row['user_id'] . '">View</a></td>';
                        echo "</tr>";
                    }
                    echo '</table></div>';
                } catch (PDOException $e) {
                    echo $sql . "<br>" . $e->getMessage(); //Throw an error if this fails
                }
            } ?>
        </main>
    </div>
    <script src="js/app.js"></script>
</body>

</html>