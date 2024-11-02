<?php
// استدعاء ملفات الكلاسات
require_once 'Ticket.php';
require_once 'Event.php';
require_once 'Seat.php';
require_once 'User.php';

// إنشاء كائن من كلاس Database
$db = new Database($servername, $username, $password, $dbname);

// التأكد من الاتصال بقاعدة البيانات
$db->connect();

// الحصول على الاتصال
$conn = $db->getConnection

// إنشاء كائن Event
$event = new Event(1);

// عرض تفاصيل الحدث
$event->displayDetails();

// الحصول على المقاعد المتاحة
$availableSeats = $event->get_available_seats();
echo "Available Seats: \n";
foreach ($availableSeats as $seat) {
    echo "Seat Number: " . $seat->getSeatNumber() . "\n";
}

// إنشاء كائن User
$user = new User(1, "John Doe", 25, "john@example.com", "123456", "password", "1234567890", 100);

// حجز تذكرة
// $ticket = $user->bookTicket(1, $event->getEventID());
// if ($ticket) {
//     echo "Ticket booked successfully: Ticket ID = " . $ticket->getTicketID() . "\n";
// } else {
//     echo "Failed to book ticket.\n";
// }

// اختيار مقعد
if ($event->reserve_seat(1)) {
    echo "Seat reserved successfully.\n";
} else {
    echo "Failed to reserve seat.\n";
}
?>
