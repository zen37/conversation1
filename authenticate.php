<?php
session_start();

echo 'Welcome to page authenticate 2';

// Or pass along the session ID if needed
// Change this to your connection info.

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

echo $DATABASE_HOST;

// Try and connect using the info above.
$conn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, display a user-friendly message
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
} else {
    // Connection successful
    echo "Connected successfully";
}
?>