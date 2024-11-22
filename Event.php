<?php
include_once 'database.php';
$database = new Database();
$db = $database->getConnection();

class Event {
    private $conn;
    private $table_name = "events";
    
    private $eventId;
    private $eventName;
    private $category;
    private $date;
    private $location;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllEvents() {
        try {
            $query = "SELECT eventname, category, eventid FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error in getAllEvents: " . $e->getMessage();
            return false;
        }
    }

    public function displayDetails($eventid) {
        try {
            $query = "SELECT * FROM events WHERE eventid = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $eventid, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error in displayDetails: " . $e->getMessage();
            return false;
        }
    }

    public function searchEvent($nameOrCategory) {
        try {
            $query = "SELECT eventid, eventname, category FROM events WHERE eventname LIKE :search OR category LIKE :search";
            $stmt = $this->conn->prepare($query);

            // إضافة % للبحث الجزئي
            $searchTerm = "%" . $nameOrCategory . "%";
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo "Error in searchEvent: " . $e->getMessage();
            return false;
        }
    }

    // البحث وعرض التفاصيل
    public function searchAndDisplayDetails($nameOrCategory) {
        $searchResults = $this->searchEvent($nameOrCategory);

        if ($searchResults && $searchResults->rowCount() > 0) {
            while ($row = $searchResults->fetch(PDO::FETCH_ASSOC)) {
                echo "Event ID: " . $row['eventid'] . "<br>";
                echo "Event Name: " . $row['eventname'] . "<br>";
                echo "Category: " . $row['category'] . "<br>";

                // استدعاء عرض التفاصيل لكل حدث باستخدام ID
                $details = $this->displayDetails($row['eventid']);
                if ($details) {
                    $detailRow = $details->fetch(PDO::FETCH_ASSOC);
                    echo "Date: " . $detailRow['date'] . "<br>";
                    echo "Location: " . $detailRow['location'] . "<br>";
                }
                echo "--------------------------------<br>";
            }
        } else {
            echo "No events found matching your search.<br>";
        }
    }
}
?>
