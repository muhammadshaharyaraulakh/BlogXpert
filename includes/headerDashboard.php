<?php 
require __DIR__."/../config/config.php";
protectFile(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="/assests/css/user.css">

</head>

<body>

    <header>
        <div class="header__user">
            <button id="sidebar-toggle"><i class="uil uil-bars"></i></button>

            <div class="header__avatar">
                <img src="/userImages/<?= $_SESSION['avatar'] ?>?>" alt="Admin">
            </div>
            <div class="header__welcome">
                <h3><?= $_SESSION['name'] ?></h3>
                <h4>Administrator Panel </h4> 
            </div>
        </div>
    </header>
        <div class="main-layout sidebar-active" id="main-layout">
    
        <aside>
            <ul>
                <li>
                    <a href="#" onclick="showSection('pending')" id="nav-pending" class="active">
                        <i class="uil uil-clock"></i>
                        <h5>Pending Posts</h5>
                    </a>
                </li>
                
                <li>
                    <a href="#" onclick="showSection('all-posts')" id="nav-all-posts">
                        <i class="uil uil-files-landscapes"></i>
                        <h5>All Posts</h5>
                    </a>
                </li>

                <li>
                    <a href="#" onclick="showSection('categories')" id="nav-categories">
                        <i class="uil uil-tag-alt"></i>
                        <h5>Categories</h5>
                    </a>
                </li>
                
                <?php if($_SESSION['role'] == "superadmin") :?>
    
    <li>
        <a href="#" id="nav-admins" onclick="showSection('admins')">
            <i class="uil uil-users-alt"></i>
            <h5>Manage Admins</h5>
        </a>
    </li>

    <li>
        <a href="#" id="nav-writer" onclick="showSection('writer')">
             <i class="uil uil-users-alt"></i> <h5>Manage Writers</h5>
        </a>
    </li>

<?php endif; ?>
                <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem 0;">

                <li>
                    <a href="/index.php"><i class="uil uil-home"></i>
                        <h5>Home</h5>
                    </a>
                </li>
                <li>
                    <a href="/auth/logout.php"><i class="uil uil-signout"></i>
                        <h5>Logout</h5>
                    </a>
                </li>
            </ul>
        </aside>