<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

// التحقق من وجود معرف المستخدم في الرابط
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // جلب تفاصيل المستخدم
    $query = "SELECT * FROM users WHERE id = :user_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
        // تحديث بيانات المستخدم
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $update_query = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :user_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':user_id', $user_id);

        if ($update_stmt->execute()) {
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Failed to update user.";
        }
    }
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <h1>Edit User</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <hr>

    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>

    <form method="POST" action="edit_user.php?id=<?= $user_id ?>">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label for="role">Role:</label><br>
        <select id="role" name="role">
            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select><br><br>

        <button type="submit" name="update_user">Update User</button>
    </form>
</body>
</html>
