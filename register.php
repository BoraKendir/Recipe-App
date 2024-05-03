<!--- register.php
    This file registers a user to the database
    It checks if the username is already in the database
    If it is not, it registers the user
--->
<!DOCTYPE html>
<html>
<body>
    <h1>Registration Result</h1>
<?php
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "mysql";
    $dbname = "project";

    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
    
    //Making sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    //User inputs as forums
    $username = $_POST["username"];
    $password = $_POST["password"];

    //Query to check if the username is already in the database
    $checkQuery = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn,$checkQuery) or die("11");
    
    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists";
        mysqli_close($conn);
        exit();
    }
    //Query to insert the user to the database, their id is auto incremented
    $insertQuery = "INSERT INTO `users` (`username`, `password`) VALUES ('$username','$password')";

    if (mysqli_query($conn,$insertQuery)) {
        echo "Registiration successful! Refreshing...";
        mysqli_close($conn);
        echo '<script>
        setTimeout(function() {
            window.location.href = "firstpage.html";
        }, 3000); // 3000 milliseconds = 3 seconds
        </script>';
        exit();
    } else {
        echo "Something went wrong!";
    }
    mysqli_close($conn);
?>
</body>
</html>