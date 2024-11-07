<?php
include_once 'database.php';
require_once 'Seat.php';
require_once 'Ticket.php';
require_once 'Review.php';
$database = new Database();
$db = $database->getConnection();

class User {
    private $conn;
    private $table_name = "users";

    public $userID;
    public $userName;
    public $userAge;
    public $email;
    public $point;
    public $accountNumber;
    public $password;
    public $phone;
    public $review;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function bookTicket($user_id, $password, $seat_id, $event_id) {
        try {
            // التحقق من id,password
            $userQuery = "SELECT * FROM users WHERE userid = ? AND password = ?";
            $userStmt = $this->conn->prepare($userQuery);
            $userStmt->bindParam(1, $user_id);
            $userStmt->bindParam(2, $password); 
            $userStmt->execute();
            $userData = $userStmt->fetch(PDO::FETCH_ASSOC);
    
            if ($userData) {
                // التحقق من توفر المقعد
                $seat = new Seat($this->conn);
    
                if ($seat->isSeatAvailable($seat_id)) {
                    // جلب السعر من الكلاس Ticket
                    $ticket = new Ticket($this->conn);
                    $price = $ticket->getPrice($event_id, $seat_id); // استدعاء الدالة لإرجاع السعر
    
                    // إدراج بيانات الحجز في tickets
                    $query = "UPDATE tickets SET status = 'booked', user_id = ? WHERE seat_id = ? AND event_id = ?";
                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(1, $user_id);
                    $stmt->bindParam(2, $seat_id);
                    $stmt->bindParam(3, $event_id);
    
                    if ($stmt->execute()) {
                        // تحديث حالة توفر المقعد
                        $seat->updateSeatAvailability($seat_id, false); // دالة لتحديث حالة المقعد في كلاس Seat
                        return true;
                    }
                }
            }
            return false;
        } catch (PDOException $e) {
            echo "error in bookTicket: " . $e->getMessage();
            return false;
        }
    }


    public function refundTicket($ticket_id) {
        try{
            // التحقق من حالة التذكرة
            $query = "SELECT status, seat_id FROM tickets WHERE ticketid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $ticket_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['status'] === 'booked') {
                // إرجاع التذكرة وتحديث الحالة
                $updateQuery = "UPDATE tickets SET status = 'refunded',user_id = NULL WHERE ticketid = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(1, $ticket_id);
                if ($updateStmt->execute()) {
                    // تحديث حالة المقعد
                    $seat_id = $row['seat_id'];
                    $updateSeatQuery = "UPDATE seats SET isavailable = TRUE WHERE seatid = ?";
                    $updateSeatStmt = $this->conn->prepare($updateSeatQuery);
                    $updateSeatStmt->bindParam(1, $seat_id);
                    $updateSeatStmt->execute();
                    return true;
                }
            }
            return false;
        } catch(PDOException $e){
            echo "error in refundticket :". $e->getMessage();
            return false;
        }
    }
    public function rateEvent($event_id, $user_id, $review) {
        try {
            // استدعاء كلاس Review لإضافة التقييم
            $reviewObj = new Review($this->conn);
            return $reviewObj->addReview($event_id, $user_id, $review);
        } catch (PDOException $e) {
            echo "Error in rateEvent: " . $e->getMessage();
            return false;
        }
    }
   
}
?>

