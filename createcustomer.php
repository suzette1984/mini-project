<?php

session_start(); // important function to allow session variables  

if ($_SESSION["loggedIn"] != "true") { // if not logged in

    header("Location: ./login.php"); // send them to the form to login

}

if ($_SESSION["Role"] != "admin") { // If not an admin user

    header("Location: ./my-account.php"); // send them to their my-account area

}

// init error message placeholder
$errorMsg;
$fNameErr;
$lNameErr;
$emailErr;
$passWeakErr;
// $passMatchErr;

require_once 'dbconfig.php'; // import the DB login credz

try {
    if ($_SESSION['Role'] == "admin" && $_SERVER['REQUEST_METHOD'] == 'POST') //has the user submitted the form
    {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // save the firstname the user submitted from $_POST
        $Fname = $_POST['fName'];
        // save the surname the user submitted from $_POST
        $Lname = $_POST['lName'];
        // save the email the user submitted from $_POST
        $Email = $_POST['email'];
        // save the password the user submitted from $_POST
        $Password = $_POST['password'];
        // What is the type of user submitted from $_POST
        //$UserType = $_POST['isAdmin'];

        // Validate First Name - https://www.w3schools.com/php/php_form_url_email.asp
        if (!preg_match("/^[a-zA-Z-' ]*$/", $Fname)) {
            $fNameErr = true;
        } else {

            $fNameErr = false;
        }

        // Validate Last Name - https://www.w3schools.com/php/php_form_url_email.asp
        if (!preg_match("/^[a-zA-Z-' ]*$/", $Lname)) {
            $lNameErr = true;
        } else {

            $lNameErr = false;
        }

        // Validate Email is valid format - https://www.w3schools.com/php/php_form_url_email.asp
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = true;
        } else {

            $emailErr = false;
        }

        // Validate Password Length and that Passwords Match
        if (strlen($Password) < 8) {
            $passWeakErr = true;
        }
        // elseif ($Password !== $Password2) {
        //     $passMatchErr = true;
        // } else {
        //     $passWeakErr = $passMatchErr = false;
        // }

        if (isset($_POST['isAdmin']) && $_POST['isAdmin'] == "on") {
            $UserType = "admin";
        } else {
            $UserType = "user";
        }

        // Check for Errors
        if ($fNameErr || $lNameErr || $emailErr || $passWeakErr || $passMatchErr) {
            // There are errors with the entered values - Return to form and display errors
        } else {

            //preparing an sql statement to search email and password for whatever the user has typed and be equal to an admin user
            $sql = $conn->prepare("INSERT INTO `users` (`fName`, `lName`, `email`, `password`, `userType`) VALUES (?,?,?,?,?);");
            $sql->bindValue(1, $Fname); //First Name
            $sql->bindValue(2, $Lname); //Last Name
            $sql->bindValue(3, $Email); //Email Address
            $sql->bindValue(4, $Password); //Validated Password
            $sql->bindValue(5, $UserType); //user type
            $sql->execute(); //execute the statement

            $NewCustomerID = $conn->lastInsertId(); // Get User ID for the user that was just registered and added to DB

            $sql = $conn->prepare("SELECT * FROM users WHERE email=? AND ID=?");
            $sql->bindValue(1, $Email); //we bind this variable to the first ? in the sql statement
            $sql->bindValue(2, $NewCustomerID); //we bind this value to the second ? in the sql statement
            $sql->execute(); //execute the statement

            if ($sql->rowCount()) { //check if we have results by counting rows

                $row = $sql->fetch();

                //redirect users to their account 
                header("Location: ./customers.php?CustomerID=" . $NewCustomerID);
            } else {
                //message to display if we did not match a user
                $errorMsg = "There was an error creating the account.";
            }
        }
    } else {
        //There was an error with registration - Return to Registration Page and Show Errors
    }
} catch (PDOException $e) {
    echo $e->getMessage();  //If we are not successful in connecting or running the query we will see an error
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

    <title>Create Customer - PastelTech</title>
</head>

<body>
    <?php include_once('./includes/navbar.php') ?>
    <div id="content">
        <!-- This is the Page Header with related links -->
        <div class="welcomeDiv">
            <header class="page-title">
                <h1>Create a New Customer</h1>
            </header>

            <div class="pageOptions">
                <div class="pageLinks">
                    <!-- <button onclick="newOrder()">+ New Order</button>
                    <button onclick="newCustomer()">+ New Customer</button>
                    <button onclick="viewCustomers()">View Customers</button> -->
                    <a href="./index.php" class="btn">+ New Order</a>
                    <a href="./createCustomer.php" class="btn">New Customer</a>
                    <a href="./customers.php" class="btn">View Customers</a>
                </div>
            </div>
        </div>
        <main>
            <div class="create-cust-form" id="create-cust-form">
                <form action="./createCustomer.php" method="post">
                    <div class="name-fields">
                        <div>
                            <label for="fName">First Name: </label>
                            <input type="text" name="fName" id="fName" value="<?php if (isset($Fname)) {
                                                                                    echo $Fname;
                                                                                } ?>" required>
                            <?php if (isset($fNameErr) && $fNameErr == true) {
                                echo '<p class="error">Error: Your First Name is required, valid characters are letters, dashes, apostrophes and spaces</p>';
                            } ?>
                        </div>
                        <div>
                            <label for="lName">Last Name: </label>
                            <input class="halfWidth" type="text" name="lName" id="lName" value="<?php if (isset($Lname)) {
                                                                                                    echo $Lname;
                                                                                                } ?>" required>
                            <?php if (isset($lNameErr) && $lNameErr == true) {
                                echo '<p class="error">Error: Your Surname is required, valid characters are letters, dashes, apostrophes and spaces</p>';
                            } ?>
                        </div>
                    </div>

                    <label for="email">Email: </label>
                    <input class="fullWidth" type="email" name="email" id="email" value="<?php if (isset($Email)) {
                                                                                                echo $Email;
                                                                                            } ?>" required>
                    <?php if (isset($emailErr) && $emailErr == true) {
                        echo '<p class="error">Error: Invalid Email Address</p>';
                    } ?>

                    <label for="password">Password: </label>
                    <input class="halfWidth" type="text" name="password" id="password" minlength="8" required autocomplete="new-password">
                    <?php if (isset($passWeakErr) && $passWeakErr == true) {
                        echo '<p class="error">Error: Your password needs to be 8 or more characters</p>';
                    } ?>

                    <label for="isAdmin">Admin User?</label>
                    <input type="checkbox" name="isAdmin" id="isAdmin">

                    <button type="submit">Create Account</button>
                </form>
            </div>
        </main>
    </div>
    <script src="js/app.js"></script>
</body>

</html>