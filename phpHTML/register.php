<!DOCTYPE html>
<html>
<body>
    <h1>Registration Result</h1>
<?php

    // This script is used to register a user to the database
    // It is called from the index page
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "mysql";
    $dbname = "project";

    $conn = mysqli_connect($servername, $dbusername, $dbpassword, $dbname);
    
    // Making sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // User inputs as forums
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Query to check if the username is already in the database
    $checkQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "Username already exists";
        echo '<script>
        setTimeout(function() {
            window.location.href = "../index.html";
        }, 3000); // 3000 milliseconds = 3 seconds
        </script>';
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        exit();
    }

    // Query to insert the user to the database, their id is auto-incremented
    $insertQuery = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $insertQuery);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);

    if (mysqli_stmt_execute($stmt)) {
        echo "Registration successful! Refreshing...";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        echo '<script>
        setTimeout(function() {
            window.location.href = "../index.html";
        }, 3000); // 3000 milliseconds = 3 seconds
        </script>';
        exit();
    } else {
        echo "Something went wrong!";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
?>
</body>
</html>
