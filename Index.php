<?php
include_once 'database.php';
include_once 'User.php';
include_once 'Event.php';
include_once 'Ticket.php';

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management</title>
</head>
<body>
    <h1>Event Management System</h1>
    <?php 
        $event = new Event($db);
        $stmt = $event->getAllEvents();
        echo "<h2>جميع الأحداث</h2>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         echo $row['eventname'] . "<br>";
         ?>
        <form action="action.php" method="post" >
        <input type="hidden" name="action" value="details">
        <input type="hidden" name="eventname" value="<?php echo $row['eventname']; ?>">
        <button type="submit" >details</button>
        </form>
         <?php
        } ?>

    <a href="booking.php"> booking</a>
    <a href="refund.php"> refund</a>
    <a href="rate.php"> rate</a>
</body>
</html>
