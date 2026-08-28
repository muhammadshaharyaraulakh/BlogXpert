<?php
require_once __DIR__ . "/../config/config.php";
header('Content-Type: application/json');
postRequest();
$response = ['status' => 'error', 'message' => 'Unexpected error occurred.'];

try {
    $post_id = $_POST['postId'] ?? null;
    $body = trim($_POST['body'] ?? '');
    $user_id = $_SESSION['id'];

    if (!$post_id || !$body) {
        throw new Exception("Post ID or comment body missing.");
    }

    $stmt = $connection->prepare("INSERT INTO comments (post_id, user_id, body) VALUES (:pid, :uid, :body)");
    $stmt->execute([
        ':pid' => $post_id,
        ':uid' => $user_id,
        ':body' => $body
    ]);

    $comment_id = $connection->lastInsertId();

    $fetchStmt = $connection->prepare("SELECT comments.*, user.first_name, user.avatar FROM comments JOIN user ON comments.user_id = user.id WHERE comments.id = :id");
    $fetchStmt->execute([':id' => $comment_id]);
    $newComment = $fetchStmt->fetch(PDO::FETCH_OBJ);

    $response = [
        'status' => 'success',
        'message' => 'Comment added!',
        'data' => $newComment
    ];
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
