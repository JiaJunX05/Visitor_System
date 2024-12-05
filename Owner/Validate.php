<?php

include_once("VerifyController.php");

// 接收前端发送的 JSON 数据
$input = json_decode(file_get_contents('php://input'), true);
$scannedData = $input['scannedData'] ?? '';

try {
    $controller = new VerifyController();
    $response = $controller->validateQRCode($scannedData);

    // If the QR code is validated successfully, insert the visit
    if ($response['status'] === 'success') {
        // Prepare data for inserting visit
        $visitorId = $response['visitor']['id'];
        $ownerId = $response['owner']['id'];
        $visitDate = date("Y-m-d H:i:s"); // Current timestamp for visit
        $status = 'checked_in'; // You can set a status like 'active', 'completed', etc.

        // Insert visit record
        $insertResponse = $controller->insertVisit($visitorId, $ownerId, $visitDate, $status);
        $response['visit'] = $insertResponse; // Append visit insert response
    }

    $controller->closeConnection();
    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}

?>