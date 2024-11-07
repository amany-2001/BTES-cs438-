<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>refund ticket</title>
        <link rel="stylesheet" href="style.css"> 
    </head>
    <body>
        <div class="page-header">
            <h1>Refund a Ticket</h1>
        </div>
        <form action="action.php" method="post">
            <input type="hidden" name="action" value="refundTicket">
            <label for="ticket_id"> ticket id:</label>
            <input type="number" name="ticket_id" required><br>
            <button type="submit"> refund</button>
        </form>
    </body>
</html>
