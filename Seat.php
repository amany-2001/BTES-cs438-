<?php
class Seat {
    private $seatNumber;
    private $isAvailable;

    public function __construct($seatNumber, $isAvailable) {
        $this->seatNumber = $seatNumber;
        $this->isAvailable = $isAvailable;
    }

    public function getSeatNumber() {
        return $this->seatNumber;
    }

    public function setAvailable($isAvailable) {
        $this->isAvailable = $isAvailable;
    }

    public function choose_seat() {
        if ($this->isAvailable) {
            $this->setAvailable(false);
            return true; // تم اختيار المقعد بنجاح
        } else {
            return false; // المقعد غير متاح
        }
    }

    public function reserv_seats($available) {
        $this->isAvailable = $available;
    }

    public function is_seat_available() {
        return $this->isAvailable;
    }
}
?>
