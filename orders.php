<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id'])) {
    redirect_to_route('login');
}

include __DIR__ . '/../includes/db.php';

$uid = (int) $_SESSION['user_id'];
$orders = [];

$orderStmt = $conn->prepare("
    SELECT *
    FROM orders
    WHERE user_id = ?
    ORDER BY id DESC
");
$orderStmt->bind_param("i", $uid);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();

$itemStmt = $conn->prepare("
    SELECT order_items.*, products.name, products.image
    FROM order_items
    JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = ?
");

while ($order = $orderResult->fetch_assoc()) {
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

function orderStepIndex($status)
{
    $normalized = strtolower((string) $status);

    if ($normalized === 'delivered') {
        return 3;
    }

    if ($normalized === 'shipped') {
        return 2;
    }

    return 1;
}

$pageTitle = 'My Orders';
$bodyClass = 'theme-store';
$activePage = 'orders';
$showSearch = true;

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main">
  <div class="page-shell">
    <section>
      <header class="section-header" style="border-bottom: 0;">
        <div>
          <h1 class="section-header__title section-header__title--glow">My Operations</h1>
          <p class="section-header__text">Track your high-performance gear acquisitions.</p>
        </div>
      </header>

      <?php if (!empty($orders)): ?>
        <div class="order-list">
          <?php foreach ($orders as $order): ?>
            <?php
            $statusIndex = orderStepIndex($order['status'] ?? 'Processing');
            $progress = $statusIndex === 3 ? '100%' : ($statusIndex === 2 ? '50%' : '0%');
            $date = !empty($order['created_at']) ? new DateTime($order['created_at']) : null;
            ?>
            <article class="order-card glass-panel" style="--progress: <?php echo $progress; ?>;">
              <div class="order-card__header">
                <div>
                  <span class="order-chip">Order #ADX-<?php echo (int) $order['id']; ?></span>
                  <?php if ($date): ?>
                    <div class="order-card__meta">
                      <span>Placed <?php echo htmlspecialchars($date->format('M d, Y'), ENT_QUOTES, 'UTF-8'); ?></span>
                      <span class="status-pill status-pill--<?php echo htmlspecialchars(strtolower((string) ($order['status'] ?? 'Processing')), ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($order['status'] ?? 'Processing', ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="order-card__total">
                  <span class="order-card__total-label">Total Value</span>
                  <span class="order-card__total-value">&#8377;<?php echo number_format((float) $order['total'], 0); ?></span>
                </div>
              </div>

              <div class="order-card__divider"></div>

              <div class="status-track">
                <div class="status-step <?php echo $statusIndex >= 1 ? 'is-active' : ''; ?>">
                  <div class="status-step__node">
                    <span class="material-symbols-outlined"><?php echo $statusIndex > 1 ? 'check' : 'inventory_2'; ?></span>
                  </div>
                  <span class="status-step__label">Confirmed</span>
                </div>

                <div class="status-step <?php echo $statusIndex >= 2 ? 'is-active' : ''; ?>">
                  <div class="status-step__node">
                    <span class="material-symbols-outlined"><?php echo $statusIndex > 2 ? 'check' : 'local_shipping'; ?></span>
                  </div>
                  <span class="status-step__label">In Transit</span>
                </div>

                <div class="status-step <?php echo $statusIndex >= 3 ? 'is-active' : ''; ?>">
                  <div class="status-step__node">
                    <span class="material-symbols-outlined">flag</span>
                  </div>
                  <span class="status-step__label">Delivered</span>
                </div>
              </div>

              <h3 class="payload-title">Payload Contents</h3>

              <div class="order-items">
                <?php if (!empty($order['items'])): ?>
                  <?php foreach ($order['items'] as $item): ?>
                    <?php $itemSize = trim((string) ($item['size'] ?? '')); ?>
                    <div class="order-item">
                      <div class="order-item__thumb">
                        <img src="<?php echo htmlspecialchars(route_url('upload', ['file' => $item['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                      </div>

                      <div>
                        <h4 class="order-item__title"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h4>
                        <p class="order-item__meta">
                          Size: <?php echo htmlspecialchars($itemSize !== '' ? $itemSize : 'Standard', ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                        <p class="order-item__meta">Qty: <?php echo (int) $item['quantity']; ?></p>
                      </div>

                      <div class="order-item__price">&#8377;<?php echo number_format((float) $item['price'] * (int) $item['quantity'], 0); ?></div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="order-item">
                    <div>
                      <h4 class="order-item__title">No items found</h4>
                      <p class="order-item__meta">This order does not have any visible line items.</p>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state glass-panel">
          <div class="empty-state__icon material-symbols-outlined">receipt_long</div>
          <h2 class="empty-state__title">No Orders Yet</h2>
          <p class="empty-state__text">Your redesigned operations screen is ready. Place an order to start tracking it here.</p>
          <a class="btn btn-ghost" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">Browse Products</a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
