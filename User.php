<?php
include_once 'database.php';
require_once 'Seat.php';
require_once 'Ticket.php';
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

    public function bookTicket($user_id, $seat_number, $event_name) {
       try{
            // جلب event_id من اسم الحدث
            $eventQuery = "SELECT eventid FROM events WHERE eventname = ?";
            $eventStmt = $this->conn->prepare($eventQuery);
            $eventStmt->bindParam(1, $event_name);
            $eventStmt->execute();
            $eventData = $eventStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($eventData) {
                $event_id = $eventData['eventid'];
        
                // استخدام كلاس Seat للتحقق من توفر المقعد
                $seat = new Seat($this->conn);
                $seat_id = $seat->getSeatIdByNumberAndEvent($seat_number, $event_id); // دالة للحصول على seat_id بناءً على رقم المقعد و event_id
        
                if ($seat_id && $seat->isSeatAvailable($seat_id)) {
                    // جلب السعر من الكلاس Ticket
                    $ticket = new Ticket($this->conn);
                    $price = $ticket->getPrice($event_id, $seat_id); // استدعاء الدالة لإرجاع السعر
        
                    // إدراج بيانات الحجز في tickets
                    $query = "UPDATE tickets SET status = 'booked' , user_id =? WHERE seat_id =? AND event_id =?";
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
        } catch(PDOException $e){
            echo "error in bookticket :". $e->getMessage();
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
                $updateQuery = "UPDATE tickets SET status = 'refunded' WHERE ticketid = ?";
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
        try{
            $query = "UPDATE users SET review = ? WHERE userid = ? AND EXISTS (SELECT * FROM events WHERE eventid = ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $review);
            $stmt->bindParam(2, $user_id);
            $stmt->bindParam(3, $event_id);
            return $stmt->execute();
        } catch(PDOException $e){
            echo "error in rateevent :". $e->getMessage();
            return false;
        }
    }
   
}
?>
