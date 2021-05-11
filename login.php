<?php
session_start(); // important function to allow session variables  

if (empty($_SESSION["loggedIn"])) {

    $_SESSION["loggedIn"] = 'false';
} elseif ($_SESSION["loggedIn"] == "true") {

    header("Location: ./my-account.php"); //send them to the form to login

}

// init error message placeholder
$errorMsg;

require_once 'dbConfig.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') //has the user submitted the form
    {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // save the email the user submitted from $_POST
        $Email = $_POST['email'];
        // save the password the user submitted from $_POST
        $Password = $_POST['password'];


        //preparing an sql statement to search email and password for whatever the user has typed and be equal to an admin user
        $sql = $conn->prepare("SELECT * FROM users WHERE user_email=? AND user_password=?");
        $sql->bindValue(1, $Email); //we bind this variable to the first ? in the sql statement
        $sql->bindValue(2, $Password); //we bind this value to the second ? in the sql statement
        $sql->execute(); //execute the statement

        if ($sql->rowCount()) { //check if we have results by counting rows

            $row = $sql->fetch();

            //set session login variable to true here
            $_SESSION["loggedIn"] = 'true';
            $_SESSION["FName"] = $row['user_firstname'];
            $_SESSION["LName"] = $row['user_lastname'];
            $_SESSION["UserID"] = $row['user_id'];
            $_SESSION["Role"] = $row['user_type'];

            if ($_SESSION['Role'] == 'admin') {
                //redirect admin users to the owner dashboard 
                header("Location: ./owner.php");
            } else {

                //redirect users to their account 
                header("Location: ./my-account.php");
            }
        } else {
            //message to display if we did not match a user
            $_SESSION["loggedIn"] = 'false';
            $errorMsg = "No users matching the credentials you entered!";
        }
    } else {
        //message incase someone lands on this page without first accessing the login form
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

    <title>Login - PastelTech</title>
</head>

<body>

    <div id="content">

        <!-- This is the Page Header with related links -->
        <div class="welcomeDiv">
            <header class="page-title">
                <h1>Login to your account</h1>
            </header>

            <div class="pageOptions flex">
                <a href="./register.php">Click here to create an account.</a>
            </div>
        </div>

        <main>
            <?php if (isset($errorMsg)) {
                echo
                '<div id="errorMessage" class="error"> 
            ' . $errorMsg . ' 
            </div> 
            <button onclick="goBack()">Go Back</button>';
            } ?>
            <div class="login-form">
                <form action="./login.php" method="post">
                    <label for="email">Email: </label>
                    <input type="email" name="email" id="email" value="<?php if (isset($Email)) {
                                                                            echo $Email;
                                                                        } ?>">

                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" autocomplete="current-password">

                    <button type="submit">Login</button>
                </form>
                </form>
            </div>
        </main>
    </div>

    </div>
    <script src="js/app.js"></script>
</body>

</html>