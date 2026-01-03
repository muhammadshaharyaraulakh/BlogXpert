<?php
require_once __DIR__ . "/../config/config.php";
header("Content-Type: application/json");
postRequest();
$response = [
    "status" => "error",
    "message" => "Unexpected error occurred."
];

try {


    $post_id = $_POST['post_id'] ?? null;
    $user_id = $_SESSION['id'];

    if (!$post_id){
    header("Location: /404.php");
    exit;
    } 

    $checkStmt = $connection->prepare("SELECT id FROM post_likes WHERE post_id = :pid AND user_id = :uid");
    $checkStmt->execute([':pid' => $post_id, ':uid' => $user_id]);

    if ($checkStmt->rowCount() > 0) {
        throw new Exception("You have already liked this post.");
    }

    $insertStmt = $connection->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (:pid, :uid)");
    $insertStmt->execute([':pid' => $post_id, ':uid' => $user_id]);

    $updateStmt = $connection->prepare("UPDATE posts SET likes_count = likes_count + 1 WHERE id = :pid");
    $updateStmt->execute([':pid' => $post_id]);

    $connection->commit();

    $countStmt = $connection->prepare("SELECT likes_count FROM posts WHERE id = :pid");
    $countStmt->execute([':pid' => $post_id]);
    $newCount = $countStmt->fetchColumn();

    $response = [
        "status" => "success",
        "likes" => $newCount,
        "message" => "Liked!"
    ];

} catch (Exception $e) {
    if ($connection->inTransaction()) {
        $connection->rollBack();
    }
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;