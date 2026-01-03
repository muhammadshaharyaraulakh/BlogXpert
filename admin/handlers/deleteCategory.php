<?php
require __DIR__ . '/../../config/config.php';
postRequest();
blockAccess('user', 'writer');

header('Content-Type: application/json');

$response = [
    "status" => "error",
    "message" => "Failed to delete category.",
    "field" => "general"
];

try {
    $category_id = $_POST['id'] ?? null;

    if (empty($category_id) || !filter_var($category_id, FILTER_VALIDATE_INT)) {
        throw new Exception("Invalid Category ID.");
    }

    $AllPosts = $connection->prepare("SELECT thumbnail FROM posts WHERE category_id = :id");
    $AllPosts->execute([':id' => $category_id]);
    $posts = $AllPosts->fetchAll(PDO::FETCH_OBJ);

    foreach ($posts as $post) {
        if (!empty($post->thumbnail)) {
            $image_path = __DIR__ . "/../../images/" . $post->thumbnail;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }

    $deletePosts = $connection->prepare("DELETE FROM posts WHERE category_id = :id");
    $deletePosts->execute([':id' => $category_id]);

    $deleteCategory = $connection->prepare("DELETE FROM categories WHERE id = :id");
    $deleteCategory->execute([':id' => $category_id]);

    if ($deleteCategory->rowCount() === 0) {
        throw new Exception("Category not found or already deleted.");
    }

    $response = [
        "status" => "success",
        "message" => "Category and related posts deleted successfully!"
    ];

} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
exit;