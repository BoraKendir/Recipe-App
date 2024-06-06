<!--- 
Logout the user from the session and redirect them to the login page.
--->
<?php

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header("Location: ../index.html");
exit();
?>