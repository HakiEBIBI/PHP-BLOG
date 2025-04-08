<?php
session_start();
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$email || !$password || !$username) {
        $errorMessage = "Veuillez remplir tous les champs correctement.";
    } else {
        $db = 'sqlite:../Database.db';
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $pdo = new PDO($db, '', '', $options);

            $stmt = $pdo->prepare('SELECT * FROM user WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];
                $_SESSION['email'] = $user['email'];

                header("Location: index.php");
                exit();
            } else {
                $errorMessage = "Email ou mot de passe incorrect.";
            }

        } catch (PDOException $e) {
            $errorMessage = "Erreur de base de données : " . $e->getMessage();
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
    <title>Login</title>
</head>
<body>
<div class="container">
    <div class="form-box">
        <h1>Login</h1>
        <?php if (isset($errorMessage)): ?>
            <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <div class="input-field">
                    <input type="text" name="username" class="input" placeholder="Username">
                </div>

                <div class="input-field">
                    <input type="email" name="email" class="input" placeholder="Email Address">
                </div>

                <div class="input-field">
                    <input type="password" name="password" class="input" placeholder="Password">
                </div>
            </div>

            <button type="submit" class="btn">Log in</button>
        </form>

        <p>Dont have a account ? <a href="sign-up.php"> signup here</a></p>
        <p>Go to Home Page <a href="index.php">here</a></p>
    </div>
</div>
</body>
</html>