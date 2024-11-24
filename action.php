<?php
include_once 'database.php';
include_once 'User.php';
include_once 'Event.php';
include_once 'Ticket.php';
include_once 'Payment.php';
include_once 'CardPayment.php';
include_once 'MobiCashPayment.php';
include_once 'SadadPayment.php';

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'];


if ($action == 'details') {
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : '';
    if ($event_id) {
        $event = new Event($db);
        $stmt = $event->displayDetails($event_id);
        echo "<h2>تفاصيل الحدث</h2>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "event name " . $row['eventname'] . "<br>";
            echo "category: " . $row['category'] . "<br>";
            echo "date: " . $row['date'] . "<br>";
            echo "location: " . $row['location'] . "<br><br>";
        }

        // عرض المقاعد المتاحة
        $seat = new Seat($db);
        $availableSeats = $seat->getSeatsByEvent($event_id);

        echo "<form action='booking.php' method='post'>";
        echo "<table border='1'>";
        echo "<tr><th> seat number</th><th>price</th><th>  </th></tr>";
        foreach ($availableSeats as $seat) {
            echo "<tr>";
            echo "<td>" . $seat['seatnumber'] . "</td>";
            echo "<td>". "</td>"; ////////المفروض ان نضيفو السعر هنا 
            echo "<td><input type='checkbox' name='seat_ids[]' value='" . $seat['seatid'] . "'></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' name='event_id' value='" . $event_id . "'>";
        echo "<button type='submit'> go to booking </button>";
        echo "</form>";
    } else {
        echo "error:event id not passed";
    }
} elseif ($action == 'bookTicket') {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];
    $event_id = $_POST['event_id'];
    $seat_ids = $_POST['seat_ids'];
    $paymentType = $_POST['payment_method'];
    $paymentData = $_POST['payment_data'];

    $ticket = new Ticket($db);
    $user = new User($db);
    $event = new Event($db);

    $stmt = $event->displayDetails($eventid);
    $eventDetails = $stmt->fetch(PDO::FETCH_ASSOC); // الحصول على تفاصيل الحدث
    try {
        $ticket->bookWithPayment($userID, $seat_id, $eventid, $paymentType, $paymentData);
        echo "Ticket booked successfully with payment.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

   
} elseif ($action == 'refundTicket') {
    $ticket_id = $_POST['ticket_id'];

    $user = new User($db);
    if ($user->refundTicket($ticket_id)) {
        echo " the ticket has been refunded";
    } else {
        echo "failed to refund ticket";
    }
}  elseif ($action == 'rateEvent') {
    $event_id = $_POST['event_id'];
    $user_id = $_POST['user_id'];
    $review = $_POST['review'];

    $user = new User($db);
    if ($user->rateEvent($event_id, $user_id, $review)) {
        echo "//////*********";
    } else {
        echo "/./************/";
    }
}
?>
