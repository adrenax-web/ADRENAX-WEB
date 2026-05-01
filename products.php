<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    redirect_to_route('login');
}

include "../includes/db.php";

$products = [];
$productQuery = $conn->query("SELECT * FROM products ORDER BY id DESC");

if ($productQuery) {
    while ($row = $productQuery->fetch_assoc()) {
        $products[] = $row;
    }
}

$pageTitle = 'Manage Products';
$bodyClass = 'theme-admin';

include "../includes/head.php";
include __DIR__ . "/includes/navigation.php";

renderAdminShellStart('products');
?>

<section class="admin-page-head">
  <div>
    <h1 class="admin-page-title">All Products</h1>
    <p class="admin-page-text">Manage your product catalog, review visuals, and act on entries from a layout aligned to the redesign reference.</p>
  </div>

  <a class="btn btn-primary" href="<?php echo htmlspecialchars(route_url('admin.add_product'), ENT_QUOTES, 'UTF-8'); ?>">
    <span class="material-symbols-outlined">add</span>
    <span>Add New Product</span>
  </a>
</section>

<section class="admin-panel">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Image</th>
          <th>Name</th>
          <th>Price</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($products)): ?>
          <?php foreach ($products as $product): ?>
            <tr>
              <td class="admin-table__id">#<?php echo (int) $product['id']; ?></td>
              <td>
                <div class="admin-thumb">
                  <img src="<?php echo htmlspecialchars(route_url('upload', ['file' => $product['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>
              </td>
              <td><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td class="admin-price">&#8377;<?php echo number_format((float) $product['price'], 2); ?></td>
              <td>
                <a class="admin-action-link" href="<?php echo htmlspecialchars(app_url('admin/delete_product.php'), ENT_QUOTES, 'UTF-8'); ?>?id=<?php echo (int) $product['id']; ?>" onclick="return confirm('Delete this product?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">No products found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="admin-panel__footer">
    <span class="subtle-text">Showing <?php echo number_format(count($products)); ?> product<?php echo count($products) === 1 ? '' : 's'; ?> in the catalog.</span>
  </div>
</section>

<?php renderAdminShellEnd(); ?>
