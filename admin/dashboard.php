<?php
require __DIR__ . "/../config/config.php";
require __DIR__ . "/../includes/headerDashboard.php";
//Checking User
blockAccess('user', 'writer');
//Fetching Categories
$categories = getAllCategories($connection);
//Fetching Pending posts
$posts = getPostsByStatus($connection, 'pending');
//Fetching Approved posts
$approvePost = getPostsByStatus($connection, 'approved');
//Fetching Roles Data
$adminList = getRoles($connection, 'admin');
$writerList = getRoles($connection, 'writer');
?>
<!-- Notifications -->
<div class="toast-container" id="toastContainer"></div>
<main class="content">
    <!-- Showing all Pending Posts -->
    <section id="view-pending">
        <div class="dashboard__header">
            <h2>Pending Posts</h2>
        </div>
        <div class="posts-container">
            <?php foreach ($posts as $post): ?>
                <article class="post-card pending-card">
                    <div class="post-info">
                        <h3 class="post-title"><?= $post->title ?></h3>
                        <p>By: <?= $post->first_name . " " . $post->last_name ?>
                            <span class="status-badge pending"><?= $post->status ?></span>
                            <span class="status-badge pending"><?= $post->category_title ?></span>
                        </p>
                    </div>
                    <div class="post-actions">
                        <button class="btn" title="View"><a href="/admin/adminPages/view.php?id=<?= $post->id ?>">view</a></button>
                        <form action="/admin/handlers/approvePost.php" class="approve-post">
                            <input type="hidden" name="postId" value="<?= $post->id ?>">
                            <button class="btn primary" title="Approve" type="submit">Approve</button>
                        </form>
                        <form class="deletePostForm" action="/admin/handlers/deletePost.php">
                            <input type="hidden" name="postId" value="<?= $post->id ?>">
                            <button class="icon-btn delete" title="Delete"><i class="uil uil-trash-alt"></i></button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <!-- View All Approved Posts with filterable categories -->
    <section id="view-all-posts" class="hidden">
        <div class="dashboard__header">
            <h2>All Posts</h2>
        </div>

        <div class="filter-container">
            <button class="btn outline active category-filter-btn" data-category="all">All</button>

            <?php foreach ($categories as $category): ?>
                <button class="btn outline category-filter-btn" data-category="<?= $category->title ?>">
                    <?= $category->title ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="posts-container">
            <?php foreach ($approvePost as $post): ?>
                <article class="post-card filterable-post" data-category="<?= $post->category_title ?>">
                    <div class="post-info">
                        <h3 class="post-title"><?= $post->title ?></h3>
                        <p>By: <?= $post->first_name . " " . $post->last_name ?>
                            <span class="status-badge pending"><?= $post->category_title ?></span>
                        </p>
                    </div>
                    <div class="post-actions">
                        <button class="btn" title="View"><a href="/admin/adminPages/view.php?id=<?= $post->id ?>">view</a></button>
                        <form class="deletePostForm" action="/admin/handlers/deletePost.php">
                            <input type="hidden" name="postId" value="<?= $post->id ?>">
                            <button class="icon-btn delete" title="Delete"><i class="uil uil-trash-alt"></i></button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
    <!-- Add and Delete categories -->
    <section id="view-categories" class="hidden">
        <div class="dashboard__header">
            <h2>Manage Categories</h2>
        </div>

        <div class="paragraph-grid">

            <div class="form__section-container" style="padding: 1.5rem; width: 100%;">

                <form id="addCategoryForm" action="/admin/handlers/addCategory.php">
                    <input type="text" placeholder="Category Title" name="title">

                    <div class="alert__message error title_error"></div>

                    <button type="submit" class="btn">Add Category</button>

                    <div class="alert__message error category_global_error"></div>
                </form>
            </div>

            <div class="posts-container">
                <?php foreach ($categories as $category): ?>
                    <article class="post-card category-Card" style="padding: 1rem;">
                        <div class="post-info">
                            <h3 class="post-title" style="font-size: 1rem;"><?= htmlspecialchars($category->title) ?></h3>
                        </div>
                        <div class="post-actions">
                            <form class="deleteCategoryForm" action="/admin/handlers/deleteCategory.php">
                                <input type="hidden" name="id" value="<?= $category->id ?>">
                                <button class="icon-btn delete"><i class="uil uil-trash-alt"></i></button>
                            </form>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- Create and Delete Admin -->
    <section id="view-admins" class="hidden">

        <?php if ($_SESSION['role'] == "superadmin") : ?>
            <div class="dashboard__header">
                <h2>Manage Admins</h2>
            </div>

            <div class="paragraph-grid">
                <div class="form__section-container" style="padding: 1.5rem; width: 100%;">
                    <h4>Add New Admin</h4>

                    <form enctype="multipart/form-data" id="adminRegistration" action="/admin/handlers/addAdmin.php" method="post">

                        <input type="text" name="firstname" placeholder="First Name">
                        <div class="alert__message error admin_firstname_error"></div>

                        <input type="text" name="lastname" placeholder="Last Name">
                        <div class="alert__message error admin_lastname_error"></div>

                        <input type="text" name="username" placeholder="Username">
                        <div class="alert__message error admin_username_error"></div>

                        <input type="email" name="email" placeholder="Email">
                        <div class="alert__message error admin_email_error"></div>

                        <input type="password" name="password" placeholder="Create Password">
                        <div class="alert__message error admin_password_error"></div>

                        <input type="password" name="confirmpassword" placeholder="Confirm Password">
                        <div class="alert__message error admin_confirmpassword_error"></div>

                        <div class="form__control">
                            <label for="admin_avatar">Admin Avatar</label>
                            <input type="file" name="avatar" id="admin_avatar">
                            <div class="alert__message error admin_avatar_error"></div>
                        </div>

                        <button type="submit" class="btn">Create Admin</button>

                    </form>
                </div>

                <div class="posts-container">
                    <?php foreach ($adminList as $admin): ?>
                        <article class="post-card post-admin">
                            <div class="post-info-admin">
                                <div class="header__avatar">
                                    <img src="/userImages/<?php echo $admin->avatar ?>" alt="A1">
                                </div>
                                <div>
                                    <h3 class="post-title"><?php echo $admin->first_name ?></h3>
                                    <small>Admin</small>
                                </div>
                            </div>
                            <div class="post-actions">
                                <form class="deleteAdminForm" action="/admin/handlers/deleteAdmin.php">
                                    <input type="hidden" name="adminId" value="<?php echo $admin->id ?>">
                                    <input type="hidden" name="adminImage" value="<?php echo $admin->avatar ?>">
                                    <button type="submit" class="icon-btn delete">
                                        <i class="uil uil-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($_SESSION['role'] == "admin"): ?>

            <div class="dashboard__header">
                <h2>You have no Access to Manage Admin</h2>
            </div>

        <?php endif; ?>
    </section>
    <!-- Create and Delete Writers -->
    <section id="view-writer" class="hidden">
        <?php if ($_SESSION['role'] == "superadmin") : ?>
            <div class="dashboard__header">
                <h2>Manage Writers</h2>
            </div>

            <div class="paragraph-grid">
                <div class="form__section-container" style="padding: 1.5rem; width: 100%;">
                    <h4>Add New Writer</h4>

                    <form enctype="multipart/form-data" id="writerRegistration" action="/admin/handlers/addWriter.php" method="post">

                        <input type="text" name="firstname" placeholder="First Name">
                        <div class="alert__message error writer_firstname_error"></div>

                        <input type="text" name="lastname" placeholder="Last Name">
                        <div class="alert__message error writer_lastname_error"></div>

                        <input type="text" name="username" placeholder="Username">
                        <div class="alert__message error writer_username_error"></div>

                        <input type="email" name="email" placeholder="Email">
                        <div class="alert__message error writer_email_error"></div>

                        <input type="password" name="password" placeholder="Create Password">
                        <div class="alert__message error writer_password_error"></div>

                        <input type="password" name="confirmpassword" placeholder="Confirm Password">
                        <div class="alert__message error writer_confirmpassword_error"></div>

                        <div class="form__control">
                            <label for="avatar">User Avatar</label>
                            <input type="file" name="avatar" id="avatar">
                            <div class="alert__message error writer_avatar_error"></div>
                        </div>

                        <button type="submit" class="btn">Create Writer</button>
                        

                    </form>
                </div>

                <div class="posts-container">
                    <?php foreach ($writerList as $writer): ?>
                        <article class="post-card post-writer">
                            <div class="post-info-admin">
                                <div class="header__avatar">
                                    <img src="/userImages/<?=$writer->avatar ?>" alt="A1">
                                </div>
                                <div>
                                    <h3 class="post-title" style="font-size: 1rem;"><?= $writer->first_name ?></h3>
                                    <small>Writer</small>
                                </div>
                            </div>
                            <div class="post-actions">
                                <form class="deleteWriterForm" action="/admin/handlers/deleteWriter.php">
                                    <input type="hidden" name="writerId" value="<?php echo  $writer->id ?>">
                                    <input type="hidden" name="adminImage" value="<?php echo  $writer->avatar ?>">
                                    <button type="submit" class="icon-btn delete">
                                        <i class="uil uil-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

        <?php elseif ($_SESSION['role'] == "admin"): ?>

            <div class="dashboard__header">
                <h2>You have no Access to Manage Writers</h2>
            </div>

        <?php endif; ?>

    </section>
</main>
<?php
require __DIR__."/../includes/footer.php";
?>