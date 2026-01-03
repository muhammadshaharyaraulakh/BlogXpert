<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

$response = [
    "status" => "error",
    "message" => "Failed to send message."
];

try {

    if (!isset($_SESSION['id'])) {
        throw new Exception("Unauthorized request.");
    }

    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($subject === "" || $message === "") {
        throw new Exception("Subject and message are required.");
    }

    $mail = new PHPMailer(true);

    // SMTP config (from config.php)
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASSWORD;
    $mail->SMTPSecure = SMTP_SECURE;
    $mail->Port       = SMTP_PORT;

    // Email setup
    $mail->setFrom(SMTP_USER, "Blog Contact Form");
    $mail->addAddress(SMTP_USER); // Admin email

    $mail->addReplyTo($_SESSION['email'], $_SESSION['name']);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = "
        <h3>New Message from Writer</h3>
        <p><strong>Name:</strong> {$_SESSION['name']}</p>
        <p><strong>Email:</strong> {$_SESSION['email']}</p>
        <hr>
        <p>{$message}</p>
    ";

    $mail->send();

    $response = [
        "status" => "success",
        "message" => "Message sent to admin successfully."
    ];

} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
exit;
