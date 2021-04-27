<?php
echo
'
<nav>
<div class="navBrand">
    <a href="./index.php">
        <h1>🍩 PastelTech</h1>
    </a>
</div>

<div class="navLinks">';

if (isset($_SESSION["Role"])) {

    if ($_SESSION['loggedIn'] == true && $_SESSION['Role'] == 'user') {
        echo '
        <div class="dropdown">
                <button class="dropbtn">My Account 
                <i class="fa fa-caret-down"></i>
                </button>
            <div class="dropdown-content">
                <a href="./my-account.php">View My Account</a>
                <a href="#">View My Orders</a>
                <a href="#">Create New Order</a>
            </div>
        </div>
        <a href="./logout.php">Logout</a>
        ';
        // echo '
        // <a class="navItem mob-nav " href="./my-account.php">My Account</a>
        // <a class="navItem mob-nav " href="./logout.php">Logout</a>
        // ';
    } elseif ($_SESSION['loggedIn'] == true && $_SESSION['Role'] == 'admin') {
        echo '
        <div class="dropdown">
                <button class="dropbtn">My Account 
                <i class="fa fa-caret-down"></i>
                </button>
            <div class="dropdown-content">
                <a href="./my-account.php">View My Account</a>
                <a href="#">View My Orders</a>
                <a href="#">Create New Order</a>
            </div>
        </div> 

        <div class="dropdown">
                <button class="dropbtn">Owner Dashboard 
                <i class="fa fa-caret-down"></i>
                </button>
            <div class="dropdown-content">
                <a href="./owner.php">Go to Dashboard</a>
                <a href="./customers.php">Manage Users</a>
                <a href="#">Manage Orders</a>
            </div>
        </div> 
        <a href="./logout.php">Logout</a>
        ';
        // echo '
        // <a class="navItem mob-nav" href="./my-account.php">My Account</a>
        // <a class="navItem mob-nav" href="./owner.php">Owner Dashboard</a>
        // <a class="navItem mob-nav" href="./logout.php">Logout</a>
        // ';
    }
} else {
    echo '
    <a href="./login.php">Login</a>
    <a href="./register.php">Register</a>
    ';
    // echo '
    // <a class="navItem mob-nav" href="./login.php">Login</a>
    // <a class="navItem mob-nav" href="./register.php">Register</a>
    // ';
}

echo '  
<img
      src="./img/doughnut menu.min.svg"
      alt="Button to display mobile menu"
      class="mob-nav"
    />

</div>    
</nav>

';
