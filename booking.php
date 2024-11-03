<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>biik ticket</title>
    </head>
    <body>
    <h2>حجز تذكرة</h2>
    <form action="action.php" method="post">
        <input type="hidden" name="action" value="bookTicket">
        <label for="user_id">معرف المستخدم:</label>
        <input type="number" name="user_id" required><br>
        <label for="seatnumber">رقم المقعد:</label>
        <input type="text" name="seatnumber" required><br>
        <label for="eventname">اسم الحدث:</label>
        <input type="text" name="eventname" required><br>
        <button type="submit">حجز التذكرة</button>
    </form>
    </body>
</html>