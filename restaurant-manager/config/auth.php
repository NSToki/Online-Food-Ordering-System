<?php

function isManagerLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'manager';
}

function requireManagerLogin() {
    if (!isManagerLoggedIn()) {
        header('Location: ?route=manager/login');
        exit;
    }
}

function getLoggedInUserId() {
    return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
}
