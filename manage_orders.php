<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    redirect_to_route('login');
}

include "../includes/db.php";

$orders = [];
$orderQuery = $conn->query("
    SELECT orders.*, users.name, users.email
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
");

$itemStmt = $conn->prepare("
    SELECT order_items.*, products.name
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");

if ($orderQuery) {
    while ($order = $orderQuery->fetch_assoc()) {
        $items = [];
        $itemStmt->bind_param("i", $order['id']);
        $itemStmt->execute();
        $itemResult = $itemStmt->get_result();

        while ($item = $itemResult->fetch_assoc()) {
            $items[] = $item;
        }

        $order['items'] = $items;
        $orders[] = $order;
    }
}

$pageTitle = 'Manage Orders';
$bodyClass = 'theme-admin';

include "../includes/head.php";
include __DIR__ . "/includes/navigation.php";

renderAdminShellStart('orders');
?>

<section class="admin-page-head">
  <div>
    <h1 class="admin-page-title">Orders</h1>
    <p class="admin-page-text">Review customer orders, inspect line items, and update fulfillment status from a single redesigned control surface.</p>
  </div>
</section>

<section class="admin-panel">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Customer</th>
          <th>Items</th>
          <th>Total</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $order): ?>
            <tr>
              <td class="admin-table__id">#<?php echo (int) $order['id']; ?></td>
              <td>
                <strong><?php echo htmlspecialchars($order['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                <div class="subtle-text"><?php echo htmlspecialchars($order['email'], ENT_QUOTES, 'UTF-8'); ?></div>
              </td>
              <td>
                <div class="order-detail-list">
                  <?php if (!empty($order['items'])): ?>
                    <?php foreach ($order['items'] as $item): ?>
                      <span><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int) $item['quantity']; ?></span>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <span>No items found</span>
                  <?php endif; ?>
                </div>
              </td>
              <td class="admin-price">&#8377;<?php echo number_format((float) $order['total'], 2); ?></td>
              <td>
                <span class="status-pill status-pill--<?php echo htmlspecialchars(strtolower((string) ($order['status'] ?? 'Processing')), ENT_QUOTES, 'UTF-8'); ?>">
                  <?php echo htmlspecialchars($order['status'] ?? 'Processing', ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </td>
              <td>
                <form class="admin-inline-form" method="POST" action="<?php echo htmlspecialchars(route_url('action.update_order'), ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="id" value="<?php echo (int) $order['id']; ?>">
                  <select name="status">
                    <option value="Processing" <?php echo ($order['status'] ?? '') === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="Shipped" <?php echo ($order['status'] ?? '') === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="Delivered" <?php echo ($order['status'] ?? '') === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                  </select>
                  <button class="btn btn-primary" type="submit">Update</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="6">No orders found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php renderAdminShellEnd(); ?>
