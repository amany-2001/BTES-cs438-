<?php
class Event {
    private $eventID;
    private $eventName;
    private $category;
    private $date;
    private $location;
    private $seats = [];

    public function __construct($eventID, $eventName, $category, $date, $location) {
        $this->eventID = $eventID;
        $this->eventName = $eventName;
        $this->category = $category;
        $this->date = $date;
        $this->location = $location;
        for ($i = 1; $i <= 100; $i++) {
            $this->seats[] = new Seat($i, true); // جميع المقاعد متاحة افتراضيًا
        }
    }

    public function displayDetails() {
        // طباعة التفاصيل
        echo "Event ID: " . $this->eventID . "\n";
        echo "Event Name: " . $this->eventName . "\n";
        echo "Category: " . $this->category . "\n";
        echo "Date: " . $this->date . "\n";
        echo "Location: " . $this->location . "\n";
    }

    public function get_available_seats() {
        $availableSeats = [];
        foreach ($this->seats as $seat) {
            if ($seat->is_seat_available()) {
                $availableSeats[] = $seat;
            }
        }
        return $availableSeats;
    }

    public function reserve_seat($seatNumber) {
        foreach ($this->seats as $seat) {
            if ($seat->getSeatNumber() == $seatNumber) {
                if ($seat->is_seat_available()) {
                    $seat->setAvailable(false);
                    return true; // تم حجز المقعد بنجاح
                } else {
                    return false; // المقعد غير متاح
                }
            }
        }
        return false; 
    }

    public function isSeatAvailable($seatNumber) {
        foreach ($this->seats as $seat) {
            if ($seat->getSeatNumber() == $seatNumber) {
                return $seat->is_seat_available();
            }
        }
        return false; // المقعد غير موجود
    }

    // دوال getter و setter
    public function getEventID() {
        return $this->eventID;
    }

    public function getEventName() {
        return $this->eventName;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getDate() {
        return $this->date;
    }

    public function getLocation() {
        return $this->location;
    }
}
?>
