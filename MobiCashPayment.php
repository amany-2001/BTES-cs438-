<?php
class MobiCashPayment extends Payment {
    public $phoneNumber;
    public $email;

    public function processPayment($data) {
        if (isset($data['phone_number'], $data['email'])) {
            $this->phoneNumber = $data['phone_number'];
            $this->email = $data['email'];
            return is_numeric($this->phoneNumber) && filter_var($this->email, FILTER_VALIDATE_EMAIL);
        }
        return false;
    }
}
?>
