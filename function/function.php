<?php
require __DIR__."/../config/config.php";

function render404() {
    http_response_code(404);
    header('Location: /404.php');
    exit(); 
}

if(!defined('SECURE_ACCESS')){
    render404();
}

function protectFile($file) {
    if (basename($file) == basename($_SERVER['PHP_SELF'])) {
        render404();
    }
}

function postRequest(){
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        render404();
    }
}

function getRequest(){
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        render404();
    }
}

function blockAccess(...$prohibited) {
    if (!isset($_SESSION['role']) || empty($_SESSION['role']) || in_array($_SESSION['role'], $prohibited)) {
        render404();
    }
}

function getAllCategories($connection) {
    $statement = $connection->prepare("SELECT * FROM categories");
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_OBJ);
}

function getPostsByStatus($connection, $status) {
    $statement = $connection->prepare("
        SELECT posts.*, 
        user.first_name, 
        user.last_name, 
        user.avatar,
        categories.title AS category_title
        FROM posts 
        JOIN user ON posts.author_id = user.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.status = :status 
        ORDER BY posts.created_at DESC
    ");
    $statement->execute([
        ':status' => $status
    ]);    
    return $statement->fetchAll(PDO::FETCH_OBJ);
}

function getRoles($connection,$role){
   $statement = $connection->prepare("SELECT * FROM user WHERE role = :role");
   $statement->execute([
    ':role' => $role
   ]);
   return $statement->fetchAll(PDO::FETCH_OBJ);
}

function createUser(PDO $connection, array $data, string $role, string $redirectPath = "") {
    $response = [
        "status" => "error",
        "message" => "Unexpected error occurred.",
        "field" => "general"
    ];

    try {
        $firstname = trim($data["firstname"] ?? '');
        $lastname  = trim($data["lastname"] ?? '');
        $username  = trim($data["username"] ?? '');
        $email     = trim($data["email"] ?? '');
        $password  = $data["password"] ?? '';
        $confirm   = $data["confirmpassword"] ?? '';
        $avatar = $_FILES['avatar'] ?? null;

        if (empty($firstname)) { $field = "firstname"; throw new Exception("First name is required."); }
        if (!preg_match("/^[a-zA-Z]+$/", $firstname)) { $field = "firstname"; throw new Exception("First name must contain letters only."); }

        if (empty($lastname)) { $field = "lastname"; throw new Exception("Last name is required."); }
        if (!preg_match("/^[a-zA-Z]+$/", $lastname)) { $field = "lastname"; throw new Exception("Last name must contain letters only."); }

        if (empty($username)) { $field = "username"; throw new Exception("Username is required."); }
        if (!preg_match("/^[a-z0-9]+$/", $username)) { $field = "username"; throw new Exception("Username must be lowercase and numbers only."); }

        if (empty($email)) { $field = "email"; throw new Exception("Email is required."); }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $field = "email"; throw new Exception("Invalid email format."); }

        if (empty($password)) { $field = "password"; throw new Exception("Password is required."); }
        if (strlen($password) < 8) { $field = "password"; throw new Exception("Password must be at least 8 characters."); }
        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/", $password)) { $field = "password"; throw new Exception("Password must contain Upper, Lower, Number & Special char."); }
        if ($password !== $confirm) { $field = "confirmpassword"; throw new Exception("Passwords do not match."); }

        $stmt = $connection->prepare("SELECT id, username, email FROM user WHERE username = :username OR email = :email");
        $stmt->execute([':username'=>$username, ':email'=>$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_OBJ);
        if ($existingUser) {
            if ($existingUser->email === $email) { $field = "email"; throw new Exception("Email already exists."); }
            if ($existingUser->username === $username) { $field = "username"; throw new Exception("Username already taken."); }
        }

        if (!$avatar || $avatar['error'] !== UPLOAD_ERR_OK) { $field="avatar"; throw new Exception("Please upload an image."); }
        if ($avatar['size'] > 2000000) { $field="avatar"; throw new Exception("Image size must be less than 2MB."); }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($avatar['tmp_name']);
        if ($mimeType !== 'image/png') { $field="avatar"; throw new Exception("Only PNG images are allowed."); }

        $upload_dir = __DIR__ . "/../userImages/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $image_name = $username . ".png";
        $target_path = $upload_dir . $image_name;

        if (!move_uploaded_file($avatar['tmp_name'], $target_path)) { $field="avatar"; throw new Exception("Failed to upload image."); }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $insert_stmt = $connection->prepare("
            INSERT INTO user (first_name, last_name, username, email, password, role, avatar)
            VALUES (:fname, :lname, :uname, :email, :pass, :role, :avatar)
        ");

        $insert_stmt->execute([
            ':fname'  => $firstname,
            ':lname'  => $lastname,
            ':uname'  => $username,
            ':email'  => $email,
            ':pass'   => $hashed_password,
            ':role'   => $role,
            ':avatar' => $image_name
        ]);

        $response = [
            "status" => "success",
            "message" => "User registered successfully!",
            "field" => "general",
            "redirect" => $redirectPath 
        ];

    } catch (Exception $e) {
        http_response_code(400);
        $response["message"] = $e->getMessage();
        $response["field"] = $field ?? "general";
        $response["redirect"] = ""; 
    }

    return $response;
}
