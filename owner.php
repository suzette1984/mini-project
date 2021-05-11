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

    <title>PastelTech - Owner Dashboard</title>
</head>

<body>

    <div id="content">
        <!-- This is the Page Header with related links -->
        <div class="welcomeDiv">
            <header class="page-title">
                <h1>Welcome to the Owner Dashboard, <?php echo $_SESSION["FName"] ?>! </h1>
            </header>

            <div class="pageOptions">
                <div class="pageLinks">
                    <!-- <button onclick="newOrder()">+ New Order</button>
                    <button onclick="newCustomer()">+ New Customer</button>
                    <button onclick="viewCustomers()">View Customers</button> -->
                    <a href="./index.php" class="btn">+ New Order</a>
                    <a href="./createCustomer.php" class="btn">New Customer</a>
                    <a href="./customers.php" class="btn" role="button">View Customers</a>
                </div>
                <div class="searchFields">
                    <div class="searchByName">
                        <label for="name"> Search by Order Name: </label>
                        <select name="name" id="name" onchange="ordersByName(this.value)">
                            <?php require_once 'dbConfig.php';

                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                                // set the PDO error mode to exception
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                //Selecting multiple rows from a MySQL database using the PDO::query function.
                                $sql = "SELECT DISTINCT customer_name FROM orders";

                                //For each result that we return, loop through the result and perform the echo statements.
                                //$row is an array with the fields for each record returned from the SELECT
                                foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {
                                    echo "<option value='" . $row['customer_name'] . "'>" . $row['customer_name'] . "</option>";
                                }
                            } catch (PDOException $e) {
                                echo $sql . "<br>" . $e->getMessage(); //Throw an error if this fails
                            } ?>
                        </select>
                    </div>
                    <div class="searchByDate">
                        <label for="date"> Search by Order Date: </label>
                        <input type="date" name="date" id="date" onchange="ordersByDate(this.value)">
                    </div>
                    <!-- <div class="fetchAll"><button onclick="fetchAll()">Fetch <strong>All</strong> Orders</button><br> -->
                    <a class="btn" onclick="fetchAll()" role="button">Fetch <strong>All</strong> Orders</a>
                </div>
            </div>
        </div>
        <main>
            <div class="results" id="results">
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Total</th>
                        <th>Order Date</th>
                        <th>User ID</th>


                    </tr>
                    <?php require_once 'dbConfig.php';

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        //Selecting multiple rows from a MySQL database using the PDO::query function.
                        $sql = "SELECT order_id, order_total, order_date, user_id
                    FROM orders";

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
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage(); //Throw an error if this fails
                    } ?>
                </table>
            </div>
        </main>
    </div>
    </div>
    <script src="js/app.js"></script>
</body>

</html>