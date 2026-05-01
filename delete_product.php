<?php
session_start();

require_once __DIR__ . '/../includes/app.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    redirect_to_route('login');
}

include __DIR__ . '/../includes/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $img = $conn->query("SELECT image FROM products WHERE id=$id")->fetch_assoc();

    $imagePath = isset($img['image']) ? app_root_path('assets/uploads/' . $img['image']) : '';

    if ($imagePath !== '' && file_exists($imagePath)) {
        unlink($imagePath);
    }

    $conn->query("DELETE FROM products WHERE id=$id");
}

redirect_to_route('admin.products');
