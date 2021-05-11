<!-- This is the Page Header with related links -->
<div class="welcomeDiv">
    <header class="page-title">
        <h1>Welcome to your account, <?php echo $_SESSION["FName"] ?>! </h1>
    </header>


</div>

<main>
    <div class="results" id="results">
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Total</th>
                <th>Order Date</th>


            </tr>
            <?php require_once 'dbConfig.php';

            try {
                $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password); //building a new connection object
                // set the PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $UserID = $_SESSION["UserID"];

                //preparing an sql statement to search email and password for whatever the user has typed and be equal to an admin user
                $sql = "SELECT * FROM orders WHERE user_id = $UserID";

                //$sql->bindValue(1, $_SESSION["UserID"]); //Bind the customer ID

                foreach ($conn->query($sql, PDO::FETCH_ASSOC) as $row) {
                    echo "<tr>";
                    echo "<td>" . $row['order_id'] . "</td>";
                    echo "<td> £" . number_format($row['order_total'], 2) . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo $sql . "<br>" . $e->getMessage(); //Throw an error if this fails
            } ?>
        </table>
    </div>
</main>