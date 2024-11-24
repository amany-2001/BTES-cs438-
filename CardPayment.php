<?php

class CardPayment extends Payment {
    private $accountNumber;
    private $password;

    // public function __construct($db, $userID, $ticketID, $amount, $accountNumber, $password) {
    //     parent::__construct($db, $userID);  // استدعاء الكونستركت من الكلاس الأب
    //     $this->ticketID = $ticketID;       
    //     $this->amount = $amount;           
    //     $this->accountNumber = $accountNumber;
    //     $this->password = $password;
    }

    // تنفيذ عملية الدفع عبر البطاقة المصرفية
    public function processPayment(): bool {
       if (isset($data['account_number'], $data['code'])) {
            $this->accountNumber = $data['account_number'];
            $this->code = $data['code'];
            return is_numeric($this->accountNumber) && is_numeric($this->code);
        }
        return false;
    }
}

?>
