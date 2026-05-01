<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    redirect_to_route('login');
}

include "../includes/db.php";

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $image = basename($_FILES['image']['name'] ?? '');
    $tmpPath = $_FILES['image']['tmp_name'] ?? '';

    if ($name === '' || $price <= 0 || $image === '' || $tmpPath === '') {
        $error = 'Please complete every required field before adding a product.';
    } else {
        $uploadPath = app_root_path('assets/uploads/' . $image);

        if (move_uploaded_file($tmpPath, $uploadPath)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $name, $price, $description, $image);

            if ($stmt->execute()) {
                $success = 'Product added successfully.';
            } else {
                $error = 'The product could not be saved. Please try again.';
            }
        } else {
            $error = 'The product image could not be uploaded.';
        }
    }
}

$pageTitle = 'Add Product';
$bodyClass = 'theme-admin';

include "../includes/head.php";
include __DIR__ . "/includes/navigation.php";

renderAdminShellStart('add-product', false);
?>

<section class="admin-form-card">
  <h1 class="admin-page-title" style="font-size: clamp(2.2rem, 4vw, 3.4rem);">Add Product</h1>
  <p class="admin-page-text" style="margin-bottom: 32px;">Enter the product details to create a new listing inside the redesigned admin experience.</p>

  <?php if ($success !== ''): ?>
    <div class="notice notice--success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endif; ?>

  <?php if ($error !== ''): ?>
    <div class="notice notice--error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
  <?php endif; ?>

  <form class="auth-form" method="POST" enctype="multipart/form-data">
    <div class="field">
      <label for="name">Product Name</label>
      <input id="name" type="text" name="name" placeholder="Product name" required>
    </div>

    <div class="field">
      <label for="price">Price</label>
      <input id="price" type="number" step="0.01" min="0" name="price" placeholder="0.00" required>
    </div>

    <div class="field">
      <label for="description">Description</label>
      <textarea id="description" name="description" placeholder="Description"></textarea>
    </div>

    <div class="field-file">
      <label for="image">Product Image</label>
      <input id="image" type="file" name="image" accept="image/*" required>
    </div>

    <button class="btn btn-primary" type="submit" style="width: 100%;">Add Product</button>
  </form>
</section>

<?php renderAdminShellEnd(); ?>
