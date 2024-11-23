<?php

abstract class Payment{
    protected $coon;
    protected $paymentId;
    protected $userId;
    protected $price;
    protected $state;
    protected $ticketId;

    public function __construct($db,$userId){
        $this->conn =$db;
        $this->userId =$userId;
        ///جلب بيانات الدفع من قاعدة البيانات بناء علي معرف المستخدم
        $query = "SELEC t.ticketId , t.price , u.userId FROM tickets t
                    INSERT JOIN users u ON t.userId = u.userId
                    WHERE u.userId =?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->userId);
        $stmt->execute();
        $data =$stmt->fetch(PDO::FETCH_ASSOC);
        
        if($data){
            $this->ticketId = $data['ticketId'];
            $this->price = $data['price'];
            $this->state = "pending"; ///قيمة افتلراضية 
        }else{
            throw new Exception("no booking found for user id : {$this->userId}");
        }
    }
    ///دالة معالجة الدفع يتم تنفيذها في كلايات الابناء 
    abstract public function processPayment() : bool;

    ////دالة ارجاع المبلغ في حالة الغاء الحجز
    public function refoundPayment($state){
        //TODO
    }
}