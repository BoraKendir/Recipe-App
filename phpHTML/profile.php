<!-- profile.php 
    This file displays the user's profile page.
    It shows the user's recipes and allows the user to remove recipes.
    The user can also log out from this page.
-->
<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="../css/profile.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        window.onload = function() {
            const params = new URLSearchParams(window.location.search);
            if (params.has('status')) {
                const status = params.get('status');
                if (status === 'success') {
                    alert('Recipe removed successfully.');
                } else if (status === 'error') {
                    alert('Error removing recipe.');
                }
            }
        }
    </script>
</head>
<?php
    session_start();
    // Access the username from the session
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
    } else {
        echo "You are not logged in. Redirecting you to the login page...";
        header("refresh:3;url=../index.html");
        exit;
    }
?>
<body>
    <h1><?php echo $_SESSION["username"]?>'s profile</h1>
    <h2>Your Recipes:</h2>
    <?php getRecipes($_SESSION["user_id"]); ?>

    <footer>
        <div class="footer-btn">
            <a class="btn btn-primary" href="contact.html">Contact</a>  
        </div>
        <div class="footer-btn">
            <a class="btn btn-primary" href="about.html">About the App</a>  
        </div>
        <div class="footer-btn">
            <a class="btn btn-primary" href="homepage.php">Homepage</a>  
        </div>
        <div class="footer-btn">
            <form method="get" action="logout.php">
                <input type="submit" value="Logout" class="btn btn-primary" />
            </form>
        </div>
    </footer>
</body>
</html>
<?php
    // Function to get the recipes of the user from the database

    function getRecipes($userId) {
        $servername = "localhost";
        $username = "root";
        $password = "mysql";
        $dbname = "project";

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        //Making sure the connection is successful
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $sql = "SELECT recipe_name FROM recipes WHERE user_id = $userId";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // Output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $limit = 10;
                $offset = ($page - 1) * $limit;

                $sql = "SELECT recipe_id, recipe_name, recipe_url FROM recipes WHERE user_id = $userId LIMIT $limit OFFSET $offset";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<div class='profile-recipes'>";
                    // Output data of each row, pagination logic
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='profile-recipe-card'>";
                        echo "<a href='" . $row["recipe_url"] . "' target='_blank'>" . $row["recipe_name"] . "</a>";
                        echo "<form method='post' action='remove_recipe.php'>";
                        echo "<input type='hidden' name='recipe_id' value='" . $row["recipe_id"] . "' />";
                        echo "<button type='submit' class='btn btn-danger'>Remove</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='recipe-warning'>";
                                echo "<h1>No Recipes Found</h1>";
                                echo "<h1>You can find and add recipes through \"Find a recipe\" button from the homepage</h1>";
                    echo "</div>";
                } 

                $total_pages = ceil(mysqli_num_rows(mysqli_query($conn, "SELECT recipe_name FROM recipes WHERE user_id = $userId")) / $limit);
                echo '<div class="buttons">';
                if ($page < $total_pages) {
                    echo "<a href='profile.php?page=" . ($page + 1) . "' class='page-link' id='next'>Next</a>";
                }

                if ($page > 1) {
                    echo "<a href='profile.php?page=" . ($page - 1) . "' class='page-link' id='prev'>Previous</a>";
                }
                echo '</div>';
                echo "</div>";
            }
        } else {
            echo "<div class='recipe-warning'>";
                        echo "<h1>No Recipes Found</h1>";
                        echo "<h1>You can find and add recipes through \"Find a recipe\" button from the homepage</h1>";
            echo "</div>";
        } 

        mysqli_close($conn);
    }
    ?>
