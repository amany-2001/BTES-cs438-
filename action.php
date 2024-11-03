<?php
include_once 'database.php';
include_once 'User.php';
include_once 'Event.php';
include_once 'Ticket.php';

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'];

if ($action == 'details') {
    $eventname = isset($_POST['eventname']) ? $_POST['eventname'] : '';
    if ($eventname) {
        $event = new Event($db);
        $stmt = $event->displayDetails($eventname); 
        echo "<h2>تفاصيل الحدث: $eventname</h2>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "اسم الحدث: " . $row['eventname'] . "<br>";
            echo "الوصف: " . $row['category'] . "<br>";
            echo "التاريخ: " . $row['date'] . "<br>";
            echo "الموقع: " . $row['location'] . "<br><br>";
        }
    } else {
        echo "حدث خطأ: لم يتم تمرير اسم الحدث.";
    }
} elseif ($action == 'bookTicket') {
    $user_id = $_POST['user_id'];
    $seat_number = $_POST['seatnumber'];
    $event_name = $_POST['eventname'];

    $user = new User($db);
    if ($user->bookTicket($user_id, $seat_number, $event_name)) {
        echo "تم حجز التذكرة بنجاح.";
    } else {
        echo "فشل في حجز التذكرة.";
    }
} elseif ($action == 'refundTicket') {
    $ticket_id = $_POST['ticket_id'];

    $user = new User($db);
    if ($user->refundTicket($ticket_id)) {
        echo "تم إرجاع التذكرة بنجاح.";
    } else {
        echo "فشل في إرجاع التذكرة.";
    }
}  elseif ($action == 'rateEvent') {
    $event_id = $_POST['event_id'];
    $user_id = $_POST['user_id'];
    $review = $_POST['review'];

    $user = new User($db);
    if ($user->rateEvent($event_id, $user_id, $review)) {
        echo "تم إرسال التقييم بنجاح.";
    } else {
        echo "فشل في إرسال التقييم.";
    }
}
?>