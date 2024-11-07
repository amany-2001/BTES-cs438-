<?php
include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

class Seat {
    private $conn;
    private $table_name = "seats";

    public $seatid;
    public $event_id;
    public $seatNumber;
    public $isAvailable;

    public function __construct($db) {
        $this->conn = $db;
    }

    // دالة لجلب جميع المقاعد الخاصة بحدث معين
    public function getSeatsByEvent($event_id) {
        try {
            $query = "SELECT * FROM seats WHERE eventid = ? AND isavailable = TRUE";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $event_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error in getSeatsByEvent: " . $e->getMessage();
            return [];
        }
    }

    public function getSeatIdByNumberAndEvent($seat_number, $event_id) {
        try{
            $query = "SELECT seatid FROM seats WHERE seatnumber = ? AND eventid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $seat_number, PDO::PARAM_INT);
            $stmt->bindParam(2, $event_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['seatid'] : null;
        } catch(PDOException $e){
            echo "error in getSeatIdByNumberAndEvent :". $e->getMessage();
            return false;
        }
    }

    // دالة لاختيار مقعد
    public function chooseSeat($seat_id, $user_id, $event_id) {
        try {
            // استدعاء دالة bookTicket لحجز المقعد وتحديث الحالة
            $ticketObj = new Ticket($this->conn);
            return $ticketObj->bookTicket($user_id, $seat_id, $event_id);
        } catch (PDOException $e) {
            echo "Error in chooseSeat: " . $e->getMessage();
            return false;
        }
    }
    

    // دالة للتحقق من حالة المقعد
    public function isSeatAvailable($seat_id) {
        try{
            $query = "SELECT isavailable FROM " . $this->table_name . " WHERE seatid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $seat_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ? $row['isavailable'] : false;
        } catch(PDOException $e){
            echo "error in isSeatAvailable :". $e->getMessage();
            return false;
        }
    }

    public function updateSeatAvailability($seat_id, $availability) {
        try{
            $query = "UPDATE seats SET isavailable = ? WHERE seatid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $availability, PDO::PARAM_BOOL);
            $stmt->bindParam(2, $seat_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(PDOException $e){
            echo "error in updateSeatAvailability :". $e->getMessage();
            return false;
        }
    }
}
?>
