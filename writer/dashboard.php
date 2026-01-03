<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/writerHeaderDashboard.php";
//Checking User
blockAccess('user', 'admin', 'superadmin');
//Fetching Categories
$categories = getAllCategories($connection);
//fetching unapproved posts for that writer
$postPending = $connection->prepare("SELECT * FROM posts WHERE status = 'pending' AND author_id = :id");
$postPending->execute(['id' => $_SESSION['id']]);
$posts = $postPending->fetchAll(PDO::FETCH_OBJ);
//fetching approved posts for that writer
$postApproved = $connection->prepare("SELECT 
    p.id, 
    p.title, 
    p.status, 
    p.likes_count, 
    COUNT(c.id) AS comment_count
FROM posts p
LEFT JOIN comments c ON p.id = c.post_id
WHERE p.status = 'approved' AND p.author_id = :id
GROUP BY p.id");
$postApproved->execute(['id' => $_SESSION['id']]);
$approvedPosts = $postApproved->fetchAll(PDO::FETCH_OBJ);

?>

<!-- Notifications -->
<div class="toast-container" id="toastContainer"></div>
<main class="content">

    <section id="view-manage">
        <div class="dashboard__header">
            <h2>Manage Pending Posts</h2>
            <button onclick="showSection('add')" class="btn">Add New Post</button>
        </div>

        <div class="posts-container">
            <?php if (empty($posts)) : ?>
                <div class="alert__message error">No posts found.</div>
            <?php else : ?>

                <?php foreach ($posts as $post) : ?>
                    <article class="post-card">
                        <div class="post-info">
                            <h3 class="post-title"><?= htmlspecialchars($post->title) ?></h3>

                            <?php
                            $statusClass = ($post->status === 'approved') ? 'success' : 'pending';
                            ?>
                            <span class="status-badge <?= $statusClass ?>">
                                <?= htmlspecialchars($post->status) ?>
                            </span>
                        </div>

                        <div class="post-actions">
                            <button class="btn primary" title="View"><a href="/admin/adminPages/view.php?id=<?= $post->id ?>">view</a></button>


                            <button type="button"
                                onclick="prepareEdit(<?= $post->id ?>)"
                                class="icon-btn edit"
                                title="Edit">
                                <i class="uil uil-edit"></i>
                            </button>


                            <form class="deletePostForm" action="/admin/handlers/deletePost.php">
                                <input type="hidden" name="postId" value="<?= $post->id ?>">
                                <button class="icon-btn delete" title="Delete"><i class="uil uil-trash-alt"></i></button>
                            </form>

                        </div>
                    </article>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </section>
    <section id="view-add" class="hidden">
        <div class="container form__section-container">
            <h2>Add New Post</h2>

            <form id="addPostForm" enctype="multipart/form-data" novalidate>

                <input type="text" name="title" placeholder="Title">
                <div class="alert__message error title_error"></div>

                <select name="category_id">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>"><?= $category->title ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="alert__message error category_error"></div>

                <div class="paragraph-grid">
                    <textarea name="para_1" rows="5" placeholder="Paragraph 1"></textarea>
                    <textarea name="para_2" rows="5" placeholder="Paragraph 2"></textarea>
                    <textarea name="para_3" rows="5" placeholder="Paragraph 3"></textarea>
                    <textarea name="para_4" rows="5" placeholder="Paragraph (Optional)"></textarea>
                    <textarea name="para_5" rows="5" placeholder="Paragraph (Optional)"></textarea>
                    <textarea name="para_6" rows="5" placeholder="Paragraph (Optional)"></textarea>
                </div>
                <div class="alert__message error paragraphs_error"></div>

                <div class="form__control">
                    <label>Thumbnail</label>
                    <input type="file" name="thumbnail">
                </div>
                <div class="alert__message error thumbnail_error"></div>

                <button type="submit" class="btn">Publish Post</button>
                <button type="button" onclick="showSection('manage')" class="btn danger">Cancel</button>

                <div class="alert__message error general_error"></div>
            </form>
        </div>
    </section>

    <section id="view-edit" class="hidden">
        <div class="container form__section-container">
            <h2>Update Post</h2>

            <form id="Update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="postId">
                <input type="text" name="title" placeholder="Title">
                <div class="alert__message error title_error"></div>

                <select name="category_id">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category->id ?>"><?= $category->title ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="alert__message error category_error"></div>

                <div class="paragraph-grid">
                    <textarea name="para_1" rows="5" placeholder="Paragraph 1"></textarea>
                    <textarea name="para_2" rows="5" placeholder="Paragraph 2"></textarea>
                    <textarea name="para_3" rows="5" placeholder="Paragraph 3"></textarea>
                    <textarea name="para_4" rows="5" placeholder="Paragraph (Optional)"></textarea>
                    <textarea name="para_5" rows="5" placeholder="Paragraph (Optional)"></textarea>
                    <textarea name="para_6" rows="5" placeholder="Paragraph (Optional)"></textarea>
                </div>
                <div class="alert__message error paragraphs_error"></div>

                <div class="form__control">
                    <label>Thumbnail</label>
                    <input type="file" name="thumbnail">
                </div>
                <div class="alert__message error thumbnail_error"></div>

                <button type="submit" class="btn">Publish Post</button>
                <button type="button" onclick="showSection('manage')" class="btn danger">Cancel</button>

                <div class="alert__message error general_error"></div>
            </form>
        </div>
    </section>
    <section id="view-blog" class="hidden">

        <div class="dashboard__header">
            <h2>Your Approved Blogs</h2>
            <button onclick="showSection('add')" class="btn">Add New Post</button>
        </div>

        <div class="posts-container">

            <?php if (empty($approvedPosts)) : ?>
                <div class="alert__message error">No posts found.</div>
            <?php else : ?>

                <?php foreach ($approvedPosts as $post) : ?>
                    <article class="post-card">

                        <div class="post-info">
                            <h3 class="post-title"><?= htmlspecialchars($post->title) ?></h3>
                            <span class="status-badge success"><?= $post->status ?></span>

                            <div class="post-stats">
                                <span><i class="uil uil-thumbs-up"></i> <?= $post->likes_count ?? 0 ?></span>
                                <span><i class="uil uil-comment-dots"></i> <?= $post->comment_count ?? 0 ?></span>
                            </div>
                        </div>

                        <div class="post-actions">
                            <a href="/pages/post.php?id=<?= $post->id ?>" class="btn btn-primary">
                                View Post
                            </a>
                        </div>

                    </article>
                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </section>
    <section id="view-contact" class="hidden">
        <div class="container form__section-container">
            <h2>Contact Admin</h2>
            <form method="POST">
                <input type="text" name="subject" placeholder="Subject">
                <div class="paragraph-grid">
                    <textarea name="message" rows="5" placeholder="Write your query..."></textarea>
                </div>
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
    </section>

</main>
</div>
<?php 
require __DIR__."/../includes/footer.php";
?>