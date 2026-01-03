<?php
require_once __DIR__ . "/../config/config.php";
header("Content-Type: application/json");
$response = [
    "status" => "error",
    "message" => "Unexpected error occurred.",
    "field" => "general"
];
postRequest();
blockAccess('user','superadmin','admin');
try {
    $title = trim($_POST['title'] ?? '');
    $category_id = $_POST['category_id'] ?? '';
    $para1 = trim($_POST['para_1'] ?? '');
    $para2 = trim($_POST['para_2'] ?? '');
    $para3 = trim($_POST['para_3'] ?? '');
    $para4 = trim($_POST['para_4'] ?? '');
    $para5 = trim($_POST['para_5'] ?? '');
    $para6 = trim($_POST['para_6'] ?? '');
    $thumbnail = $_FILES['thumbnail'] ?? null;

    if (empty($title)) {
        $field = "title";
        throw new Exception("Please enter a post title.");
    }

    $stmt = $connection->prepare("SELECT id FROM posts WHERE title = :title");
    $stmt->execute([':title' => $title]);
    if ($stmt->rowCount() > 0) {
        $field = "title";
        throw new Exception("A post with this title already exists.");
    }

    if (empty($category_id)) {
        $field = "category";
        throw new Exception("Please select a category.");
    }

    if (empty($para1) || empty($para2) || empty($para3)) {
        $field = "para_1";
        throw new Exception("Please enter at least the first three paragraphs.");
    }

    if (!$thumbnail || $thumbnail['error'] !== UPLOAD_ERR_OK) {
        $field = "thumbnail";
        throw new Exception("Please upload a thumbnail image.");
    }

    if ($thumbnail['size'] > 2000000) {
        $field = "thumbnail";
        throw new Exception("Image size must be less than 2MB.");
    }

    $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($thumbnail['tmp_name']);
    if (!in_array($mimeType, $allowed_types)) {
        $field = "thumbnail";
        throw new Exception("Only PNG and JPG images are allowed.");
    }

    $extension = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
    $unique_image_name = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
    $upload_dir = __DIR__ . "/../../images/";
    $target_path = $upload_dir . $unique_image_name;

    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    if (!move_uploaded_file($thumbnail['tmp_name'], $target_path)) {
        $field = "thumbnail";
        throw new Exception("Failed to upload image to server.");
    }

    $insert_query = "INSERT INTO posts (
        title, para_1, para_2, para_3, para_4, para_5, para_6,
        thumbnail, category_id, author_id, status, likes_count
    ) VALUES (
        :title, :p1, :p2, :p3, :p4, :p5, :p6,
        :thumbnail, :category_id, :author_id, 'pending', 0
    )";

    $stmt = $connection->prepare($insert_query);
    $stmt->execute([
        ':title'       => $title,
        ':p1'          => $para1,
        ':p2'          => $para2,
        ':p3'          => $para3,
        ':p4'          => $para4,
        ':p5'          => $para5,
        ':p6'          => $para6,
        ':thumbnail'   => $unique_image_name,
        ':category_id' => $category_id,
        ':author_id'   => $_SESSION['id']
    ]);

    $response = [
        "status"  => "success",
        "message" => "Post added successfully! Waiting for admin approval.",
        "redirect" => "dashboard.php"
    ];

} catch (Exception $e) {
    if (isset($target_path) && file_exists($target_path)) unlink($target_path);
    $response["message"] = $e->getMessage();
    $response["field"]   = $field ?? "general";

} catch (PDOException $e) {
    $response["message"] = "Database Error: " . $e->getMessage();
    $response["field"]   = "general";
}

echo json_encode($response);
exit;
