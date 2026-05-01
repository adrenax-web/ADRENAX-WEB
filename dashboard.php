<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    redirect_to_route('login');
}

include "../includes/db.php";

$users = (int) ($conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0);
$orders = (int) ($conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'] ?? 0);
$products = (int) ($conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'] ?? 0);
$revenue = (float) ($conn->query("SELECT SUM(total) AS total FROM orders")->fetch_assoc()['total'] ?? 0);

$recentOrders = [];
$recentOrderQuery = $conn->query("
    SELECT orders.*, users.name
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
    LIMIT 5
");

if ($recentOrderQuery) {
    while ($row = $recentOrderQuery->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

$pageTitle = 'Admin Dashboard';
$bodyClass = 'theme-admin';

include "../includes/head.php";
include __DIR__ . "/includes/navigation.php";

renderAdminShellStart('dashboard');
?>

<section class="admin-page-head">
  <div>
    <h1 class="admin-page-title">Dashboard</h1>
    <p class="admin-page-text">A high-contrast control room for your storefront metrics, recent orders, and catalog velocity.</p>
  </div>
</section>

<section class="admin-stat-grid">
  <article class="admin-card">
    <p class="admin-card__label">Users</p>
    <p class="admin-card__value"><?php echo number_format($users); ?></p>
  </article>

  <article class="admin-card">
    <p class="admin-card__label">Orders</p>
    <p class="admin-card__value"><?php echo number_format($orders); ?></p>
  </article>

  <article class="admin-card">
    <p class="admin-card__label">Products</p>
    <p class="admin-card__value"><?php echo number_format($products); ?></p>
  </article>

  <article class="admin-card admin-card--highlight">
    <p class="admin-card__label">Revenue</p>
    <p class="admin-card__value admin-card__value--currency">
      <span class="admin-card__currency">&#8377;</span>
      <span><?php echo number_format($revenue, 2); ?></span>
    </p>
  </article>
</section>

<section class="admin-panel">
  <div class="admin-panel__header">
    <h2 class="admin-panel__title">Recent Orders</h2>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Total</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($recentOrders)): ?>
          <?php foreach ($recentOrders as $order): ?>
            <tr>
              <td class="admin-table__id">#<?php echo (int) $order['id']; ?></td>
              <td><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td>&#8377;<?php echo number_format((float) $order['total'], 2); ?></td>
              <td class="subtle-text"><?php echo htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="4">No orders found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php renderAdminShellEnd(); ?>
