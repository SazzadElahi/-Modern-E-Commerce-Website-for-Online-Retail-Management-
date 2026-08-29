<?php


const REMEMBER_COOKIE = 'username'; 

function current_user(mysqli $conn): ?array {
    if (!empty($_SESSION['user_id'])) {
        $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE id=?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user) return $user;
        unset($_SESSION['user_id'], $_SESSION['username']); 
    }
    return null;
}
