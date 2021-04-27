<?php

if (!empty($_SESSION["shopping_cart"])) {
    //counting the number of things in the shopping cart
    $cart_count = count(array_keys($_SESSION["shopping_cart"]));
    echo '
<div class="cart_div">
    <a href="cart.php"><img src="images/cart.png"  /> Products in the basket: <span>' . $cart_count . '</span></a>
</div>
';
}
