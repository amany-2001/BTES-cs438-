<?php
include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

class Ticket {
    private $conn;
    private $table_name = "tickets";

    public $ticketID;
    public $price;
    public $status;
    public $user_id;
    public $seat_id;
    public $event_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getPrice($event_id, $seat_id) {
        try{
            $query = "SELECT price FROM tickets WHERE event_id = ? AND seat_id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $event_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $seat_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['price'] : null;
        } catch(PDOException $e){
            echo "error in getprice :". $e->getMessage();
            return false;
        }
    }
}
?>
