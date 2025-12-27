<?php
require __DIR__ . '/../../config/config.php';
postRequest();
blockAccess('user','writer');

header('Content-Type: application/json');

$response = [
    "status" => "error",
    "message" => "Unexpected error.",
    "field" => "general"
];

try {
    $title = trim($_POST['title'] ?? '');

    if (empty($title)) {
        $field = "title";
        throw new Exception("Title is required.");
    }

    if (!preg_match('/^[A-Za-z]+$/', $title)) {
        $field = "title";
        throw new Exception("Title must contain only letters");
    }

    $statement = $connection->prepare("SELECT id FROM categories WHERE title = :title");
    $statement->execute(['title' => $title]);

    if ($statement->rowCount() > 0) {
        $field = "title";
        throw new Exception("Category already exists.");
    }

    $insert = $connection->prepare("INSERT INTO categories (title) VALUES (:title)");
    if ($insert->execute(['title' => $title])) {
        $response = [
            "status" => "success",
            "message" => "Category added successfully!"
        ];
    } else {
        throw new Exception("Database failed to insert.");
    }

} catch (Exception $e) {
    http_response_code(400);
    $response["message"] = $e->getMessage();
    $response["field"] = $field ?? "general";
}

echo json_encode($response);
exit;