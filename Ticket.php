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

    public function getTicketDetails($ticket_id) {
        try{
            $query = "SELECT * FROM " . $this->table_name . " WHERE ticketid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $ticket_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e){
            echo "error in getTicketdetails :". $e->getMessage();
            return false;
        }
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
    // public function refund() {
    //     if ($this->status !== "Purchased") {
    //         echo "Ticket cannot be refunded.";
    //         return false;
    //     }
    //     $query = "UPDATE ticket SET status='Refunded' WHERE ticketID=?";
    //     return $this->updateTicketStatus($query, $this->ticketID);
    // }

    // public function sell() {
    //     if ($this->status !== "Available") {
    //         echo "Ticket cannot be sold.";
    //         return false;
    //     }
    //     $query = "UPDATE ticket SET status='Sold' WHERE ticketID=?";
    //     return $this->updateTicketStatus($query, $this->ticketID);
    // }

    // public function purchase() {
    //     if ($this->status !== "Available") {
    //         echo "Ticket cannot be purchased.";
    //         return false;
    //     }
    //     $query = "UPDATE ticket SET status='Purchased' WHERE ticketID=?";
    //     return $this->updateTicketStatus($query, $this->ticketID);
    // }

    // private function updateTicketStatus($query, $ticketID) {
    //     $url = "mysql:host=localhost;dbname=btes";
    //     $user = "root";
    //     $password = "";
    //     try {
    //         $connection = new PDO($url, $user, $password);
    //         $stmt = $connection->prepare($query);
    //         $stmt->execute([$ticketID]);
    //         $connection = null;
    //         echo "Ticket status updated successfully!";
    //         return true;
    //     } catch (Exception $e) {
    //         echo "Error updating ticket status: " . $e->getMessage();
    //         return false;
    //     }
    // }
}
?>
