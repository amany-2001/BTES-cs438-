<?php
interface Observer {
    public function update($eventData);
}

class EmailNotifier implements Observer {
    public function update($eventData) {
        // إرسال بريد إلكتروني عند إضافة حدث جديد
        echo "Email sent: New event '{$eventData['name']}' added on {$eventData['date']}.<br>";
    }
}

class Logger implements Observer {
    public function update($eventData) {
        // تسجيل الحدث في سجل النشاطات
        echo "Log: Event '{$eventData['name']}' added.<br>";
    }
}

class EventManager {
    private $observers = [];

    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function notifyObservers($eventData) {
        foreach ($this->observers as $observer) {
            $observer->update($eventData);
        }
    }
}
?>
