<?php
class Database {
    private $host = "localhost"; // اسم الخادم
    private $db_name = "bt"; // اسم قاعدة البيانات
    private $username = "root"; // اسم المستخدم لقاعدة البيانات
    private $password = ""; // كلمة المرور لقاعدة البيانات
    public $conn;

    // دالة إنشاء الاتصال بقاعدة البيانات
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "connection failed: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
