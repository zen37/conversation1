<?php
define('ENV_FILE_PATH', '.env');

ini_set('display_errors', TRUE);


session_start();

echo 'Welcome to page authenticate 9<br>';


if (file_exists(ENV_FILE_PATH)) {
    echo 'Running locally<br>';
	readEnvLocal();
} else {
    echo 'Running on a cloud server';
}

$CERT_PATH 		= getenv("CERT_PATH");

$DATABASE_HOST 	= getenv("APPSETTING_DB_HOST");
$DATABASE_NAME	= getenv("DB_NAME");
$DATABASE_USER 	= getenv("DB_USER");
$DATABASE_PASS 	= getenv("DB_PASS");


// Initialize MySQLi connection
$conn = mysqli_init();

// Set SSL options
mysqli_ssl_set($conn, NULL, NULL, $CERT_PATH, NULL, NULL);

// Connect to MySQL database
mysqli_real_connect($conn, $DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    // If there is an error with the connection, display a user-friendly message
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
} else {
    // Connection successful
    echo "Connected successfully";
}

mysqli_close($conn);

function readEnvLocal() 
{
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
			#echo $key, $value ;
		}
	}
	
}
?>