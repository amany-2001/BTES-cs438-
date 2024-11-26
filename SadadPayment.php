<?php
class SadadPayment extends Payment {
    public $code;

    public function processPayment($data) {
        if (isset($data['code'])) {
            $this->code = $data['code'];
            return is_numeric($this->code);
        }
        return false;
    }
}
?>

