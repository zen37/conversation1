<?php
require_once('include/connect.php');

// Define pagination parameters
$recordsPerPage = 20; // Number of records to display per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number

// Calculate the starting record for the current page
$offset = ($page - 1) * $recordsPerPage;

// Fetch total number of records
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM accounts limit 1000";
$totalRecordsResult = mysqli_query($conn, $totalRecordsQuery);
$totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];

$totalRecords = 1000;

// Calculate total number of pages
$totalPages = ceil($totalRecords / $recordsPerPage);

// Fetch records for the current page
$sql = "SELECT * FROM accounts LIMIT $offset, $recordsPerPage";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home Page</title>
    <link href="style.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
</head>
<body class="loggedin">
<nav class="navtop">
    <div>
        <h1>Website Title</h1>
        <a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
        <a href="login.html"><i class="fas fa-sign-out-alt"></i>Login</a>
    </div>
</nav>
<div class="content">
    <h2>Home Page</h2>
    <p>Welcome back!</p>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
        </tr>
        <?php
        // Display records in the table
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <!-- Pagination links -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= ($page == $i) ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
