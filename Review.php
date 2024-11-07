<?php
class Review {
    private $conn;
    private $table_name = "eventreviews";

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    public function addReview($event_id, $user_id, $review) {
        try {
            // إدراج تقييم جديد
            $insertQuery = "INSERT INTO " . $this->table_name . " (event_id, user_id, review) VALUES (?, ?, ?)";
            $insertStmt = $this->conn->prepare($insertQuery);
            $insertStmt->bindParam(1, $event_id, PDO::PARAM_INT);
            $insertStmt->bindParam(2, $user_id, PDO::PARAM_INT);
            $insertStmt->bindParam(3, $review, PDO::PARAM_STR);
            return $insertStmt->execute();
        } catch (PDOException $e) {
            echo "Error in addReview: " . $e->getMessage();
            return false;
        }
    }
    
}
