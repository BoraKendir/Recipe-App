<?php
// This script is used to remove a recipe from the database
// It is called from the profile page
session_start();

if (isset($_SESSION["user_id"]) && isset($_POST["recipe_id"])) {
    $userId = $_SESSION["user_id"];
    $recipeId = $_POST["recipe_id"];

    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "project";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Making sure the connection is successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete the recipe
    $sql = "DELETE FROM recipes WHERE recipe_id = $recipeId AND user_id = $userId";
    if (mysqli_query($conn, $sql)) {
        $status = "success";
    } else {
        $status = "error";
    }

    mysqli_close($conn);
    header("Location: profile.php?status=" . $status);
    exit;
} else {
    echo "Invalid request.";
    header("refresh:3;url=profile.php");
    exit;
}
?>
