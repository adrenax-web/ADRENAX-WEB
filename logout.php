<?php
require_once __DIR__ . '/../includes/session.php';

session_unset();
session_destroy();

redirect_to_route('login');
?>
