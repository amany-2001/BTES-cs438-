<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

// حذف الحدث
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    try {
        // بدء المعاملة
        $db->beginTransaction();

        // حذف التذاكر المرتبطة بالحدث
        $delete_tickets = "DELETE FROM tickets WHERE seat_id IN (SELECT seat_id FROM seats WHERE eventid = :eventid)";
        $stmt = $db->prepare($delete_tickets);
        $stmt->bindParam(':eventid', $event_id);
        $stmt->execute();

        // حذف المقاعد المرتبطة بالحدث
        $delete_seats = "DELETE FROM seats WHERE eventid = :eventid";
        $stmt = $db->prepare($delete_seats);
        $stmt->bindParam(':eventid', $event_id);
        $stmt->execute();

        // حذف الحدث نفسه
        $delete_event = "DELETE FROM events WHERE eventid = :eventid";
        $stmt = $db->prepare($delete_event);
        $stmt->bindParam(':eventid', $event_id);
        $stmt->execute();

        // إتمام المعاملة
        $db->commit();
        header("Location: admin_dashboard.php");
        exit();
    } catch (Exception $e) {
        $db->rollBack();
        $error_message = "Failed to delete event: " . $e->getMessage();
    }
}
?>
