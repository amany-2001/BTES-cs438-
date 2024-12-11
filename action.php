<?php
include_once 'database.php';
include_once 'User.php';
include_once 'Event.php';
include_once 'Ticket.php';
include_once 'Seat.php'; 


// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

$action = $_POST['action'];




if ($action == 'details') {
    $eventid = isset($_POST['eventid']) ? $_POST['eventid'] : '';
    if ($eventid) {
        $event = new Event($db);
        $stmt = $event->displayDetails($eventid);
        echo "<h2>تفاصيل الحدث</h2>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "event name " . $row['eventname'] . "<br>";
            echo "category: " . $row['category'] . "<br>";
            echo "date: " . $row['date'] . "<br>";
            echo "location: " . $row['location'] . "<br><br>";
        }

        // عرض المقاعد المتاحة
        $seat = new Seat($db);
        $availableSeats = $seat->getSeatsByEvent($eventid);

        echo "<form action='booking.php' method='post'>";
        echo "<table border='1'>";
        echo "<tr><th> seat number</th><th>price</th><th>  </th></tr>";
        foreach ($availableSeats as $seat) {
            echo "<tr>";
            echo "<td>" . $seat['seatnumber'] . "</td>";
            echo "<td>" . $seat['price'] . "</td>"; ////////المفروض ان نضيفو السعر هنا 
            echo "<td><input type='checkbox' name='seat_id[]' value='" . $seat['seat_id'] . "'></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' name='eventid' value='" . $eventid . "'>";
        echo "<button type='submit'> go to booking </button>";
        echo "</form>";
    } else {
        echo "error:event id not passed";
    }

   

} elseif ($action == 'bookTicket') {
        $user_id = $_POST['user_id'];
        $eventid = $_POST['eventid'];
        $seat_id = $_POST['seat_id'];
       
        $ticket = new Ticket($db);
        $user = new User($db);
        $event = new Event($db);
        try {
            $ticket->bookTicket($user_id, $seat_id, $eventid);

            echo "Ticket booked successfully .";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        
    

    }
    //$stmt = $event->displayDetails($eventid);
    //$eventDetails = $stmt->fetch(PDO::FETCH_ASSOC); // الحصول على تفاصيل الحدث
   

   
    if ($action == 'refundTicket') {
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
