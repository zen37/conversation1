<?php
define('ENV_FILE_PATH', '.env');

ini_set('display_errors', TRUE);


session_start();


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
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// Connect to MySQL database
mysqli_real_connect($conn, $DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if (mysqli_connect_errno()) {
    // If there is an error with the connection, display a user-friendly message
    die('Failed to connect to MySQL: ' . mysqli_connect_error());
} else {
    // Connection successful
    echo "Connected successfully<br>";
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

	if ($stmt->num_rows > 0) {
		$stmt->bind_result($id, $password);
		$stmt->fetch();
		// Account exists, now we verify the password.
		// Note: remember to use password_hash in your registration file to store the hashed passwords.
		if (password_verify($_POST['password'], $password)) {
			// Verification success! User has logged-in!
			// Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
			session_regenerate_id();
			$_SESSION['loggedin'] = TRUE;
			$_SESSION['name'] = $_POST['username'];
			$_SESSION['id'] = $id;
			echo 'Welcome back, ' . htmlspecialchars($_SESSION['name'], ENT_QUOTES) . '!';
		} else {
			// Incorrect password
			echo 'Incorrect username and/or password!';
		}
	} else {
		// Incorrect username
		echo 'Incorrect username and/or password!';
	}

	$stmt->close();
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