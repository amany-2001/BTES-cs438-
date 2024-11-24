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

    public function bookWithPayment($userID, $seat_id, $eventid, $paymentType, $paymentData) {
        $price = $this->getPrice($eventid, $seat_id);

        if (!$price) {
            throw new Exception("Invalid ticket details.");
        }

        // إنشاء كائن من نوع الدفع المحدد
        $paymentClass = $paymentType . "Payment";
        if (!class_exists($paymentClass)) {
            throw new Exception("Invalid payment method.");
        }

        $payment = new $paymentClass($this->conn);
        $payment->ticketID = $seat_id; 
        $payment->userID = $userID;
        $payment->amount = $price;
        $payment->status = 'pending';
        $payment->method = $paymentType;

        // التحقق من بيانات الدفع
        if (!$payment->validate($paymentData)) {
            throw new Exception("Invalid payment details.");
        }

        // تنفيذ عملية الدفع
        if ($payment->createPayment()) {
            return true;
        }

        throw new Exception("Payment failed.");
    }
}
?>
