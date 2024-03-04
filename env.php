<?php 
// Get the keys of environment variables and sort them alphabetically
$keys = array_keys($_ENV);
sort($keys);

// Iterate through the sorted keys and display corresponding values
foreach ($keys as $key) {
    echo "$key: {$_ENV[$key]}<br>";
}
?>