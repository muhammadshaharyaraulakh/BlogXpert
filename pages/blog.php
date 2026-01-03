<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/header.php";

// Fetch all categories
$categories = getAllCategories($connection);

// Get search query
$searchQuery = trim($_GET['query'] ?? '');

if ($searchQuery !== '') {
    $stmt = $connection->prepare("
        SELECT posts.*, categories.title AS category_title,
               user.first_name, user.last_name, user.avatar
        FROM posts
        JOIN user ON posts.author_id = user.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.status = 'approved'
          AND posts.title LIKE :search
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute([':search' => "%$searchQuery%"]);
    $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
    $stmt = $connection->prepare("
        SELECT posts.*, categories.title AS category_title,
               user.first_name, user.last_name, user.avatar
        FROM posts
        JOIN user ON posts.author_id = user.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.status = 'approved'
        ORDER BY posts.created_at DESC
    ");
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>


<!-- Search Bar -->
<section class="search__bar">
    <form action="/pages/blog.php" method="GET" class="container search__bar-container">
        <div>
            <i class="uil uil-search"></i>
            <input type="search" name="query" placeholder="Search by title" value="<?= htmlspecialchars($searchQuery) ?>">
            <button type="submit" class="btn">Go</button>
        </div>
    </form>
</section>

<!-- Posts Section -->
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
                <p>No posts found<?= $searchQuery ? ' for "' . htmlspecialchars($searchQuery) . '"' : '' ?>!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Category Buttons -->
<section class="category__buttons" id="category__buttons">
    <div class="container category__buttons-container">
        <?php foreach($categories as $category): ?>
            <a href="/pages/category-posts.php?id=<?= $category->id ?>" class="category__button">
                <?= htmlspecialchars($category->title) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php
require __DIR__ . "/../includes/footer.php";
?>
