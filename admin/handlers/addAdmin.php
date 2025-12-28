<?php
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');
postRequest();
blockAccess('user','writer','admin');
$response = createUser($connection, $_POST, 'admin'); 
echo json_encode($response);
exit;