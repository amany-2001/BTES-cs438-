<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">

    
</head>
<body>
    <h1>Welcome Admin</h1>
    <a href="logout.php">Logout</a>
    <hr>
    
    <h2>Select Action</h2>
    <a href="manage_users.php">Manage Users</a><br>
    <a href="manage_events.php">Manage Events</a>
</body>
</html>
