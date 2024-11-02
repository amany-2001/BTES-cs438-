<?php
class User {
    private $userID;
    private $userName;
    private $userAge;
    private $email;
    private $point;
    private $accountNumber;
    private $password;
    private $phone;
    private $review;

    public function __construct($userID, $userName, $userAge, $email, $accountNumber, $password, $phone, $point) {
        $this->userID = $userID;
        $this->userName = $userName;
        $this->userAge = $userAge;
        $this->email = $email;
        $this->point = $point;
        $this->accountNumber = $accountNumber;
        $this->password = $password;
        $this->phone = $phone;
    }

    public function search() {
        return null;
    }

    public function bookTicket($seatNumber, $eventID) {
        $ticket = null;
        $url = "mysql:host=localhost;dbname=btes";
        $user = "root";
        $password = "";

        try {
            $connection = new PDO($url, $user, $password);
            $query = "INSERT INTO ticket (userid, seatNumber, eventID, status) VALUES (3, r4t, 4, Booked)";
            $stmt = $connection->prepare($query);
            $stmt->execute([$this->userID, $seatNumber, $eventID, 'Booked']);

            $ticketID = $connection->lastInsertId();
            $ticket = new Ticket($ticketID, $eventID, $seatNumber, 'Booked');
            $connection = null;
            echo "Ticket booked successfully!";
        } catch (Exception $e) {
            echo "Error booking ticket: " . $e->getMessage();
        }
        return $ticket;
    }

    // public function rateEvent($eventID) {
    //     $url = "mysql:host=localhost;dbname=btes";
    //     $user = "root";
    //     $password = "";

    //     try {
    //         $connection = new PDO($url, $user, $password);
    //         $query = "INSERT INTO event_ratings (userID, eventID, rating, review) VALUES (?, ?, ?, ?)";
    //         $stmt = $connection->prepare($query);
    //         $stmt->execute([$this->userID, $eventID, null, $this->review]);

    //         $connection = null;
    //         echo "Event rated successfully!";
    //         return "Rating submitted!";
    //     } catch (Exception $e) {
    //         echo "Error rating event: " . $e->getMessage();
    //         return "Error submitting rating.";
    //     }
    // }

    public function sellTicket($ticketID) {
        $url = "mysql:host=localhost;dbname=btes";
        $user = "root";
        $password = "";

        try {
            $connection = new PDO($url, $user, $password);
            $query = "UPDATE ticket SET status='Sold' WHERE ticketID=? AND userId=?";
            $stmt = $connection->prepare($query);
            $stmt->execute([$ticketID, $this->userID]);

            if ($stmt->rowCount() > 0) {
                echo "Ticket sold successfully!";
                return true;
            } else {
                echo "No matching ticket found or already sold.";
                return false;
            }
        } catch (Exception $e) {
            echo "Error selling ticket: " . $e->getMessage();
            return false;
        }
    }

    public function getAge() {
        return $this->userAge;
    }

    public function getPoint() {
        return $this->point;
    }
}
?>
