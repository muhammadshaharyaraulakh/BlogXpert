<?php
require_once __DIR__ . "/../config/config.php";
header('Content-Type: application/json');
postRequest();
$response = [
    'status' => 'error',
    'message' => 'Unexpected error occurred.'
];

if (!isset($_SESSION['id'])) {
    $response['message'] = 'You must be logged in.';
    echo json_encode($response);
    exit;
}

$post_id = $_POST['post_id'] ?? null;
$comment_id = $_POST['comment_id'] ?? null;
$user_id = $_SESSION['id'];

if ($post_id && $comment_id) {
    try {
        $stmt = $connection->prepare("DELETE FROM comments WHERE id=:id AND post_id=:pid AND user_id=:userId");
        $stmt->execute([
            ':id' => $comment_id,
            ':pid' => $post_id,
            ':userId' => $user_id
        ]);

        if ($stmt->rowCount() > 0) {
            $response = [
                'status' => 'success',
                'message' => 'Comment deleted successfully!',
                'comment_id' => $comment_id
            ];
        } else {
            $response['message'] = 'You cannot delete this comment.';
        }

    } catch (PDOException $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
exit;
