<?php

include_once("../include/db.php");

// 接收前端发送的 JSON 数据
$input = json_decode(file_get_contents('php://input'), true);
$scannedData = $input['scannedData'] ?? '';

// 验证 QR 码和访客信息
$stmt = $conn->prepare("
   SELECT v.*, v.name AS visitor_name, q.generator_at, q.expres_at, u.name AS owner_name
   FROM visitors AS v
   INNER JOIN qr_codes AS q ON v.visitor_code = q.qr_code
   INNER JOIN users AS u ON v.owner_id = u.id
   WHERE v.visitor_code = ?
");

$stmt->bind_param("s", $scannedData);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 只需调用一次 fetch_assoc 获取数据
    $visitor = $result->fetch_assoc();

    $expiration_time = strtotime($visitor['expres_at']); // 将时间转换为时间戳
    $visit_date = strtotime($visitor['visit_date']); // 将时间转换为时间戳
    $current_time = time();

    if ($current_time > $expiration_time) {
        echo json_encode(['status' => 'error', 'message' => 'QR Code Expired']);
        exit;
    } elseif ($visit_date > $current_time) {
        echo json_encode(['status' => 'error', 'message' => 'Visit date not reached yet!']);
        exit;
    } else {
        // 验证成功，返回访客信息
        echo json_encode([
            'status' => 'success',
            'visitor' => ['name' => $visitor['visitor_name']],
            'owner' => ['owner_name' => $visitor['owner_name']],
            'message' => "Visitor Valid: {$visitor['visitor_name']}"
        ]);
    }
} else {
    // 验证失败
    echo json_encode(['status' => 'error', 'message' => 'QR Code Invalid']);
}

$stmt->close();
$conn->close();
?>
