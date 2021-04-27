<?php
session_start(); //needed so we can read what's in the shopping cart

include('dbconfig.php');

$status = ""; //initiating the variable to use later
if (isset($_POST['product_id']) && $_POST['product_id'] != "") {
    $product_id = $_POST['product_id']; //For product ID to exist, someone must have pressed buy on a product
    if (isset($_POST['product_image'])) {
        $product_image = $_POST['product_image']; //For product image to exist, someone must have pressed buy on a product and product image must be in database
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //We search for the product that has been clicked on
        $sql = $conn->prepare('SELECT * FROM product WHERE product_id= :product_id');
        $sql->execute(['product_id' => $product_id]); //execute the statement
        $row = $sql->fetch();

        $product_name = $row['product_name'];
        $product_id = $row['product_id'];
        $product_price = $row['product_price'];




        //You could perform another search here to obtain the product image

        $cartArray = array(
            $product_id => array(
                'product_name' => $product_name,
                'product_id' => $product_id,
                'product_price' => $product_price,
                'product_quantity' => 1,
                'product_image' => $product_image,
            )
        );
        // we perform some logic that detects if the product is already in the basket.
        // If it is, we display an error message. Increasing quantity is handled on the cart page
        if (empty($_SESSION["shopping_cart"])) {
            $_SESSION["shopping_cart"] = $cartArray;
            $status = "<div class='box'>Product is added to your cart!</div>";
        } else {
            $array_keys = array_keys($_SESSION["shopping_cart"]);
            if (in_array($product_id, $array_keys)) {
                $status = "<div class='box' style='color:red;'>
            Product is already added to your cart!</div>";
            } else {
                $_SESSION["shopping_cart"] = $_SESSION["shopping_cart"] + $cartArray;
                $status = "<div class='box'>Product is added to your cart!</div>";
            }
        }
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Products</title>
    <style>
        body {
            font-family: "verdana", sans-serif;
            color: pink;
            background-color: pink;
        }

        .buy {
            background-color: green;
            color: white;
        }

        .product_wrapper {
            margin: 10px 1% 0 0;
            width: 40%;
            padding: 4%;
            height: inherit;
            background-color: grey;
            float: left;

        }

        .product_image {
            width: 100px;
            height: 75px;
        }
    </style>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Products</title>
        <style>
            body {
                font-family: "verdana", sans-serif;
                color: white;
            }

            .buy {
                background-color: green;
                color: white;
            }

            .product_wrapper {
                margin: 10px 1% 0 0;
                width: 40%;
                padding: 4%;
                height: inherit;
                background-color: grey;
                float: left;

            }

            .product_image {
                width: 100px;
                height: 75px;
            }
        </style>

    </head>

<body>
    <?php include 'cart_count.php'; ?>
    <?php

    //display products
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //Selecting multiple rows from a MySQL database using the PDO::query function.

        $sql = $conn->prepare("SELECT * FROM product ORDER BY product_id DESC");

        $sql->execute(); //execute the statement
        $product_array = $sql->fetchAll();

        if ($sql->rowCount()) { //check if we have results by counting rows
            foreach ($product_array as $row) {
                //Loop here to obtain images linked to product
                $current_product = $row['product_id'];

                try {
                    $sql2 = $conn->prepare('SELECT * FROM product_images WHERE product_id= :product_id');
                    $sql2->execute(['product_id' => $current_product]); //execute the statement
                    $row2 = $sql2->fetch();
                    $product_image = $row2['product_image_filename'];
                    //echo "product image is". $product_image;
                    //exit();  
                } catch (PDOException $e) {
                    echo $sql2 . "<br>" . $e->getMessage(); //If we are not successful we will see an error
                }

                //include a code snippet to show preview of each product


                echo " <div class='product_wrapper'>
                        <img class='product_image' src='" . $product_image . "'>
                        <form method='post' action=''>
                        <input type='hidden' name='product_id' value=" . $row['product_id'] . " />
                        <input type='hidden' name='product_image' value=" . $product_image . " />
                        <div class='name'>" . $row['product_name'] . "</div>
                        <div class='price'>£" . number_format($row['product_price'], 2) . "</div>";

                echo '<a href="moreinfo.php?product_id=' . $current_product . '" class="button">More Info</a><br>';

                echo "<button type='submit' class='buy'>Buy Now</button>
                        </form>
                        </div>";
            }
        } else {
            echo 'no products to show'; //message to display if the search returned no results
        }
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
    }
    ?>

    <div style="clear:both;"></div>

    <div class="message_box" style="margin:10px 0px;">
        <?php echo $status; ?>
    </div>

    </div>
</body>

</html>