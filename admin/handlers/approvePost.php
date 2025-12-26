<?php
require __DIR__ . '/../../config/config.php';

postRequest();
blockAccess('user', 'writer');

header('Content-Type: application/json');

$response = [
    "status" => "error",
    "message" => "Unexpected error occurred.",
    "field" => "general"
];

try {
    $postId = filter_input(INPUT_POST, 'postId', FILTER_VALIDATE_INT);
    if (!$postId || $postId <= 0) {
        throw new Exception("Invalid Post ID.");
    }

    $statement = $connection->prepare("UPDATE posts SET status = 'approved' WHERE id = :id");
    $statement->execute(['id' => $postId]);

    if ($statement->rowCount() === 0) {
        throw new Exception("Post not found or already approved.");
    }

    $response = [
        "status" => "success",
        "message" => "Post approved successfully!"
    ];

} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
    $response["field"] = $field ?? "general";
}

echo json_encode($response);
exit;