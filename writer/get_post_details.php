<?php
require __DIR__."/../config/config.php"; 

header("Content-Type: application/json");
getRequest();
blockAccess('user','superadmin','admin');
$response = [
    "status" => "error",
    "message" => "Unexpected error occurred.",
    "field" => "general"
];

try {
    if (isset($_GET['id'])) {
        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        $stmt = $connection->prepare("SELECT * FROM posts WHERE id = :id AND status = 'pending' LIMIT 1");
        $stmt->execute(['id' => $id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            $response = [
                "status" => "success",
                "data" => $post
            ];
        } else {
            $response["message"] = "Post not found or it is already approved.";
        }
    } else {
        $response["message"] = "No Post ID provided.";
    }

} catch (PDOException $e) {
    $response["message"] = "Database error: " . $e->getMessage();
}

echo json_encode($response);
exit;
