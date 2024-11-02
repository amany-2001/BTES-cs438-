<?php
class Ticket {
    private $ticketID;
    private $eventName;
    private $seatNumber;
    private $price;
    private $status;

    public function __construct($ticketID, $eventName, $seatNumber, $price, $status) {
        $this->ticketID = $ticketID;
        $this->eventName = $eventName;
        $this->seatNumber = $seatNumber;
        $this->price = $price;
        $this->status = $status;
    }

    public function refund() {
        if ($this->status !== "Purchased") {
            echo "Ticket cannot be refunded.";
            return false;
        }
        $query = "UPDATE ticket SET status='Refunded' WHERE ticketID=?";
        return $this->updateTicketStatus($query, $this->ticketID);
    }

    public function sell() {
        if ($this->status !== "Available") {
            echo "Ticket cannot be sold.";
            return false;
        }
        $query = "UPDATE ticket SET status='Sold' WHERE ticketID=?";
        return $this->updateTicketStatus($query, $this->ticketID);
    }

    public function purchase() {
        if ($this->status !== "Available") {
            echo "Ticket cannot be purchased.";
            return false;
        }
        $query = "UPDATE ticket SET status='Purchased' WHERE ticketID=?";
        return $this->updateTicketStatus($query, $this->ticketID);
    }

    private function updateTicketStatus($query, $ticketID) {
        $url = "mysql:host=localhost;dbname=btes";
        $user = "root";
        $password = "";
        try {
            $connection = new PDO($url, $user, $password);
            $stmt = $connection->prepare($query);
            $stmt->execute([$ticketID]);
            $connection = null;
            echo "Ticket status updated successfully!";
            return true;
        } catch (Exception $e) {
            echo "Error updating ticket status: " . $e->getMessage();
            return false;
        }
    }

    public function getTicketID() {
        return $this->ticketID;
    }

    public function setTicketID($ticketID) {
        $this->ticketID = $ticketID;
    }

    public function getEventName() {
        return $this->eventName;
    }

    public function setEventName($eventName) {
        $this->eventName = $eventName;
    }

    public function getSeatNumber() {
        return $this->seatNumber;
    }

    public function setSeatNumber($seatNumber) {
        $this->seatNumber = $seatNumber;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }
}
?>
