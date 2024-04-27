<!-- homepage.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <link href="./css/homepage.css" rel="stylesheet"/>
    <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
</head>
<?php
    session_start();
    // Access the username from the session
    if (isset($_SESSION["username"])) {
        $username = $_SESSION["username"];
    } else {
        echo "User not logged in.";
    }
?>
<body>
    <h1>Welcome <?php echo $_SESSION["username"]?></h1>
    
    <footer>
        <div class="profile-btn">
            <form method="get" action="profile.php">
                <input type="submit" value="Profile" />
            </form>
        </div>
        <div class="logout-btn">
            <form method="get" action="logout.php">
                <input type="submit" value="Logout" />
            </form>
        </div>
    </footer>
</body>
</html>