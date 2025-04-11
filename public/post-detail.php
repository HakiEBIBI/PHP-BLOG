<?php
SESSION_START();
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

        <p>username - created at</p>

        <h2>Title Blog</h2>

        <img class="image" src="image/signup-image.jpg" alt="boat on river">

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="login.php">
                <input class="btn" type="submit" value="Edit">
            </a>
        <?php endif; ?>
    </div>


</body>
</html>