<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Ticket</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="page-header">
        <h1>Book a Ticket</h1>
    </div>
    <form action="action.php" method="post">
        <input type="hidden" name="action" value="bookTicket">
        <?php
        if (isset($_POST['seat_ids']) && isset($_POST['event_id'])) {
            foreach ($_POST['seat_ids'] as $seat_id) {
                echo "<input type='hidden' name='seat_ids[]' value='$seat_id'>";
            }
            echo "<input type='hidden' name='event_id' value='" . $_POST['event_id'] . "'>";
        }
        ?>
        <label for="user_id">معرف المستخدم:</label>
        <input type="text" name="user_id" required><br>

        <label for="password">كلمة المرور:</label>
        <input type="password" name="password" required><br>

        <button type="submit">تأكيد الحجز</button>
    </form>
</body>
</html>
