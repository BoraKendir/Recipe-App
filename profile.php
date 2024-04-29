<!-- profile.php 
    This file displays the user's profile page.
    

-->
<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="css/profile.css" />
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">

</head>

<?php
    session_start();
    //Getting and setting $_SESSION variables
    if (isset($_SESSION["username"])) {
        $SessionUsername = $_SESSION["username"];
    } else {
        echo "User not logged in.";
    }
?>
<body>
    <h1><?php echo $_SESSION["username"]?>'s profile</h1>
    <h2>Your Recipes:</h2>
    <?php getRecipes($_SESSION["user_id"]); ?>

</body>
</html>
<?php
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
                echo "<div>" . $row["recipe_name"] . "</div>";
            }
        } else {
            echo "0 recipes found.";
        } 

        mysqli_close($conn);
    }
    ?>