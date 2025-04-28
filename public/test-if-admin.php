<?php
function isUserAdmin($PDO, $userId) {
    $stmt = $PDO->prepare('SELECT is_admin FROM user WHERE id = ?');
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result && $result['is_admin'] === 1;
}