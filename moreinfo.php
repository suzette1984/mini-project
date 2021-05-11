<?php
include('dbConfig.php');

$image_gallery_qty = 0;

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') //has the user submitted the form and edited the order
    {
        //Data from form
        $product_id = $_GET['product_id'];

        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $conn->prepare("SELECT * FROM `product` WHERE product_id= ?"); // building a string with the SQL INSERT you want to run

        $sql->bindValue(1, "$product_id");

        $sql->execute(); //execute the statement

        if ($sql->rowCount() >= 1) { //check if we have results by counting rows

            $row = $sql->fetch();


            $title = $row['product_name'];
            $description = $row['product_description'];
            $price = $row['product_price'];

            $sql = $conn->prepare("SELECT `product_main_image` FROM `product_images` WHERE `product_id`= ?");
            $sql->bindValue(1, "$product_id");
            $sql->execute(); //execute the statement //check if we have results by counting rows
            $row = $sql->fetch();
            $product_main_image = $row['product_main_image'];
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } else {
            echo 'Product not found!';
            header('Location: ../products.php');
        }
    }
} catch (PDOException $e) {
    echo 'There was an error<br>';
    echo $sql . "<br>" . $e->getMessage(); //If we are not successful we will see an error
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $title; ?> - PastelTech</title>
</head>

<body>
    <h1> <?php echo $title; ?></h1>
    <h3> <?php echo $description; ?></h3>
    <h3> <?php echo $price; ?></h3>


    <div id="product-view">
        <div class="product_image">
            <img id="product_main_image" src="uploads/st peters campus.jpg <?php echo $product_main_image; ?>" alt="<?php echo $title; ?>">
        </div>
        <!-- Images used to open the lightbox -->
        <div class="row">

            <?php


            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //Selecting multiple rows from a MySQL database using the PDO::query function.
                $sql = "SELECT `product_image_filename` FROM `product_images` WHERE `product_id`= $product_id";

                //For each result that we return, loop through the result and perform the echo statements.
                //$row is an array with the fields for each record returned from the SELECT
                foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {

                    //List patient info for selected practice
                    echo '<div class="column">';
                    echo '<img src="' . $row['product_image_filename'] . '" onclick="openModal();currentSlide(1)" class="hover-shadow">';
                    echo '</div>';
                    $image_gallery_qty++;
                }
            } catch (PDOException $e) {
                echo $sql . "There was an error" . $e->getMessage(); //If we are not successful we will see an error
            }

            ?>
        </div>

        <!-- The Modal/Lightbox -->
        <div id="myModal" class="modal">
            <span class="close cursor" onclick="closeModal()">&times;</span>
            <div class="modal-content">
                <div class="mySlides">

                    <?php
                    echo '<div class="numbertext">1 / ' . $image_gallery_qty . '</div>';
                    try {
                        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                        // set the PDO error mode to exception
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        //Selecting multiple rows from a MySQL database using the PDO::query function.
                        $sql = "SELECT `product_image_filename` FROM `product_images` WHERE `product_id`= $product_id";

                        //For each result that we return, loop through the result and perform the echo statements.
                        //$row is an array with the fields for each record returned from the SELECT
                        foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {

                            //List product info for selected 
                            echo '<img src="' . $row['product_image_filename'] . '" style="width:100%">';
                        }
                    } catch (PDOException $e) {
                        echo $sql . "There was an error" . $e->getMessage(); //If we are not successful we will see an error
                    }
                    ?>

                </div>

                <!-- Next/previous controls -->
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>

                <!-- Caption text -->
                <div class="caption-container">
                    <p id="caption"></p>
                </div>


                <!-- Thumbnail image controls -->
                <?php
                $gallery_img_number = 0;
                try {
                    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                    // set the PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    //Selecting multiple rows from a MySQL database using the PDO::query function.
                    $sql = "SELECT `product_image_filename` FROM `product_images` WHERE `product_id`= $product_id";

                    //For each result that we return, loop through the result and perform the echo statements.
                    //$row is an array with the fields for each record returned from the SELECT
                    foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {

                        //List product info for selected 
                        echo '
                            <div class="column">
                            <img class="demo" src="' . $row['product_image_filename'] . '" onclick="currentSlide(' . $gallery_img_number . ')">
                </div>';
                    }
                } catch (PDOException $e) {
                    echo $sql . "There was an error" . $e->getMessage(); //If we are not successful we will see an error
                }
                ?>

            </div>

        </div>
    </div>
    <script src="./js/app.js"></script>
</body>

</html>