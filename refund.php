<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>refund ticket</title>
    </head>
    <body>
        <h2>إرجاع تذكرة</h2>
        <form action="action.php" method="post">
            <input type="hidden" name="action" value="refundTicket">
            <label for="ticket_id">معرف التذكرة:</label>
            <input type="number" name="ticket_id" required><br>
            <button type="submit">إرجاع التذكرة</button>
        </form>
    </body>
</html>