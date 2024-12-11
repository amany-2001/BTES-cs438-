<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

// جلب المستخدمين من قاعدة البيانات
$query = "SELECT * FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// حذف مستخدم
if (isset($_GET['delete_user_id'])) {
    $delete_user_id = $_GET['delete_user_id'];

    try {
        // حذف التقييمات المرتبطة أولاً
        $delete_reviews_query = "DELETE FROM eventreviews WHERE userid = :userid";
        $delete_reviews_stmt = $db->prepare($delete_reviews_query);
        $delete_reviews_stmt->bindParam(':userid', $delete_user_id);
        $delete_reviews_stmt->execute();

        // حذف المستخدم بعد حذف التقييمات
        $delete_query = "DELETE FROM users WHERE userid = :userid";
        $delete_stmt = $db->prepare($delete_query);
        $delete_stmt->bindParam(':userid', $delete_user_id);
        $delete_stmt->execute();

        header("Location: manage_users.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Manage Users</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <hr>

    <h2>Users List</h2>
    

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>password</th>

            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>

            

        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['userid']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['password']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                <a href="edit_user.php?id=<?= $user['userid'] ?>">Edit</a>
                <a href="manage_users.php?delete_user_id=<?= $user['userid'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>

                    
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
