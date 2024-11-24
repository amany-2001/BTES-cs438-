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
        <input type="hidden" name="action" value="action"> 
         
        <?php 
        // التأكد من إرسال بيانات المقعد والحدث 
        if (isset($_POST['seat_id']) && isset($_POST['event_id'])) { 
            $seat_id = $_POST['seat_id']; 
            $event_id = $_POST['event_id']; 
            echo "<input type='hidden' name='seat_id' value='$seat_id'>"; 
            echo "<input type='hidden' name='event_id' value='$event_id'>"; 
        } 
 
        $price = isset($_POST['price']) ? $_POST['price'] : 0; 
        echo "<input type='hidden' name='price' value='$price'>"; 
        ?> 
 
        <!-- إدخال معرف المستخدم وكلمة المرور --> 
        <label for="user_id">معرف المستخدم:</label> 
        <input type="text" name="user_id" required><br> 
 
        <label for="password">كلمة المرور:</label> 
        <input type="password" name="password" required><br> 
 
        <!-- اختيار طريقة الدفع --> 
        <label for="payment_method">طريقة الدفع:</label><br> 
        <input type="radio" name="payment_method" value="card" required> البطاقة المصرفية<br> 
        <input type="radio" name="payment_method" value="sadad" required> خدمة سداد<br> 
        <input type="radio" name="payment_method" value="mobicash" required> موبي كاش<br> 
 
        <!-- قسم تفاصيل الدفع يظهر حسب الاختيار --> 
        <div id="card_details" style="display:none;"> 
            <label for="account_number">رقم الحساب:</label> 
            <input type="text" name="account_number"><br> 
 
            <label for="card_password">الرمز السري:</label> 
            <input type="password" name="card_password"><br> 
        </div> 
 
        <div id="sadad_details" style="display:none;"> 
            <label>رقم المتجر: </label> 
            <span>12345</span><br> 
            <label for="sadad_code">رمز سداد السري:</label> 
            <input type="password" name="sadad_code"><br> 
        </div> 
 
        <div id="mobicash_details" style="display:none;"> 
            <label for="email">البريد الإلكتروني:</label> 
            <input type="email" name="email"><br> 
 
            <label for="phone">رقم الهاتف:</label> 
            <input type="text" name="phone"><br> 
        </div> 
 
        <!-- زر تأكيد الحجز --> 
        <button type="submit">تأكيد الحجز</button> 
    </form> 
 
    <!-- JavaScript لتغيير تفاصيل الدفع حسب الاختيار --> 
    <script> 
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]'); 
        paymentMethods.forEach(method => { 
            method.addEventListener('change', function() { 
                document.getElementById('card_details').style.display = (this.value === 'card') ? 'block' : 'none'; 
                document.getElementById('sadad_details').style.display = (this.value === 'sadad') ? 'block' : 'none'; 
                document.getElementById('mobicash_details').style.display = (this.value === 'mobicash') ? 'block' : 'none'; 
            }); 
        }); 
    </script> 
</body> 
</html>
