<?php
include_once "session.php";

function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        redirect_to_route('login');
    }
}

function requireAdmin()
{
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        redirect_to_route('login');
    }
}
?>
