<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function auth() {
    return $_SESSION['user_id'] ?? null;
}

function logout() {
    session_destroy();
}