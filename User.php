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
    public $review;

    public function __construct($db) {
        $this->conn = $db;
    }
   // داخل كلاس User.php أو Ticket.php
public function bookTicket($user_id, $seat_id, $eventid) {
    try {
        foreach ($seat_id as $seat_id) {
            // التحقق من توفر المقعد
            $seatQuery = "SELECT isavailable FROM seats WHERE seat_id = ? AND eventid = ?";
            $seatStmt = $this->conn->prepare($seatQuery);
            $seatStmt->bindParam(1, $seat_id);
            $seatStmt->bindParam(2, $eventid);
            $seatStmt->execute();
            $seat = $seatStmt->fetch(PDO::FETCH_ASSOC);

            if ($seat && $seat['isavailable']) {
                // حجز التذكرة
                $query = "INSERT INTO tickets (user_id, seat_id, eventid, status) 
                          VALUES (?, ?, ?, 'booked')";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(1, $user_id);
                $stmt->bindParam(2, $seat_id);
                $stmt->bindParam(3, $eventid);
                $stmt->execute();

                // تحديث حالة المقعد إلى "غير متاح"
                $updateSeatQuery = "UPDATE seats SET isavailable = 0 WHERE seat_id = ? AND eventid = ?";
                $updateSeatStmt = $this->conn->prepare($updateSeatQuery);
                $updateSeatStmt->bindParam(1, $seat_id);
                $updateSeatStmt->bindParam(2, $eventid);
                $updateSeatStmt->execute();
            } else {
                throw new Exception("Seat $seat_id is not available.");
            }
        }
        return true;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
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
