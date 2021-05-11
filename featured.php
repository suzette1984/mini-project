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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>featured</title>
</head>

<body>
    <?php
    require('dbConfig.php');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //display products
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //Selecting multiple rows from a MySQL database using the PDO::query function.

            $sql = $conn->prepare("SELECT `product_id`, `product_name` FROM product ORDER BY product_id DESC");

            $sql->execute(); //execute the statement
            $product_array = $sql->fetchAll();

            if ($sql->rowCount()) {
                echo '
                <form action="./featured.php" method= "post">
                <label for="selectedProduct">Please select a product that you would like to make <strong>featured</strong></label>
                <select name="selectedProduct" id="selectedProduct">
                ';
                //check if we have results by counting rows
                foreach ($product_array as $row) {
                    //Loop here to obtain images linked to product
                    echo '<option value="' . $row['product_id'] . '" >' . $row['product_name'] . '</option>';
                }
                echo '</select>
                <button type="submit">Set Featured</button>
                </form>';
            } else {
                echo 'No products setup!';
            }
        } catch (PDOException $e) {
            echo $sql2 . "<br>" . $e->getMessage(); //If we are not successful we will see an error
        }
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['selectedProduct'])) {
        $SelectedProduct = $_POST['selectedProduct'];
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE product
            SET featured= '0'
            WHERE NOT featured = '0'";
            // use exec() because no results are returned
            $conn->exec($sql);

            $sql = "UPDATE product
            SET featured= '1'
            WHERE product_id = $SelectedProduct";
            // use exec() because no results are returned
            $conn->exec($sql);

            echo 'Your selected product has been made featured.';
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
        }
    }

    ?>
</body>

</html>