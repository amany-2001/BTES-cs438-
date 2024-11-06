<?php
include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

class Event {
    private $conn;
    private $table_name = "events";
    
    private $eventId;
    private $eventName;
    private $category;
    private $date;
    private $location;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllEvents() {
        try{
            $query = "SELECT eventname ,category ,eventid  FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e){
            echo "error in getAllEvents :". $e->getMessage();
            return false;
        }
    }
    public function displayDetails($eventid) {
        $query = "SELECT * FROM events WHERE eventid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $eventid, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt;
    }

}
?>
