<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

// التحقق من وجود ID الحدث في الرابط
if (!isset($_GET['id'])) {
    header("Location: manage_events.php");
    exit();
}

$event_id = $_GET['id'];

// جلب بيانات الحدث
$query = "SELECT * FROM events WHERE eventid = :eventid";
$stmt = $db->prepare($query);
$stmt->bindParam(':eventid', $event_id);
$stmt->execute();
$event = $stmt->fetch(PDO::FETCH_ASSOC);

// إذا لم يتم العثور على الحدث
if (!$event) {
    header("Location: manage_events.php");
    exit();
}

// تحديث بيانات الحدث
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_event'])) {
    $event_name = $_POST['event_name'];
    $event_category = $_POST['category'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];

    try {
        $update_query = "UPDATE events SET eventname = :eventname, category = :category, date = :date, location = :location 
                         WHERE eventid = :eventid";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(':eventname', $event_name);
        $update_stmt->bindParam(':category', $event_category);
        $update_stmt->bindParam(':date', $event_date);
        $update_stmt->bindParam(':location', $event_location);
        $update_stmt->bindParam(':eventid', $event_id);
        $update_stmt->execute();

        $success_message = "Event updated successfully!";
    } catch (Exception $e) {
        $error_message = "Failed to update event: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Edit Event</h1>
    <a href="manage_events.php">Back to Events</a>
    <hr>

    <!-- نموذج تعديل بيانات الحدث -->
    <form method="POST" action="edit_event.php?id=<?= htmlspecialchars($event_id) ?>">
        <label for="event_name">Event Name:</label><br>
        <input type="text" id="event_name" name="event_name" value="<?= htmlspecialchars($event['eventname']) ?>" required><br><br>

        <label for="category">Event Category:</label><br>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($event['category']) ?>" required><br><br>

        <label for="event_date">Event Date:</label><br>
        <input type="date" id="event_date" name="event_date" value="<?= htmlspecialchars($event['date']) ?>" required><br><br>

        <label for="event_location">Event Location:</label><br>
        <input type="text" id="event_location" name="event_location" value="<?= htmlspecialchars($event['location']) ?>" required><br><br>

        <button type="submit" name="update_event">Update Event</button>
    </form>

    <?php if (isset($success_message)) { echo "<p style='color:green;'>$success_message</p>"; } ?>
    <?php if (isset($error_message)) { echo "<p style='color:red;'>$error_message</p>"; } ?>
</body>
</html>
