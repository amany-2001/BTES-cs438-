<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// استيراد الملفات المطلوبة
include_once 'database.php';       // الاتصال بقاعدة البيانات
include_once 'EventObserver.php';  // Observer Pattern للإشعارات

// الاتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

// إنشاء مدير الأحداث للمراقبين (Observer Pattern)
$eventManager = new EventManager();
$eventManager->addObserver(new EmailNotifier());
$eventManager->addObserver(new Logger());

// إضافة حدث جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event'])) {
    $eventData = [
        'name' => $_POST['event_name'],
        'category' => $_POST['category'],
        'date' => $_POST['event_date'],
        'location' => $_POST['event_location'],
        'seat_count' => intval($_POST['seat_count']),
        'price_per_seat' => floatval($_POST['price_per_seat'])
    ];

    try {
        // بدء معاملة قاعدة البيانات
        if (!$db->inTransaction()) {
            $db->beginTransaction();
        }

        // إضافة الحدث إلى قاعدة البيانات
        $query = "INSERT INTO events (eventname, date, location, category) 
                  VALUES (:eventname, :date, :location, :category)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':eventname', $eventData['name']);
        $stmt->bindParam(':date', $eventData['date']);
        $stmt->bindParam(':location', $eventData['location']);
        $stmt->bindParam(':category', $eventData['category']);
        $stmt->execute();

        // الحصول على ID الحدث الذي تم إضافته
        $event_id = $db->lastInsertId();

        // إضافة المقاعد والتذاكر
        $seat_query = "INSERT INTO seats (eventid, seatnumber, isavailable) VALUES (:eventid, :seatnumber, 1)";
        $seat_stmt = $db->prepare($seat_query);

        $ticket_query = "INSERT INTO tickets (seat_id, eventid, status) VALUES (:seat_id, :eventid, 'available')";
        $ticket_stmt = $db->prepare($ticket_query);

        // إضافة المقاعد والتذاكر بناءً على عدد المقاعد
        for ($i = 1; $i <= $eventData['seat_count']; $i++) {
            $seat_stmt->bindParam(':eventid', $event_id);
            $seat_stmt->bindParam(':seatnumber', $i);
            $seat_stmt->execute();

            $seat_id = $db->lastInsertId();  // الحصول على ID المقعد الجديد

            // إضافة التذكرة
            $ticket_stmt->bindParam(':seat_id', $seat_id);
            $ticket_stmt->bindParam(':eventid', $event_id);
            $ticket_stmt->execute();
        }

        // تنفيذ عملية commit بعد إضافة جميع البيانات
        $db->commit();

        // إشعار المراقبين بعد إضافة الحدث
        $eventManager->notifyObservers($eventData);

        $success_message = "Event added successfully!";
    } catch (Exception $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }
        $error_message = "Failed to add event: " . $e->getMessage();
    }
}

// جلب قائمة الأحداث
$query = "SELECT * FROM events";
$stmt = $db->prepare($query);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Events</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Manage Events</h1>
    <a href="admin_dashboard.php">Back to Dashboard</a>
    <hr>

    <!-- نموذج إضافة حدث جديد -->
    <h2>Add New Event</h2>
    <form method="POST" action="manage_events.php">
        <label for="event_name">Event Name:</label><br>
        <input type="text" id="event_name" name="event_name" required><br><br>

        <label for="category">Event Category:</label><br>
        <input type="text" id="category" name="category" required><br><br>

        <label for="event_date">Event Date:</label><br>
        <input type="date" id="event_date" name="event_date" required><br><br>

        <label for="event_location">Event Location:</label><br>
        <input type="text" id="event_location" name="event_location" required><br><br>

        <label for="seat_count">Number of Seats:</label><br>
        <input type="number" id="seat_count" name="seat_count" required><br><br>

        <label for="price_per_seat">Price per Seat:</label><br>
        <input type="number" id="price_per_seat" name="price_per_seat" step="0.01" required><br><br>

        <button type="submit" name="add_event">Add Event</button>
    </form>

    <?php if (isset($success_message)) { echo "<p style='color:green;'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>

    <hr>

    <!-- عرض قائمة الأحداث الحالية -->
    <h2>Current Events</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Date</th>
            <th>Location</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($events as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event['eventid']) ?></td>
                <td><?= htmlspecialchars($event['eventname']) ?></td>
                <td><?= htmlspecialchars($event['category']) ?></td>
                <td><?= htmlspecialchars($event['date']) ?></td>
                <td><?= htmlspecialchars($event['location']) ?></td>
                <td>
                    <a href="edit_event.php?id=<?= $event['eventid'] ?>">Edit</a> |
                    <a href="delete_event.php?id=<?= $event['eventid'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

