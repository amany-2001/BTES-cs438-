<?php
class MobicashPayment extends Payment {
    private $email;
    private $phone;

    // public function __construct($db, $userID, $amount, $ticketID, $email, $phone) {
    //     parent::__construct($db);  // استدعاء الكونستركت من الكلاس الأب
    //     $this->amount = $amount;  
    //     $this->ticketID = $ticketID;  
    //     $this->email = $email;
    //     $this->phone = $phone;
    // }

    // تنفيذ الدفع عبر خدمة موباي كاش
    public function processPayment(): bool {
       if (isset($data['phone_number'], $data['email'])) {
            $this->phoneNumber = $data['phone_number'];
            $this->email = $data['email'];
            return is_numeric($this->phoneNumber) && filter_var($this->email, FILTER_VALIDATE_EMAIL);
        }
        return false;
    }
}
?>
