<?php session_start();
if (!isset($_SESSION['user_id'])){
header('Location: login.php');
exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/post-edition.css" type="text/css">
    <title>Post Edition</title>
</head>

<header>
    <div class="nav-bar">
        <div class="menu-item"><a href="index.php">Home</a></div>
        <div class="menu-item"><a href="post-creation.php">Post Creation</a></div>
    </div>
</header>

<body>
<form>
    <div class="all-input">
        <div class="text-input">

            <input type="text" name="title" class="title" placeholder="Enter the title">


            <input type="file" name="image" class="image" placeholder="Put an image url">

            <textarea type="text" name="content blog" class="content"
                      placeholder="Enter the content of the blog"></textarea>

        </div>
        <input class="btn" type="submit" value="Edit Post">

    </div>
</form>

</body>
</html><?php
