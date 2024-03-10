<?php
session_start();

require_once('include/errors_display.php');

// Define the base path for the script
define('BASE_PATH', dirname(__FILE__));
// Define the path to the .env file
define('ENV_FILE_PATH', BASE_PATH . '/.env');

// Check if the .env file exists
if (file_exists(ENV_FILE_PATH)) {
    echo 'Running on localhost<br>';
    readEnvLocal();
} else {
    echo 'Running on a cloud server<br>';
}

//$CERT_PATH      = getenv("CERT_PATH");

$DATABASE_HOST  = getenv("APPSETTING_DB_HOST");
$DATABASE_NAME  = getenv("DB_NAME");
$DATABASE_USER  = getenv("DB_USER");
$DATABASE_PASS  = getenv("DB_PASS");

$conn = mysqli_init();

// Set SSL options
// mysqli_ssl_set($conn, NULL, $CERT_PATH, NULL, NULL, NULL);

mysqli_real_connect($conn, $DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

// Check if there's an error with the connection
if (mysqli_connect_errno()) {
    // If there is an error with the connection, display a user-friendly message
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
} else {
    // Connection successful
    echo "Connected successfully<br>";
}

// Function to read the contents of the .env file and set environment variables
function readEnvLocal()
{
    global $envFileContent;

    // Read the contents of the .env file
    $envFileContent = file_get_contents(ENV_FILE_PATH);

    // Parse the contents of the .env file
    $envVariables = explode("\n", $envFileContent);

    // Loop through each line and set environment variables
    foreach ($envVariables as $envVariable) {
        // Skip empty lines and lines starting with #
        if (!empty($envVariable) && strpos($envVariable, '#') !== 0) {
            // Split the line into key and value
            list($key, $value) = array_map('trim', explode('=', $envVariable, 2));

            // Set the environment variable
            putenv("$key=$value");
        }
    }
}
?>