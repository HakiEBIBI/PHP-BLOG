<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($title) || empty($content)) {
        $errorMessage = "Tous les champs sont obligatoires";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            require 'image-upload.php';
            $uploadResult = uploadImage($_FILES['image']);

            if (isset($uploadResult['error'])) {
                $errorMessage = $uploadResult['error'];
            } else {
                $imagePath = $uploadResult['path'];

                try {
                    $pdo = new PDO('sqlite:../Database.db', '', '', [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]);

                    $stmt = $pdo->prepare('INSERT INTO blog_posts (title, content, image, user_id, created_at) VALUES (?, ?, ?, ?, datetime())');
                    $stmt->execute([$title, $content, $imagePath, $_SESSION['user_id']]);

                    $_SESSION['successMessage'] = "Votre blog a été posté";
                    header("Location: index.php");
                } catch (PDOException $e) {
                    $errorMessage = "Erreur lors de la création de l'article";
                }
            }
        } else {
            $errorMessage = "Veuillez sélectionner une image";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/post-creation.css" type="text/css">
    <title>Post Creation</title>
</head>

<header>
    <div class="nav-bar">
        <div class="menu-item"><a href="index.php">Home</a></div>
        <div class="menu-item" id="post-creation"><a href="post-creation.php">Post Creation</a></div>
    </div>
</header>

<body>
<?php if ($errorMessage): ?>
    <div class="error-message" style="color: red; text-align: center; margin: 10px;">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="all-input">
        <div class="text-input">

            <input type="text" name="title" class="title" placeholder="Enter the title">


            <input type="file" name="image" class="image" placeholder="Put an image url">

            <textarea type="text" name="content" class="content"
                      placeholder="Enter the content of the blog"></textarea>

        </div>
        <input class="btn" type="submit" value="Create Post">

    </div>
</form>

</body>
</html>