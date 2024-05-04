<!-- homepage.php -->
<!DOCTYPE html>
<html>
    <head>
        <title>Homepage</title>
        <link href="../css/homepage.css" rel="stylesheet"/>
        <link href="https://fonts.cdnfonts.com/css/chirp-2" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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

        <div class="welcome-title">
            <h1 style="background-color: #F5F1E7;">Welcome <?php echo $_SESSION["username"]; ?></h1>
        </div>
        <div class="big-buttons">
            <div class="recipe-btn">
                <a class="btn btn-primary" href="recipe.php">Find a recipe</a>  
            </div>
            
            <div class="profile-btn">
                <form method="get" action="profile.php">
                    <input type="submit" value="Profile" class="btn btn-primary" />
                </form>
            </div>
        </div>
        <footer>
            <div class="footer-btn">
                <a class="btn btn-primary" href="contact.html">Contact</a>  
            </div>
            <div class="footer-btn">
                <a class="btn btn-primary" href="about.html">About the App</a>  
            </div>
            <div class="footer-btn">
                <form method="get" action="logout.php">
                    <input type="submit" value="Logout" class="btn btn-primary" />
                </form>
            </div>
        </footer>
    </body>
</html>