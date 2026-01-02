<?php 
require_once __DIR__ . "/../config/config.php";
?>
<!DOCTYPE html>
<html" lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Zen Blogs</title>
        <link rel="stylesheet" href="/assests/style.css">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,800;1,700&display=swap" rel="stylesheet">
    </head>

    <body>

        <nav>
            <div class="container nav__container">
                <a href="/index.php" class="nav__logo">Zen Blogs</a>
                <ul class="nav__items">
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/pages/blog.php">Blog</a></li>
                    <li><a href="/index.php#category__buttons">Categories</a></li>
                    <?php if(!empty($_SESSION['id'])): ?>
                    <li><a href="/pages/contact.html">Join as Author</a></li>
                    <?php endif; ?>
                    <?php if(empty($_SESSION['id'])): ?>
                    <li><a href="/auth/login/login.php">Login</a></li>
                    <?php endif; ?>
                    <?php if(!empty($_SESSION['id'])): ?>
                    <li class="nav__profile">
                        <div class="avatar">
                            <img src="/userImages/<?php echo htmlspecialchars($_SESSION['avatar']) ?>">
                        </div>
                        <ul>
                             <?php if($_SESSION['role']=="admin" || $_SESSION['role']=="superadmin"): ?>
                            <li><a href="/admin/dashboard.php">Dashboard</a></li>
                            <?php endif?>
                             <?php if($_SESSION['role']=="writer"): ?>
                            <li><a href="/writer/dashboard.php">Dashboard</a></li>
                            <?php endif?>
                            <li><a href=""><?php echo htmlspecialchars($_SESSION['name'])?></a></li>
                            <li><a href="/auth/logout.php">Logout</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>

                <button id="open__nav-btn"><i class="uil uil-bars"></i></button>
                <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
            </div>
        </nav>