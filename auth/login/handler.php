<?php
// Ensure NO whitespace before this opening tag
require_once __DIR__ . "/../../config/config.php";

// Set header immediately
header("Content-Type: application/json");

// Initialize response and field variable
$response = [
    "status" => "error",
    "message" => "Unexpected error occurred.",
    "field" => "general"
];
$field = "general"; 

try {
    // 1. Basic Validation
    $email = trim($_POST["email"] ?? '');
    $password = $_POST["password"] ?? '';

    if (empty($email)) {
        $field = "email";
        throw new Exception("Please enter your email.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $field = "email";
        throw new Exception("Invalid email format.");
    }
    if (empty($password)) {
        $field = "password";
        throw new Exception("Please enter your password.");
    }

    // 2. Database Check
    $statement = $connection->prepare(
        "SELECT id, username, first_name, email, password, role, avatar
         FROM user 
         WHERE email = :email 
         LIMIT 1"
    );
    $statement->execute([':email' => $email]);
    $user = $statement->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        $field = "email";
        throw new Exception("No account found with that email.");
    }

    // 3. Password Verification
    if (!password_verify($password, $user->password)) {
        $field = "password";
        throw new Exception("Incorrect password.");
    }

    // 4. Success - Set Session
    // Ensure session_start() is called in config.php
    session_regenerate_id(true);
    $_SESSION['id'] = $user->id;
    $_SESSION['username'] = $user->username;
    $_SESSION['name'] = $user->first_name;
    $_SESSION['avatar'] = $user->avatar;
    $_SESSION['email'] = $user->email;
    $_SESSION['role'] = $user->role; 

    // 5. Redirection Logic
    if ($user->role === 'admin' || $user->role === 'superadmin') {
        $redirect = "/admin/dashboard.php";
    } elseif ($user->role === 'writer') {
        $redirect = "/writer/dashboard.php";
    } else {
        $redirect = "/index.php";
    }

    $response = [
        "status" => "success",
        "message" => "Login successful!",
        "redirect" => $redirect
    ];

} catch (PDOException $e) {
    // Database specific errors
    $response["message"] = "Database connection failed.";
    $response["field"] = "general";
} catch (Exception $e) {
    // Validation/User errors
    $response["message"] = $e->getMessage();
    $response["field"] = $field;
}

// Single exit point
echo json_encode($response);
exit;