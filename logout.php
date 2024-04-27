<!--- 
This file was provided by the teacher/assistant 
--->
<?php
session_start();

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the login page
header("Location: firstpage.html");
exit();
?>