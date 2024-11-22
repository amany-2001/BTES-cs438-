<?php
session_start();
include_once 'database.php';

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // تحقق من البريد الإلكتروني وكلمة المرور
    $query = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password); // كلمة المرور غير مشفرة هنا
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // تسجيل الدخول ناجح
        $_SESSION['user'] = $stmt->fetch(PDO::FETCH_ASSOC);
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Login to Event Management System</h1>
    <form action="login.php" method="POST">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <?php
    if (isset($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>
</body>
</html>
