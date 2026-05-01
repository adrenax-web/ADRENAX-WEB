<?php
require_once __DIR__ . '/includes/session.php';

if (isset($_SESSION['user_id'])) {
    redirect_to_route('home');
}

redirect_to_route('shop');
