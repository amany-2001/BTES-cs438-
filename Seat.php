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
        try{
            $query = "SELECT * FROM " . $this->table_name . " WHERE eventid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $event_id);
            $stmt->execute();
            return $stmt;
        } catch(PDOException $e){
            echo "error in getSeatsByEvent :". $e->getMessage();
            return false;
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
    public function chooseSeat($seat_id) {
        try{
            // التحقق من توفر المقعد
            $query = "SELECT isavailable FROM " . $this->table_name . " WHERE seatid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $seat_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['isavailable']) {
                // تحديث حالة المقعد إلى غير متاح
                $updateQuery = "UPDATE " . $this->table_name . " SET isavailable = 0 WHERE seatid = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->bindParam(1, $seat_id);
                return $updateStmt->execute();
            } else {
                return false; // المقعد غير متاح
            }
        } catch(PDOException $e){
            echo "error in chooseSeat :". $e->getMessage();
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

    // public function setAvailable($isAvailable) {
    //     $this->isAvailable = $isAvailable;
    // }
    // public function reserv_seats($available) {
    //     $this->isAvailable = $available;
    // }
}
?>
