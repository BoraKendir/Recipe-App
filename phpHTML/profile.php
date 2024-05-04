<!-- profile.php 
    This file displays the user's profile page.
    

-->
<!DOCTYPE html>
<html>
<head>
    <title>Your Profile</title>
    <link rel="stylesheet" href="../css/profile.css" />
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">

</head>
<?php
    session_start();
    // Access the username from the session
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
    } else {
        echo "You are not logged in. Redirecting you to the login page...";
        header("refresh:3;url=../firstpage.html");
        exit;
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