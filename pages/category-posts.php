<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/header.php";

// 1. FETCH ALL CATEGORIES (For the buttons at the bottom)
$getCategories = $connection->prepare("SELECT * FROM categories");
$getCategories->execute();
$allCategories = $getCategories->fetchAll(PDO::FETCH_OBJ);

// 2. CHECK FOR ID & FETCH POSTS
if (isset($_GET["id"])) {
    $id = $_GET['id'];

    // Get Category Name
    $catStmt = $connection->prepare("SELECT title FROM categories WHERE id = :id");
    $catStmt->execute([':id' => $id]);
    $currentCategory = $catStmt->fetch(PDO::FETCH_OBJ);

    // If category doesn't exist, redirect or handle error
    if (!$currentCategory) {
        header("Location: /index.php");
        die();
    }

    // Get Posts (JOIN with User table to get Author details)
$postStmt = $connection->prepare("
    SELECT posts.*, 
           user.first_name, 
           user.last_name, 
           user.avatar, 
           categories.title AS category_title
    FROM posts 
    JOIN user ON posts.author_id = user.id 
    JOIN categories ON posts.category_id = categories.id
    WHERE posts.category_id = :id AND posts.status = 'approved'
    ORDER BY posts.created_at DESC
");

$postStmt->execute([':id' => $id]);
$posts = $postStmt->fetchAll(PDO::FETCH_OBJ);

} else {
    header("Location: /index.php");
    die();
}
?>

    <header class="category__title">
        <h2><?php echo $currentCategory->title ?></h2>
    </header>

<section class="posts">

 <div class="container posts__container">
            
            <?php if (count($posts) > 0): ?>
                <?php foreach ($posts as $post): ?>
                
                <article class="post">
                    <div class="post__thumbnail">
                        <img src="/images/<?= htmlspecialchars($post->thumbnail) ?>" alt="Post Thumbnail">
                    </div>
                    
                    <div class="post__info">
                        <a href="/pages/category-posts.php?id=<?= $post->category_id ?>" class="category__button">
    <?= htmlspecialchars($post->category_title) ?>
</a>
                        
                        <h3 class="post__title">
                            <a href="/pages/post.php?id=<?= $post->id ?>">
                                <?= htmlspecialchars($post->title) ?>
                            </a>
                        </h3>
                        
                        <p class="post__body">
                            <?= substr(strip_tags($post->para_1), 0, 150) ?>...
                        </p>
            
                        <div class="post__author">
                            <div class="post__author-avatar">
                                <img src="/userImages/<?= htmlspecialchars($post->avatar) ?>" alt="Author Avatar">
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= htmlspecialchars($post->first_name . " " . $post->last_name) ?></h5>
                                <small>
                                    <?= date("M d, Y - H:i", strtotime($post->created_at)) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </article>
                
                <?php endforeach; ?>
            
            <?php else: ?>
                <div class="alert__message error lg">
                    <p>No posts!</p>
                </div>
            <?php endif; ?>

        </div>

</section>


    <section class="category__buttons">
        <div class="container category__buttons-container">
            <?php foreach ($allCategories as $category): ?>
                <a href="/pages/category-posts.php?id=<?= $category->id ?>" class="category__button">
                    <?= htmlspecialchars($category->title) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

<?php 
// Corrected: Include footer here, not header
require __DIR__ . "/../includes/footer.php"; 
?>