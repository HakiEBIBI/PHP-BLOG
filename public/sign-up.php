<?php
$errorMessage = "";

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$email || !$password || !$username) {
        $errorMessage = "Veuillez remplir tous les champs correctement.";
    } else {
        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

        $db = 'sqlite:../Database.db';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($db, '', '', $options);

            $check = $pdo->prepare('SELECT email FROM user WHERE email = ?');
            $check->execute([$email]);

            if ($check->fetch()) {
                $errorMessage = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
            } else {
                $insert = $pdo->prepare('INSERT INTO user (name, email, password) VALUES (?, ?, ?)');
                $insert->execute([$username, $email, $passwordHashed]);

                header('Location: login.php');
                exit();
            }
        } catch (PDOException $e) {
            $errorMessage = "Il y'a eu une erreur lors de l'ajout de l'utilisateur";
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
    <link rel="stylesheet" href="style/sign-up.css" type="text/css">
    <title>Sign up</title>
</head>
<body>
<div class="container">
    <div class="form-box">
        <h1>Sign up</h1>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message" style="color: red; margin-bottom: 10px;">
                <?= htmlspecialchars($errorMessage) ?>
            </div>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <div class="input-field">
                    <label>
                        <input type="text" name="username" class="input" placeholder="Username">
                    </label>
                </div>

                <div class="input-field">
                    <label>
                        <input type="email" name="email" class="input" placeholder="Email Address">
                    </label>
                </div>

                <div class="input-field">
                    <label>
                        <input type="password" name="password" class="input" placeholder="Password">
                    </label>
                </div>
            </div>

            <button type="submit" class="btn">Sign up</button>
        </form>

        <p>Already have an account? <a href="login.php"> login here</a></p>
        <p>Go to Home Page <a href="index.php">here</a></p>
    </div>
</div>
</body>
</html>