<?php
require __DIR__ . '/../../config/config.php';
postRequest();
blockAccess('user', 'writer','admin');
header("Content-Type: application/json");

$response = [
    "status" => "error",
    "message" => "Failed to delete writer.",
    "field" => "general"
];

try {
    $writer_id = $_POST['writerId'] ?? null;
    if (!$writer_id) throw new Exception("No Writer ID received.");

    $FetchUser = $connection->prepare("SELECT id, avatar FROM user WHERE id = :id");
    $FetchUser->execute([':id' => $writer_id]);
    $targetUser = $FetchUser->fetch(PDO::FETCH_ASSOC);

    if (!$targetUser) throw new Exception("User not found.");

    $stmtPosts = $connection->prepare("SELECT thumbnail FROM posts WHERE author_id = :id");
    $stmtPosts->execute([':id' => $writer_id]);
    $posts = $stmtPosts->fetchAll(PDO::FETCH_ASSOC);

    foreach ($posts as $post) {
        if (!empty($post['thumbnail'])) {
            $post_image_path = __DIR__ . "/../../images/" . $post['thumbnail'];
            if (file_exists($post_image_path)) unlink($post_image_path);
        }
    }

    $connection->prepare("DELETE FROM posts WHERE author_id = :id")->execute([':id' => $writer_id]);

    if (!empty($targetUser['avatar'])) {
        $avatar_path = __DIR__ . "/../../userImages/" . $targetUser['avatar'];
        if (file_exists($avatar_path)) unlink($avatar_path);
    }

    $connection->prepare("DELETE FROM user WHERE id = :id")->execute([':id' => $writer_id]);

    $response = [
        "status" => "success",
        "message" => "Writer deleted successfully.",
        "field" => "general"
    ];
} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
    $response["field"] = "general";
}

echo json_encode($response);
exit;