<?php
require __DIR__ . "/config/config.php";
require __DIR__ . "/includes/header.php";
//Fetching Categories
$categories = getAllCategories($connection);
//Fetching Approved posts
$posts = getPostsByStatus($connection, 'approved');
?>
<section class="featured">
    <div class="container featured__container">
        <div class="post__thumbnail">
            <img src="./images/Hero.png">
        </div>
        <div class="post__info">
            <a href="category-posts.html" class="category__button">Global News</a>
            <h2 class="post__info"><a href="post.html">Analyzing Global Markets And Trends</a></h2>
            <p class="post__body">
                There's significant value within reading the news updates on the website, and taking the time to understand the nuances of policy. From the domestic reports to the global agreements, it's a resource that will shape a better understanding. That's why we're committed to a diligent inquiry across the entire globe to uncover some of the most pressing issues.
            </p>
        </div>
    </div>
</section>
<!-- All Posts -->
<section class="posts">

    <div class="container posts__container">

        <?php if (count($posts) > 0): ?>
           <?php foreach (array_slice($posts, 0, 9) as $post): ?>

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
<!-- All Categories -->
<section class="category__buttons" id="category__buttons">
    <div class="container category__buttons-container">
        <?php foreach ($categories as $category): ?>
            <a href="/pages/category-posts.php?id=<?= $category->id ?>" class="category__button">
                <?= $category->title ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php
require __DIR__ . "/includes/footer.php";
?>