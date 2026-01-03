<?php
require __DIR__."/../config/config.php";
header("Content-Type: application/json");

$response = ["status" => "error", "message" => "Unexpected error.", "field" => "general"];
postRequest();
blockAccess('user','superadmin','admin');
try {
    $id = $_POST['postId'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $cat = $_POST['category_id'] ?? '';
    $p1 = trim($_POST['para_1'] ?? '');
    $p2 = trim($_POST['para_2'] ?? '');
    $p3 = trim($_POST['para_3'] ?? '');
    $p4 = $_POST['para_4'] ?? '';
    $p5 = $_POST['para_5'] ?? '';
    $p6 = $_POST['para_6'] ?? '';

    if (empty($title)) {
        throw new Exception("Title is required", 101); 
    }
    if (empty($cat)) {
        throw new Exception("Category is required", 102);
    }
    if (empty($p1) || empty($p2) || empty($p3)) {
        throw new Exception("First 3 paragraphs are mandatory", 103);
    }

    $stmt = $connection->prepare("SELECT thumbnail FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $old_post = $stmt->fetch(PDO::FETCH_ASSOC);
    $thumb_name = $old_post['thumbnail'];
    $upload_dir = __DIR__ . "/../images/";

    if (!empty($_FILES['thumbnail']['name'])) {
        $new_name = time() . '_' . $_FILES['thumbnail']['name'];
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_dir . $new_name)) {
            if (!empty($thumb_name) && file_exists($upload_dir . $thumb_name)) {
                unlink($upload_dir . $thumb_name);
            }
            $thumb_name = $new_name;
        }
    }


    $sql = "UPDATE posts SET title=?, category_id=?, para_1=?, para_2=?, para_3=?, para_4=?, para_5=?, para_6=?, thumbnail=? WHERE id=?";
    $update = $connection->prepare($sql);
    $update->execute([$title, $cat, $p1, $p2, $p3, $p4, $p5, $p6, $thumb_name, $id]);

    echo json_encode(["status" => "success", "message" => "Post updated!"]);

} catch (Exception $e) {

    $field = "general";
    if ($e->getCode() == 101) $field = "title";
    if ($e->getCode() == 102) $field = "category";
    if ($e->getCode() == 103) $field = "paragraphs";

    echo json_encode(["status" => "error", "message" => $e->getMessage(), "field" => $field]);
}