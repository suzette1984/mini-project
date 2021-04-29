<?php
session_start();
include "dbconfig.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Basket</title>
    <style>
        body {
            font-family: "arial", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #999;
        }

        .grid-container {
            display: grid;
            grid-template-columns: auto auto auto auto auto auto;
            /*grid-gap: 10px;*/

            padding: 10px;
        }

        .grid-item-header {
            background-color: #777;
            padding: 10px 0 10px 0;
            margin: 0 0 10px 0;
        }

        .grid-item-header p {
            margin: 0 0 0 10px;
        }

        .total-text {
            text-align: right;
            width: 99%;
            margin: 1% 1% 0 0;
        }

        .checkout {
            background-color: green;
            border-radius: 5px;
            margin: 10px;
            float: right;
            padding: 10px;
            color: #fff;
            text-decoration: none;
        }

        .empty-basket {
            background-color: grey;
            border-radius: 5px;
            margin: 10px;
            float: right;
            padding: 10px;
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>

<body>


    <?php

    // Troubleshooting
    print_r($_SESSION);

    if (isset($_SESSION["shopping_cart"])) {
        $total_price = 0;
    ?>
        <h1>Confirmation of your order</h1>
        <div class="grid-container">
            <div class="grid-item-header">
                <p>ITEM IMAGE</p>
            </div>
            <div class="grid-item-header">
                <p>ITEM NAME</p>
            </div>
            <div class="grid-item-header">
                <p>QUANTITY</p>
            </div>
            <div class="grid-item-header">
                <p>UNIT PRICE</p>
            </div>
            <div class="grid-item-header">
                <p>ITEMS TOTAL</p>
            </div>
            <div class="grid-item-header">
                <p>REMOVE</p>
                <!-- as thinking delete from database and daft thought detroy session go from here also add in sessuon to confirm logged in -->
            </div>

            <?php
            foreach ($_SESSION["shopping_cart"] as $product) {
            ?>

                <div class="grid-item">
                    <img src='<?php echo $product["product_image"]; ?>' width="50" height="40" />
                </div>
                <div class="grid-item">
                    <?php echo $product["product_name"]; ?>
                </div>
                <div class="grid-item">
                    <?php echo $product["product_quantity"]; ?>
                </div>
                <div class="grid-item"><?php echo "£" . number_format($product["product_price"], 2); ?></div>
                <div class="grid-item"><?php $product_total = $product["product_price"] * $product["product_quantity"];
                                        echo "£ " . number_format($product_total, 2) ?></div>

                <div class="grid-item">
                    <form method="post" action="">
                        <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>" />
                        <input type="hidden" name="action" value="remove" />
                        <button type="submit" class="remove">Remove Item</button>
                    </form>
                </div>

            <?php
                $total_price += ($product["product_price"] * $product["product_quantity"]);
            }
            ?>
        </div>
        <p class="total-text">TOTAL: <?php echo "£" . $total_price; ?></p>


        <?php
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $user_id = '1'; // for debug purposes - should be replaced with SESSION var
            //we insert the main order details and save the last id
            $sql = "INSERT INTO orders (order_total, order_date, user_id) VALUES ('$total_price', now(), '$user_id')"; // building a string with the SQL INSERT you want to run
            //echo $sql;
            //exit();
            // use exec() because no results are returned
            $conn->exec($sql);
            $last_id = $conn->lastInsertId();
            //loop round and insert all of the product items from the basket
            foreach ($_SESSION["shopping_cart"] as $product) {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = 'INSERT INTO order_items (product_id, quantity, order_id) VALUES (' . $product['product_id'] . ', ' . $product['product_quantity'] . ', ' . $last_id . ')'; // building a string with the SQL INSERT you want to run
                $conn->exec($sql);
            }
        } catch (PDOException $e) {
            echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
        }
        ?>



    <?php
    } else {
        echo "<h3>No order to process!</h3>";
    }
    ?>
    </div>

    <div style="clear:both;"></div>
    <p>Thankyou for ordering with us, we hope to see you again soon</p>
    <div class="message_box" style="margin:10px 0px;">

    </div>


</body>

</html>