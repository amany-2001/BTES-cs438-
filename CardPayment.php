
<?php

class CardPayment extends Payment {
    public $accountNumber;
    public $code;

    public function processPayment($data) {
        if (isset($data['account_number'], $data['code'])) {
            $this->accountNumber = $data['account_number'];
            $this->code = $data['code'];
            return is_numeric($this->accountNumber) && is_numeric($this->code);
        }
        return false;
    }
}

?>
