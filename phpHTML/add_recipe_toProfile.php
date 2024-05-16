<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = $_POST['recipe_url'];
    $name = $_POST['recipe_name'];
    $userId = $_POST['user_id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "project";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO recipes (user_id, recipe_name, recipe_url) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iss", $userId, $name, $url);
        if (mysqli_stmt_execute($stmt)) {
            echo "Recipe added successfully!";
        } else {
            echo "Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo "Invalid request method.";
}

