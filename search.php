<?php
include_once 'database.php';
include_once 'Event.php';

// إنشاء اتصال بقاعدة البيانات
$database = new Database();
$db = $database->getConnection();

// إنشاء كائن الحدث
$event = new Event($db);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Events</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Search for Events</h1>

    <!-- نموذج البحث -->
    <form action="search.php" method="get">
        <label for="search">Enter Event Name or Category:</label>
        <input type="text" id="search" name="search" placeholder="Search for events..." required>
        <button type="submit">Search</button>
    </form>

    <?php
    // إذا تم تقديم طلب البحث
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = htmlspecialchars($_GET['search']); // الحصول على نص البحث
        echo "<h2>Search Results for: '$searchTerm'</h2>";

        // استخدام دالة البحث مع عرض التفاصيل
        $event->searchAndDisplayDetails($searchTerm);
    }
    ?>
</body>
</html>
