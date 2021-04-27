<?php
session_start();
?>
<!DOCTYPE html>
<html>

<body>

    <?php
    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy();

    echo 'You have been logged out - You will now be redirected to the login page.';
    header("Refresh: 2; URL = login.php");
    ?>

</body>

</html>