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
        // التأكد من إرسال بيانات المقعد والحدث 
        if (isset($_POST['seatid']) && isset($_POST['eventid'])) { 
            $seat_id = $_POST['seat_id']; 
            $eventid = $_POST['eventid']; 
            echo "<input type='hidden' name='seat_id' value='$seat_id'>";
            echo "<input type='hidden' name='event_id' value='$eventid'>";
        }
 
        $price = isset($_POST['price']) ? $_POST['price'] : 0; 
        echo "<input type='hidden' name='price' value='$price'>"; 
        ?> 
 
        <!-- إدخال معرف المستخدم وكلمة المرور --> 
        <label for="user_id">معرف المستخدم:</label> 
        <input type="text" name="user_id" required><br> 
 
        <label for="password">كلمة المرور:</label> 
        <input type="password" name="password" required><br> 
 
        <!-- زر تأكيد الحجز --> 
        <button type="submit">تأكيد الحجز</button> 
    </form> 
</body> 
</html>
