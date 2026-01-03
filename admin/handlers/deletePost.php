<?php
require __DIR__ . '/../../config/config.php';

postRequest();
blockAccess('user');

header('Content-Type: application/json');

$response = [
    "status" => "error",
    "message" => "Failed to delete post.",
    "field" => "general" 
];

try {
    $postId = filter_input(INPUT_POST, 'postId', FILTER_VALIDATE_INT);

    if (!$postId || $postId <= 0) {
        throw new Exception("Invalid Post ID.");
    }

    $statement = $connection->prepare("SELECT thumbnail FROM posts WHERE id = :id LIMIT 1");
    $statement->execute(['id' => $postId]);
    $post = $statement->fetch(PDO::FETCH_OBJ);

    if (!$post) {
        throw new Exception("Post not found or already deleted");
    }

    if (!empty($post->thumbnail)) {
        $image_path = __DIR__ . '/../../images/' . $post->thumbnail;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $delete = $connection->prepare("DELETE FROM posts WHERE id = :id");    
    if ($delete->execute(['id' => $postId])) {
        $response = [
            "status" => "success", 
            "message" => "Post deleted successfully"
        ];
    } else {
        throw new Exception("Database failed to delete record.");
    }

} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
    $response["field"] = $field ?? "general";
}

echo json_encode($response);
exit;
