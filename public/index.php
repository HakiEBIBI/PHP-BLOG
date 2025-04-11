<?php
session_start();

require_once 'image-upload.php';
$db = 'sqlite:../Database.db';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $PDO = new PDO($db, '', '', $options);
    $PDOPREPARE = $PDO->prepare('SELECT blog_posts.*, user.name FROM blog_posts JOIN user ON blog_posts.user_id = user.id');
    $PDOPREPARE->execute();
    $posts = $PDOPREPARE->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $errors[] = 'Database error: ' . $e->getMessage();
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/index.css" type="text/css">
    <title>Blog Website</title>
</head>
<header>
    <div class="nav-bar">
        <div class="menu-item" id="home"><a href="index.php">Home</a></div>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="post-creation.php">
                <div class="menu-item">Post Creation</div>
            </a>
        <?php endif; ?>
    </div>
    <div class="nav-btn">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="sign-up.php">
                <button class="btn">Sign Up</button>
            </a>
            <a href="login.php">
                <button class="btn">Login</button>
            </a>
        <?php else: ?>
            <a href="log-out.php">
                <button class="btn">Log Out</button>
            </a>
        <?php endif; ?>
    </div>
</header>
<body>

<div class="all-blog">

    <?php
    foreach ($posts as $post) : ?>
        <a href="post-detail.php">
            <div class="blog">
                <p><?= $post["name"] ?> - <?= $post["created_at"] ?></p>
                <h2><?= $post["title"] ?></h2>
                <img alt="image-blog" class="image" src="<?= $post['image'] ?>">
                <p><?= $post['content'] ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

</body>
</html>