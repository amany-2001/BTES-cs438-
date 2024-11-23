<?php
class SdadPayment extends Payment {
    private $storeNumber;

    public function __construct($db, $userID, $amount, $ticketID, $storeNumber) {
        parent::__construct($db, $userID);  // استدعاء الكونستركت من الكلاس الأب
        $this->amount = $amount;  
        $this->ticketID = $ticketID;  
        $this->storeNumber = $storeNumber;
    }

    // تنفيذ الدفع عبر خدمة سداد
    public function processPayment(): bool {
        return false;
    }
}
?>
