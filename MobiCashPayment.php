<?php
class MobicashPayment extends Payment {
    private $email;
    private $phone;

    public function __construct($db, $userID, $amount, $ticketID, $email, $phone) {
        parent::__construct($db, $userID);  // استدعاء الكونستركت من الكلاس الأب
        $this->amount = $amount;  
        $this->ticketID = $ticketID;  
        $this->email = $email;
        $this->phone = $phone;
    }

    // تنفيذ الدفع عبر خدمة موباي كاش
    public function processPayment(): bool {
        return false;
    }
}
?>
