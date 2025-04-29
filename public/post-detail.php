<?php
session_start();
$error = '';
$db = 'sqlite:../Database.db';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$PDO = new PDO($db, '', '', $options);

require_once 'test-if-admin.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!empty($id)) {
    $stmt = $PDO->prepare('SELECT blog_posts.*, user.name AS name
    FROM blog_posts
    JOIN user ON blog_posts.user_id = user.id
    WHERE blog_posts.id = ?
');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
} else {
    $error = 'Post not found';
}

$isAdmin = isset($_SESSION['user_id']) ? isUserAdmin($PDO, $_SESSION['user_id']) : false;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/post-detail.css">
    <title>Document</title>
</head>
<header>
    <div class="nav-bar">
        <div class="menu-item" id="home"><a href="index.php">Home</a></div>
        <div class="menu-item"><a href="post-creation.php">Post Creation</a></div>
    </div>
    <div class="nav-btn">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="sign-up.php">
                <button class="btn">Sign Up</button>
            </a>
            <a href="login.php">
                <button class="btn">Login</button>
            </a>
        <?php endif; ?>
    </div>
</header>
<body>

<div class="all-input">
    <div class="text-input">

        <p><?= $user["created_at"] ?> - <?= $user['name'] ?></p>
        <h2><?= $user["title"] ?></h2>
        <img alt="image blog" src="<?= $user["image"] ?>" class="image"/>
        <p><?= $user["content"] ?></p>


        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] === $user['user_id'] || $isAdmin)): ?>
             <a class="btn" href="post-edition.php?id=<?= $user['id'] ?>">
                Edit
            </a>
        <?php endif; ?>
    </div>


</body>
</html>