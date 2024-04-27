<!-- profile.php 
    This file displays the user's profile page.
    
    The user can see their tweets, followers, and followings,
    also their own tweets.
    
    User can tweet things, go to their homepage, or logout.
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
    <h1>Welcome, <?php echo $_SESSION["name"]?>!</h1>
    <footer>
        <div class="profile-btn">
            <form method="get" action="homepage.php">
                <input type="submit" value="Homepage" />
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