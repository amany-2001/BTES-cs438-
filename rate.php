<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>rate event</title>
        <link rel="stylesheet" href="style.css"> 
    </head>
    <body>
        <div class="page-header">
            <h1>Rate an Event</h1>
        </div>
        <form action="action.php" method="post">
            <input type="hidden" name="action" value="rateEvent">
            <label for="event_id"> event id:</label>
            <input type="number" name="event_id" required><br>
            <label for="user_id">user id :</label>
            <input type="number" name="user_id" required><br>
            <label for="review">the rating:</label>
            <input type="textarea" name="review" required><br>
            <button type="submit"> rated</button>
        </form>
    </body>
</html>
