<?php
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
postRequest();
$response = createUser($connection, $_POST, 'user', '/auth/login/login.php');
echo json_encode($response);
exit;