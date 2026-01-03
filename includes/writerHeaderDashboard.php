<?php 
require_once __DIR__."/../config/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writer Dashboard</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="/assests/css/user.css">
    <style>


    </style>
</head>

<body>

    <header>
        <div class="header__user">
            <button id="sidebar-toggle"><i class="uil uil-bars"></i></button>

            <div class="header__avatar">
                <img src="/userImages/<?php echo $_SESSION['avatar'] ?>" alt="User">
            </div>
            <div class="header__welcome">
                <h3> <?= $_SESSION['name'] ?></h3>
                <h4>Writer Dashboard</h4>
            </div>
        </div>
    </header>

    <div class="main-layout sidebar-active" id="main-layout">

        <aside>
            <ul>
                <li>
                    <a href="#" onclick="showSection('manage')" id="nav-manage" class="active">
                        <i class="uil uil-layer-group"></i>
                        <h5>Manage Pending Posts</h5>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showSection('add')" id="nav-add">
                        <i class="uil uil-plus-circle"></i>
                        <h5>Add New Post</h5>
                    </a>
                </li>
               
                <li>
                    <a href="#" onclick="showSection('blog')" id="nav-blog">
                        <i class="uil uil-document-layout-left"></i>
                        <h5>My Blogs</h5>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="showSection('contact')" id="nav-contact">
                        <i class="uil uil-envelope"></i>
                        <h5>Contact Admin</h5>
                    </a>
                </li>


                <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">

                <li><a href="/index.php"><i class="uil uil-home"></i>
                        <h5>Home</h5>
                    </a></li>
                <li><a href="/auth/logout.php"><i class="uil uil-signout"></i>
                        <h5>Logout</h5>
                    </a></li>
            </ul>
        </aside>