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
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

// جلب بيانات المستخدم
$query = "SELECT * FROM users WHERE userid = :userid";
$stmt = $db->prepare($query);
$stmt->bindParam(':userid', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// إذا لم يتم العثور على المستخدم
if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// تحديث بيانات المستخدم
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // تشفير كلمة المرور
    $role = $_POST['role'];

    try {
        $update_query = "UPDATE users SET username = :username, email = :email, password = :password, role = :role WHERE userid = :userid";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':email', $email);
        $update_stmt->bindParam(':password', $password);
        $update_stmt->bindParam(':role', $role);
        $update_stmt->bindParam(':userid', $user_id);
        $update_stmt->execute();

        $success_message = "User updated successfully!";
    } catch (PDOException $e) {
        $error_message = "Error updating user: " . $e->getMessage();
    }
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
    <a href="manage_users.php">Back to Users</a>
    <hr>

    <!-- نموذج تعديل بيانات المستخدم -->
    <form method="POST" action="edit_user.php?id=<?= htmlspecialchars($user_id) ?>">
        <label for="username">Name:</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Leave blank to keep current password"><br><br>

        <label for="role">Role:</label><br>
        <select id="role" name="role" required>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
        </select><br><br>

        <button type="submit" name="update_user">Update User</button>
    </form>

    <?php if (isset($success_message)) { echo "<p style='color:green;'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
</body>
</html>
