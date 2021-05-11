<?php
/* Attempt MySQL server connection. */
include "dbConfig.php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Attempt search query execution
try {
    if (isset($_REQUEST["term"])) {
        // create prepared statement
        $sql = "SELECT * FROM product WHERE product_name LIKE :term";
        $stmt = $conn->prepare($sql);
        $term = '%' . $_REQUEST["term"] . '%';
        // bind parameters to statement
        $stmt->bindParam(":term", $term);
        // execute the prepared statement
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch()) {
                echo '<a href="product_details.php?id=' . $row["product_id"] . '">' . $row["product_name"] . "</a>";
            }
        } else {
            echo "<p>No products found</p>";
        }
    }
} catch (PDOException $e) {
    die("ERROR: Search could not be performed $sql. " . $e->getMessage());
}
