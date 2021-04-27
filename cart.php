<?php
session_start();
$status = "";
if (isset($_POST['action']) && $_POST['action'] == "remove") {

    if (!empty($_SESSION["shopping_cart"])) {

        foreach ($_SESSION["shopping_cart"] as $key => $value) {
            echo "product id is" . $_POST['product_id'] . "</br>";
            echo "key is" . $key . "</br>";

            if ($_POST["product_id"] == $key) {



                unset($_SESSION["shopping_cart"][$key]);
                $status = "<div class='box' style='color:red;'>
      Product is removed from your cart!</div>";
            }
            if (empty($_SESSION["shopping_cart"]))
                unset($_SESSION["shopping_cart"]);
        }
    }
}

if (isset($_POST['action']) && $_POST['action'] == "change") {

    foreach ($_SESSION["shopping_cart"] as &$value) {
        if ($value['product_id'] === $_POST["product_id"]) {

            $value['product_quantity'] = $_POST["product_quantity"];
            break; // Stop the loop after we've found the product
        }
    }
}


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
    //print_r($_SESSION);

    if (isset($_SESSION["shopping_cart"])) {
        $total_price = 0;
    ?>
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
                    <form method='post' action=''>
                        <input type='hidden' name='product_id' value="<?php echo $product["product_id"]; ?>" />
                        <input type='hidden' name='action' value="change" />
                        <select name='product_quantity' class='quantity' onChange="this.form.submit()">
                            <option <?php if ($product["product_quantity"] == 1) echo "selected"; ?> value="1">1</option>
                            <option <?php if ($product["product_quantity"] == 2) echo "selected"; ?> value="2">2</option>
                            <option <?php if ($product["product_quantity"] == 3) echo "selected"; ?> value="3">3</option>
                            <option <?php if ($product["product_quantity"] == 4) echo "selected"; ?> value="4">4</option>
                            <option <?php if ($product["product_quantity"] == 5) echo "selected"; ?> value="5">5</option>
                        </select>
                    </form>
                </div>
                <div class="grid-item"><?php echo "£" . number_format($product["product_price"], 2); ?></div>
                <div class="grid-item"><?php $product_total = $product["product_price"] * $product["product_quantity"];
                                        echo "£ " . number_format($product_total, 2) ?></div>
                <div class="grid-item">
                    <form method='post' action=''>
                        <input type='hidden' name='product_id' value="<?php echo $product["product_id"]; ?>" />
                        <input type='hidden' name='action' value="remove" />
                        <button type='submit' class='remove'>Remove Item</button>
                    </form>
                </div>

            <?php
                $total_price += ($product["product_price"] * $product["product_quantity"]);
            }
            ?>
        </div>
        <p class="total-text">TOTAL: <?php echo "£" . $total_price; ?></p>
        <a class="checkout" href="add_to_order.php"> Checkout</a>
        <a class="empty-basket" href="empty_basket.php"> Empty your basket</a>



    <?php
    } else {
        echo "<h3>Your cart is empty!</h3>";
    }
    ?>
    </div>

    <div style="clear:both;"></div>

    <div class="message_box" style="margin:10px 0px;">
        <?php echo $status; ?>
    </div>


</body>

</html>