
<?php
include_once 'database.php';
include_once 'User.php';
include_once 'Event.php';
include_once 'Seat.php';

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Event Management System</h1>
    <?php 
        $event = new Event($db);
        $stmt = $event->getAllEvents();
        echo "<h2>All Events</h2>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<h3>" . $row['eventname'] . "</h3>";
            echo $row['category']. '<br>' ;
            echo "<form action='action.php' method='post'>";
            echo "<input type='hidden' name='action' value='details'>";
            echo "<input type='hidden' name='event_id' value='" . $row['eventid'] . "'>";
            echo "<button type='submit'>Details</button>";
            echo "</form>";
        }
        if (isset($_GET['event_id'])) {
            $event_id = $_GET['event_id'];
            $seatObj = new Seat($db);
            $availableSeats = $seatObj->getAvailableSeatsByEvent($event_id);

            echo "<h2>Available Seats for Event ID: $event_id</h2>";
            if (!empty($availableSeats)) {
                foreach ($availableSeats as $seat) {
                    echo "Seat Number: " . $seat['seat_number'] . "<br>";
                    ?>
                    <form action="action.php" method="post">
                        <input type="hidden" name="action" value="choose_seat">
                        <input type="hidden" name="seat_id" value="<?php echo $seat['seatid']; ?>">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                        <button type="submit">Book this Seat</button>
                    </form>
                    <br>
                    <?php
                }
            } else {
                echo "No available seats for this event.";
            }
        }
    ?>
    <a href="refund.php"> refund</a>
    <a href="rate.php"> rate</a>
</body>
</html>
