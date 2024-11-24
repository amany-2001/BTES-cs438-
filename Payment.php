<?php
include_once 'database.php';
abstract class Payment{
    protected $coon;
    protected $paymentId;
    protected $userId;
    protected $amount;
    protected $state;
    protected $ticketId;
    protected $method;

    public function __construct($db){
        $this->conn = $db;
    }
    ///دالة معالجة الدفع يتم تنفيذها في كلايات الابناء 
    abstract public function processPayment();

    ////دالة ارجاع المبلغ في حالة الغاء الحجز
    public function refoundPayment($state){
        //TODO
    }
}
