<?php
session_start(); // important function to allow session variables  

if (empty($_SESSION["loggedIn"])) {

    $_SESSION["loggedIn"] = 'false';
} elseif ($_SESSION["loggedIn"] == "true") {

    header("Location: ./my-account.php"); //send them to their account

}

// init error message placeholder
$errorMsg;
$fNameErr;
$lNameErr;
$emailErr;
$passWeakErr;
$passMatchErr;

require_once 'dbconfig.php'; // import the DB login credz

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //has the user submitted the form
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
        // save the password the user submitted from $_POST
        $Password2 = $_POST['confirmPassword'];

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
        } elseif ($Password !== $Password2) {
            $passMatchErr = true;
        } else {
            $passWeakErr = $passMatchErr = false;
        }


        // Check for Errors
        if ($fNameErr || $lNameErr || $emailErr || $passWeakErr || $passMatchErr) {
            // There are errors with the entered values - Return to form and display errors
        } else {

            //preparing an sql statement to search email and password for whatever the user has typed and be equal to an admin user
            $sql = $conn->prepare("INSERT INTO `users` (`user_firstname`, `user_lastname`, `user_email`, `user_password`) VALUES (?,?,?,?);");
            $sql->bindValue(1, $Fname); //First Name
            $sql->bindValue(2, $Lname); //Last Name
            $sql->bindValue(3, $Email); //Email Address
            $sql->bindValue(4, $Password); //Validated Password
            $sql->execute(); //execute the statement

            $_SESSION["userID"] = $conn->lastInsertId(); // Get User ID for the user that was just registered and added to DB

            $sql = $conn->prepare("SELECT * FROM users WHERE user_email=? AND user_id=?");
            $sql->bindValue(1, $Email); //we bind this variable to the first ? in the sql statement
            $sql->bindValue(2, $_SESSION["user_id"]); //we bind this value to the second ? in the sql statement
            $sql->execute(); //execute the statement

            if ($sql->rowCount()) { //check if we have results by counting rows

                $row = $sql->fetch();

                //set session variables here
                $_SESSION["loggedIn"] = 'true';
                $_SESSION["FName"] = $row['user_firstname'];
                $_SESSION["LName"] = $row['user_lastname'];
                $_SESSION["UserID"] = $row['user_id'];
                $_SESSION["Role"] = $row['user_type'];

                //redirect users to their account 
                header("Location: ./my-account.php");
            } else {
                //message to display if we did not match a user
                $_SESSION["loggedIn"] = 'false';
                $errorMsg = "There was an error registering your account.";
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

    <title>Register - PastelTech</title>
</head>

<body>
    <?php include_once('./includes/navbar.php') ?>
    <div id="content">

        <!-- This is the Page Header with related links -->
        <div class="welcomeDiv">
            <header class="page-title">
                <h1>Create your account below</h1>
            </header>

            <div class="pageOptions flex">
                <!-- <button onclick="newOrder()">+ New Order</button> -->
            </div>
        </div>

        <main>
            <p>Please fill in the form below to create an with PastelTech.</p>
            <?php if (isset($errorMsg)) {
                echo
                '<div id="errorMessage" class="error"> 
            ' . $errorMsg . ' 
            </div> 
            <button onclick="goBack()">Go Back</button>';
            } ?>
            <div class="register-form">
                <form action="./register.php" method="post">
                    <label for="fName">First Name: </label>
                    <input class="halfWidth" type="text" name="fName" id="fName" value="<?php if (isset($Fname)) {
                                                                                            echo $Fname;
                                                                                        } ?>" required>
                    <?php if (isset($fNameErr) && $fNameErr == true) {
                        echo '<p class="error">Error: Your First Name is required, valid characters are letters, dashes, apostrophes and spaces</p>';
                    } ?>

                    <label for="lName">Last Name: </label>
                    <input class="halfWidth" type="text" name="lName" id="lName" value="<?php if (isset($Lname)) {
                                                                                            echo $Lname;
                                                                                        } ?>" required>
                    <?php if (isset($lNameErr) && $lNameErr == true) {
                        echo '<p class="error">Error: Your Surname is required, valid characters are letters, dashes, apostrophes and spaces</p>';
                    } ?>

                    <label for="email">Email: </label>
                    <input class="fullWidth" type="email" name="email" id="email" value="<?php if (isset($Email)) {
                                                                                                echo $Email;
                                                                                            } ?>" required>
                    <?php if (isset($emailErr) && $emailErr == true) {
                        echo '<p class="error">Error: Invalid Email Address</p>';
                    } ?>

                    <label for="password">Password: </label>
                    <input class="halfWidth" type="password" name="password" id="password" minlength="8" required autocomplete="new-password">
                    <?php if (isset($passWeakErr) && $passWeakErr == true) {
                        echo '<p class="error">Error: Your password needs to be 8 or more characters</p>';
                    } ?>

                    <label for="confirmPassword">Repeat Password: </label>
                    <input class="halfWidth" type="password" name="confirmPassword" id="confirmPassword" minlength="8" required autocomplete="new-password">
                    <?php if (isset($passMatchErr) && $passMatchErr == true) {
                        echo '<p class="error">Error: Your passwords did <strong>not</strong> match</p>';
                    } ?>

                    <button type="submit">Create Account</button>
                </form>
            </div>
        </main>
    </div>

    </div>
    <script src="js/app.js"></script>
</body>

</html>