<?php
session_start();
echo 'Welcome to page #1';
$_SESSION['favcolor'] = 'green';
$_SESSION['animal'] = 'cat';
$_SESSION['time'] = time();
echo '<br /><a href="page2.php">page 2</a>';
// Or pass along the session ID if needed
echo '<br /><a href="page2.php?' . SID . '">page 2</a>';
?>