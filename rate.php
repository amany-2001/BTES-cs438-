<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>rate event</title>
    </head>
    <body>
        <h2>تقييم حدث</h2>
        <form action="action.php" method="post">
            <input type="hidden" name="action" value="rateEvent">
            <label for="event_id">معرف الحدث:</label>
            <input type="number" name="event_id" required><br>
            <label for="user_id">معرف المستخدم:</label>
            <input type="number" name="user_id" required><br>
            <label for="review">التقييم:</label>
            <input type="text" name="review" required><br>
            <button type="submit">إرسال التقييم</button>
        </form>
    </body>
</html>