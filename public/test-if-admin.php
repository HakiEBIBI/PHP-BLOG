<?php
$db = 'sqlite:../Database.db';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$PDO = new PDO($db, '', '', $options);

function isUserAdmin($PDO, $userId) {
    $stmt = $PDO->prepare('SELECT is_admin FROM user WHERE id = ?');
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result && $result['is_admin'] === 1;
}