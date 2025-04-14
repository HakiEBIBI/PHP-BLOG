<?php
session_start();
$getId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($_SESSION['user_id'] === $getId)
    $errorMessage = "";
$successMessage = "";

if (isset($getId)) {
    $id = $getId;
    try {
        $pdo = new PDO('sqlite:../Database.db', '', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $stmt = $pdo->prepare('SELECT * FROM blog_posts WHERE id = ?');
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if (!$post) {
            $errorMessage = "Post non trouvé.";
        }
    } catch (PDOException $e) {
        $errorMessage = "Erreur lors de la récupération du post : " . $e->getMessage();
    }
}

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

                    if (isset($post)) {
                        $stmt = $pdo->prepare('UPDATE blog_posts SET title = ?, content = ?, image = ? WHERE id = ?');
                        $stmt->execute([$title, $content, $imagePath, $id]);
                        $successMessage = "Post mis à jour avec succès!";
                    } else {
                        $stmt = $pdo->prepare('INSERT INTO blog_posts (title, content, image, user_id, created_at) VALUES (?, ?, ?, ?, datetime())');
                        $stmt->execute([$title, $content, $imagePath, $_SESSION['user_id']]);
                        $successMessage = "Post créé avec succès!";
                    }

                    header("refresh:1;url=index.php");
                } catch (PDOException $e) {
                    $errorMessage = "Erreur lors de l'ajout du post : " . $e->getMessage();
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

            <label>
                text Blog
                <input type="text" name="title" class="title" placeholder="Enter the title"
                       value="<?= $post['title'] ?>">
            </label>

            <input type="file" name="image" class="image" placeholder="Put an image url">
            <?php if (!empty($post['image'])): ?>
                <div class="current-image">
                    <p>Ancienne image du Blog :</p>
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="ancienne image du Blog" width="200">
                </div>
            <?php endif; ?>
            <label>
                Content blog
                <textarea name="content" class="content"
                          placeholder="Enter the content of the blog"><?= $post['content'] ?></textarea>
            </label>

        </div>
        <input class="btn" type="submit" value="Edit Post">

    </div>
</form>

</body>
</html>
