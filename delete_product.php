<?php
if (isset($_POST['submit'])) {
    // Include the database configuration file
    include_once 'dbConfig.php';
    $statusMsg = "";
    //insert product info to product table here

    //To get the id of the last inserted product use $last_id = $conn->lastInsertId();
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $main_file_image = "";
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "DELETE FROM product (product_name, product_price, product_description) VALUES ('$product_name', '$product_price', '$product_description')"; // building a string with the SQL INSERT you want to run

        // use exec() because no results are returned
        $conn->exec($sql);
        $last_id = $conn->lastInsertId();
        $statusMsg .=  "New product created successfully. "; // If successful we will see this
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
    }


    // File upload configuration
    $targetDir = "uploads/";
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

    // This section handles the main image upload
    if (isset(($_FILES['product_main_image']['name']))) {
        // File upload path
        $fileName = basename($_FILES['files']['name'][$key]);

        $main_file_image = $targetDir . $fileName;

        // Check whether file type is valid by looking at the file extension
        $fileType = pathinfo($main_file_image, PATHINFO_EXTENSION);
        if (in_array($fileType, $allowTypes)) {
            // Upload file to server
            if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $main_file_image)) {

                //insert the filename into the SQL table product_images
            }
        }
    }
    if (!empty(array_filter($_FILES['files']['name']))) {
        //This setion handles all other image uploads
        //Loop through all of the files you selected to upload
        foreach ($_FILES['files']['name'] as $key => $val) {
            // File upload path
            $fileName = basename($_FILES['files']['name'][$key]);

            $targetFilePath = $targetDir . $fileName;

            // Check whether file type is valid by looking at the file extension
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
            if (in_array($fileType, $allowTypes)) {
                // Upload file to server
                if (move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)) {

                    //insert the filename into the SQL table product_images

                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        //we use the last inserted ID from the product table and the target filepath to record where the image will live
                        $sql = "DELETE FROM product_images (product_image_id, product_image_filename, product_id, product_main_image) VALUES (0, '$targetFilePath', '$last_id', '$main_file_image')"; // building a string with the SQL INSERT you want to run

                        // use exec() because no results are returned
                        $conn->exec($sql);
                    } catch (PDOException $e) {
                        echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
                    }
                } else {
                    //for some reason the file may have not uploaded, such as a dropped connection, or incorrect permissions
                    $errorUpload .= "Upload error:" . $_FILES['files']['name'][$key] . ', ';
                    echo $errorUpload;
                }
            } else {
                //Wrong file type
                $errorUploadType .= "Wrong file type" . $_FILES['files']['name'][$key] . ', ';
                echo $errorUploadType;
            }
        }
    } else {
        $statusMsg = 'Please select a file to upload.';
    }
    $statusMsg .= "Product images added. ";

    // Display status message
    echo $statusMsg;
}
