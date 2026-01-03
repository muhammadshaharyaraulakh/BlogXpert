<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header.php';
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $stmt = $connection->prepare("
    SELECT posts.*, categories.title AS category_title,
               user.first_name, user.last_name, user.avatar AS author_avatar
        FROM posts 
        JOIN user ON posts.author_id = user.id
        JOIN categories ON posts.category_id = categories.id
        WHERE posts.id = :postId AND posts.status = 'approved'
         LIMIT 1
    ");
    $stmt->execute([':postId' => $postId]);
    $post = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$post) {
        header('location: ./blog.php');
        die();
    }

    $commentStmt = $connection->prepare("
        SELECT comments.*, user.first_name, user.last_name, user.avatar 
        FROM comments
        JOIN user ON comments.user_id = user.id
        WHERE comments.post_id = :postId
        ORDER BY comments.created_at DESC
    ");
    $commentStmt->execute([':postId' => $postId]);
    $comments = $commentStmt->fetchAll(PDO::FETCH_OBJ);

} else {
    header('location: ./blog.php');
    die();
}
?>
<!-- Notifications -->
<div class="toast-container" id="toastContainer"></div>
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
        
        <p><?= nl2br(htmlspecialchars($post->para_1)) ?></p>
        <p><?= nl2br(htmlspecialchars($post->para_2)) ?></p>
        <p><?= nl2br(htmlspecialchars($post->para_3)) ?></p>

        <?php if (!empty($post->para_4)): ?>
            <p><?= nl2br(htmlspecialchars($post->para_4)) ?></p>
        <?php endif; ?>

        <div class="singlepost__thumbnail">
            <img src="/images/<?= $post->thumbnail ?>" alt="Post Thumbnail">
        </div>

        <?php if (!empty($post->para_5)): ?>
            <p><?= nl2br(htmlspecialchars($post->para_5)) ?></p>
        <?php endif; ?>

        <?php if (!empty($post->para_6)): ?>
            <p><?= nl2br(htmlspecialchars($post->para_6)) ?></p>
        <?php endif; ?>
    </div>

        <div class="interaction-bar">
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "user"): ?>
    
    <form action="/pages/likehandler.php" method="POST" class="like-form">
    <input type="hidden" name="post_id" value="<?= $post->id ?>">
    
    <button type="submit" class="like-btn <?= $hasLiked ? 'disabled-like' : '' ?>" <?= $hasLiked ? 'disabled' : '' ?>>
        <i class="uil uil-thumbs-up"></i>
        <span><?= $post->likes_count ?> Likes</span> 
    </button>
</form>

<?php else: ?>
    
    <div class="stats">
        <div class="like-btn">
            <i class="uil uil-thumbs-up"></i>
            <span><?= $post->likes_count ?> Likes</span> 
        </div>
    </div>

<?php endif; ?>

    <button id="toggle-comments-btn" class="comment-toggle-btn">
        <i class="uil uil-comment-alt-lines"></i>
        Show Comments
    </button>
</div>

<div class="comments-section" id="comments-section">
    
    <div class="comments-header">
        <h3>Comments (<?= count($comments) ?>)</h3>
        <?php if ($_SESSION['role']=="user"):?>
        <button id="open-comment-popup" class="comment-btn">Add Comment</button>
        <?php endif; ?>
    </div>

    <div class="comments-list">
        <?php if (count($comments) > 0): ?>
                <?php foreach ($comments as $comment): ?>
                    <article class="comment">
                        <div class="comment__avatar">
                            <img src="/userImages/<?= $comment->avatar ?>" alt="User">
                        </div>
                        <div class="comment__content">
                            <div class="comment__info">
                                <h5><?= htmlspecialchars($comment->first_name) ?></h5>
                                <?= date("M d, Y", strtotime($comment->created_at)) ?></small> 
                            </div>
                            <p class="comment__body">
                                <?= nl2br(htmlspecialchars($comment->body)) ?>
                            </p>
                            
                            <?php if (isset($_SESSION['id']) && $_SESSION['id'] == $comment->user_id): ?>
                                <form action="delete.php" method="POST" class="delete-comment-form">
                                    <input type="hidden" name="comment_id" value="<?= $comment->id ?>">
                                    <input type="hidden" name="post_id" value="<?= $post->id ?>">
                                    <button type="submit" class="delete-btn">
                                        <i class="uil uil-trash-alt"></i> Delete
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            
            <?php endif; ?>
    </div>
</div>

<div class="comment-popup" id="comment-popup">
    <div class="popup-content">
        <div class="popup-header">
            <h3>Leave a Reply</h3>
            <button id="close-popup-btn"><i class="uil uil-multiply"></i></button>
        </div>
        
        <form action="/pages/comment.php" method="POST">
            <input type="hidden" name="postId" value=
            "<?php echo $_GET['id'] ?>">
            <textarea name="body" rows="5" placeholder="Write your comment here" required></textarea>
            <button id="add-comment-btn" class="comment-btn">Submitt</button>
        </form>
    </div>
</div>
    </section>


<?php
require __DIR__."/../includes/footer.php";
?>