<?php
session_start();
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
$newPassword = filter_input(INPUT_POST, 'newpassword', FILTER_SANITIZE_SPECIAL_CHARS);
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$id = $_SESSION['user_id'];

try {
    $pdo = new PDO('sqlite:../Database.db', '', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    $stmt = $pdo->prepare('SELECT * FROM user WHERE id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();


} catch (PDOException $e) {
    $errorMessage = "utilisateur non trouvé : " . $e->getMessage();
}

if ($newPassword !== '') {
    $stmt = $pdo->prepare('SELECT password FROM user WHERE id = ?');
    $stmt->execute([$id]);
    $currentHashedPassword = $stmt->fetchColumn();

    if (!password_verify($password, $currentHashedPassword)) {
        $errorMessage = "le mot de passe actuel est incorrect.";
    } elseif ($newPassword !== '') {
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE user SET password = ? WHERE id = ?');
        $stmt->execute([$hashedNewPassword, $id]);
        $_SESSION['successMessage'] = "le mot de passe à été changé";
        header('Location: index.php');
    }
}

if ($newPassword === '' && password_verify($password, $hashedPassword) && $username === $user['name'] && $email === $user['email']) {
    $_SESSION['successMessage'] = "le compte reste inchangé";
    header('Location: index.php');
}

try {
    if ($email && $email !== $user['email']) {
        $stmt = $pdo->prepare('UPDATE user SET email = ? WHERE id = ?');
        $stmt->execute([$email, $id]);
        $updated = true;
    }

    if ($username && $username !== $user['name']) {
        $stmt = $pdo->prepare('UPDATE user SET name = ? WHERE id = ?');
        $stmt->execute([$username, $id]);
        $updated = true;
    }

    if (isset($updated)) {
        $_SESSION['successMessage'] = "votre compte à bien été modifié";
        header('Location: index.php');
        exit();
    }
} catch (PDOException $e) {
    $errorMessage = "Erreur de mise à jour : " . $e->getMessage();
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/edit-user-profile.css" type="text/css">
    <title>edit user profile</title>
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
        <a href="log-out.php">
            <button class="btn">Log Out</button>
        </a>
    </div>
</header>
<body>
<div class="container">
    <div class="form-box">
        <h1>Edit User Profile</h1>
        <?php if (isset($errorMessage)): ?>
            <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <div class="input-field">
                    <label>
                        <input type="text" name="username" class="input" placeholder="Username"
                               value="<?= $post['name'] ?>">
                    </label>
                </div>
                <div class=" input-field">
                    <label>
                        <input type="email" name="email" class="input" placeholder="Email Address"
                               value="<?= $post['email'] ?>">
                    </label>
                </div>

                <div class="input-field">
                    <label>
                        <input type="password" name="password" class="input" placeholder="Password">
                    </label>
                </div>

                <div class="input-field">
                    <label>
                        <input type="password" name="newpassword" class="input"
                               placeholder="new password dont fill if you dont want a new password">
                    </label>
                </div>
            </div>

            <button type="submit" class="btn">Edit User</button>
        </form>

        <p>Go to Home Page <a href="index.php">here</a></p>
    </div>
</div>
</body>
</html>
