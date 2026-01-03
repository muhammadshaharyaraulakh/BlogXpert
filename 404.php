<?php 
require __DIR__."/config/config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found</title>
    <link rel="stylesheet" href="/assests/css/user.css">
    <link rel="stylesheet" href="/assests/css/404.css">
</head>
<body>

    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>

    <div class="error-container">
        <h1 class="error-code">404</h1>
        <h2>Oops! Page Not Found</h2>
        <p class="error-message">
            The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>
        <a href="index.php" class="btn primary">Return to Homepage</a>
    </div>

</body>
</html>