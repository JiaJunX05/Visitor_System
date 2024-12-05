<?php

include_once("../include/db.php");

class VerifyController{
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function validateQRCode($scannedData) {
        if (empty($scannedData)) {
            return ['status' => 'error', 'message' => 'QR Code data is missing'];
        }

        $stmt = $this->conn->prepare("
            SELECT v.*, v.name AS visitor_name, q.generated_at, q.expires_at, u.name AS owner_name, u.id AS owner_id
            FROM visitors AS v
            INNER JOIN qr_codes AS q ON v.visitor_code = q.qr_code
            INNER JOIN users AS u ON v.owner_id = u.id
            WHERE v.visitor_code = ?
        ");
        $stmt->bind_param("s", $scannedData);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $visitor = $result->fetch_assoc();
            return $this->processVisitorData($visitor);
        } else {
            return ['status' => 'error', 'message' => 'QR Code Invalid'];
        }
    }

    private function processVisitorData($visitor) {
        $expiration_time = strtotime($visitor['expires_at']);
        $visit_date = strtotime($visitor['visit_date']);
        $current_time = time();

        if ($current_time > $expiration_time) {
            return ['status' => 'error', 'message' => 'QR Code Expired'];
        } elseif ($visit_date > $current_time) {
            return ['status' => 'error', 'message' => 'Visit date not reached yet!'];
        } else {
            return [
                'status' => 'success',
                'visitor' => [
                    'id' => $visitor['id'],
                    'name' => $visitor['visitor_name']
                ],
                'owner' => [
                    'id' => $visitor['owner_id'],
                    'name' => $visitor['owner_name']
                ],
                'message' => "Visitor Valid: {$visitor['visitor_name']}"
            ];
        }
    }

    // public function insertVisit($visitorId, $ownerId, $visitDate, $status) {
    //     // Validate the inputs
    //     if (empty($visitorId) || empty($ownerId) || empty($visitDate) || empty($status)) {
    //         return ['status' => 'error', 'message' => 'Missing required information'];
    //     }
    
    //     // Get the current date (in YYYY-MM-DD format)
    //     $currentDate = date("Y-m-d", strtotime($visitDate));
    
    //     // Check if the visitor has already visited today
    //     $stmt = $this->conn->prepare("
    //         SELECT COUNT(*) FROM visits 
    //         WHERE visitor_id = ? AND owner_id = ? AND DATE(visit_date) = ?
    //     ");
    //     $stmt->bind_param("iis", $visitorId, $ownerId, $currentDate);
    //     $stmt->execute();
    //     $stmt->bind_result($visitCount);
    //     $stmt->fetch();
    //     $stmt->free_result(); // Clear the previous result set
    
    //     if ($visitCount > 0) {
    //         // If a visit is already recorded today, return an error
    //         return ['status' => 'error', 'message' => 'Visit already recorded today'];
    //     }
    
    //     // Prepare the query to insert a new visit record, including the status
    //     $stmt = $this->conn->prepare("
    //         INSERT INTO visits (visitor_id, owner_id, visit_date, status) 
    //         VALUES (?, ?, ?, ?)
    //     ");
        
    //     // Bind the parameters (assuming $visitDate is in a valid date format, and $status is a string)
    //     $stmt->bind_param("iiss", $visitorId, $ownerId, $visitDate, $status);
        
    //     // Execute the statement
    //     if ($stmt->execute()) {
    //         return ['status' => 'success', 'message' => 'Visit recorded successfully'];
    //     } else {
    //         return ['status' => 'error', 'message' => 'Failed to record visit'];
    //     }
    // }

    public function insertVisit($visitorId, $ownerId, $visitDate, $status) {
        // Validate the inputs
        if (empty($visitorId) || empty($ownerId) || empty($visitDate) || empty($status)) {
            return ['status' => 'error', 'message' => 'Missing required information'];
        }
    
        // Define the time window in seconds (e.g., 5 minutes = 300 seconds)
        $timeWindow = 300;
    
        // Check if the visitor has visited within the time window
        $stmt = $this->conn->prepare("
            SELECT visit_date FROM visits 
            WHERE visitor_id = ? AND owner_id = ? 
            ORDER BY visit_date DESC LIMIT 1
        ");
        $stmt->bind_param("ii", $visitorId, $ownerId);
        $stmt->execute();
        $stmt->bind_result($lastVisitDate);
        $stmt->fetch();
        $stmt->free_result(); // Clear the previous result set
    
        // If there's a recent visit, check the time difference
        if ($lastVisitDate) {
            $lastVisitTime = strtotime($lastVisitDate);
            $currentVisitTime = strtotime($visitDate);
    
            // If the last visit was within the time window, return an error
            if (($currentVisitTime - $lastVisitTime) < $timeWindow) {
                return ['status' => 'error', 'message' => 'Visit recorded recently, try again later'];
            }
        }
    
        // Prepare the query to insert a new visit record, including the status
        $stmt = $this->conn->prepare("
            INSERT INTO visits (visitor_id, owner_id, visit_date, status) 
            VALUES (?, ?, ?, ?)
        ");
        
        // Bind the parameters (assuming $visitDate is in a valid date format, and $status is a string)
        $stmt->bind_param("iiss", $visitorId, $ownerId, $visitDate, $status);
        
        // Execute the statement
        if ($stmt->execute()) {
            return ['status' => 'success', 'message' => 'Visit recorded successfully'];
        } else {
            return ['status' => 'error', 'message' => 'Failed to record visit'];
        }
    }
    
    
    

    public function closeConnection()
    {
        $this->conn->close();
    }
}

?>