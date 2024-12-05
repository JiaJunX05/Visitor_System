<?php

include_once '../owner/OwnerController.php';

$name = isset($_GET['name']) ? $_GET['name'] : '';
$unit = isset($_GET['unit']) ? $_GET['unit'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

try {
    $ownerController = new OwnerController();
    $users = $ownerController->SearchOwner($name, $unit, $limit, $offset);
    $totalOwners = $ownerController->CountOwners($name, $unit);
    $totalPages = ceil($totalOwners / $limit);

    $response = array(
        'users' => $users,
        'totalPages' => $totalPages
    );

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (Exception $e) {
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(array('error' => $e->getMessage()));
}

?>