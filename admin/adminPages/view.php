<?php
require __DIR__ . "/../../config/config.php";
require __DIR__ . "/../../includes/header.php";
//Checking User
blockAccess('user');
//Request Check
getRequest();
//Fetch Data
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    if(empty($postId)){
        render404();
    }
    $statment = $connection->prepare("
    SELECT posts.*, categories.title AS category_title,
               user.first_name, user.last_name, user.avatar AS author_avatar
        FROM posts 
        JOIN user ON posts.author_id = user.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.id = :postId 
         LIMIT 1
    ");
    $statment->execute([':postId' => $postId]);
    $post = $statment->fetch(PDO::FETCH_OBJ);
}else{
    header('Location: /404.php');
    die();
}
?>

<section class="singlepost">
    <div class="singlepost__container">
        <h2><?= htmlspecialchars($post->title) ?></h2>
        <div class="post__author">
            <div class="post__author-avatar">
                <img src="/userImages/<?= $post->author_avatar ?>" alt="Author">
            </div>
            <div class="post__author-info">
                <h5>By: <?= htmlspecialchars($post->first_name . " " . $post->last_name) ?></h5>
                <small>
                    <?= date("M d, Y - H:i", strtotime($post->created_at)) ?>
                    | <?= htmlspecialchars($post->category_title) ?>
                </small>
            </div>
        </div>
        <p><?= htmlspecialchars($post->para_1) ?></p>
        <p><?= htmlspecialchars($post->para_2) ?></p>
        <p><?= htmlspecialchars($post->para_3) ?></p>
        <?php if (!empty($post->para_4)): ?>
            <p><?= htmlspecialchars($post->para_4) ?></p>
        <?php endif; ?>
        <?php if (!empty($post->para_5)): ?>
            <p><?= htmlspecialchars($post->para_5) ?></p>
        <?php endif; ?>
        <?php if (!empty($post->para_6)): ?>
            <p><?= htmlspecialchars($post->para_6) ?></p>
        <?php endif; ?>
        <div class="singlepost__thumbnail">
            <img src="/images/<?= $post->thumbnail ?>" alt="Post Thumbnail">
        </div>
        <?php if (!empty($post->para_5)): ?>
            <p><?=htmlspecialchars($post->para_5) ?></p>
        <?php endif; ?>
        <?php if (!empty($post->para_6)): ?>
            <p><?=htmlspecialchars($post->para_6)?></p>
        <?php endif; ?>
    </div>
    <?php if($_SESSION['role']=='admin'):?>
    <button class="btn danger"><a href="/admin/dashboard.php">Home</a></button>
    <?php elseif($_SESSION['role']=='writer'): ?>
         <button class="btn danger"><a href="/writer/dashboard.php">Home</a></button>
         <?php endif; ?>
</section>