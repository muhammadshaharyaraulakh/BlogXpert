<?php

require __DIR__ . '/../../config/config.php';
postRequest(); 
blockAccess('user', 'writer','admin');
header("Content-Type: application/json");

$response = [
    "status" => "error",
    "message" => "Failed to delete admin.",
    "field" => "general"
];

try {
    $id = $_POST['adminId'] ?? 0;
    $image = $_POST['adminImage'] ?? null;

    if ($image) {
        $image_path = __DIR__ . "/../../userImages/" . $image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $delete = $connection->prepare("DELETE FROM user WHERE id = :id AND role='admin'");
    $delete->execute([':id' => $id]);

    if ($delete->rowCount() === 0) {
        throw new Exception("Admin not found or cannot be deleted.");
    }

    $response = [
        "status" => "success",
        "message" => "Admin deleted successfully.",
        "field" => "general"
    ];

} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
    $response["field"] = "general";
}

echo json_encode($response);
exit;