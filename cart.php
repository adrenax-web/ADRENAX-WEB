<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id'])) {
    redirect_to_route('login');
}

include __DIR__ . '/../includes/db.php';

$uid = (int) $_SESSION['user_id'];
$items = [];
$subtotal = 0;

$stmt = $conn->prepare("
    SELECT cart.*, products.name, products.price, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $items[] = $row;
    $subtotal += (float) $row['price'] * (int) $row['quantity'];
}

$pageTitle = 'Your Cart';
$bodyClass = 'theme-store';
$activePage = 'cart';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main">
  <div class="page-shell">
    <section>
      <header class="section-header" style="justify-content: center; text-align: center; border-bottom: 0; margin-bottom: 48px;">
        <div>
          <h1 class="section-header__title">Your Cart</h1>
          <p class="section-header__text" style="margin-left: auto; margin-right: auto;">Review your gear before you hit the streets.</p>
        </div>
      </header>

      <?php if (!empty($items)): ?>
        <div class="cart-layout">
          <div class="stack-card-list">
            <?php foreach ($items as $item): ?>
              <?php $itemSize = trim((string) ($item['size'] ?? '')); ?>
              <article class="cart-item glass-panel--raised">
                <div class="cart-item__thumb">
                  <img src="<?php echo htmlspecialchars(route_url('upload', ['file' => $item['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div>
                  <div class="cart-item__header">
                    <div>
                      <h2 class="cart-item__title"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                      <p class="cart-item__meta">
                        Size: <?php echo htmlspecialchars($itemSize !== '' ? $itemSize : 'Standard', ENT_QUOTES, 'UTF-8'); ?>
                      </p>
                    </div>

                    <div class="cart-item__price">&#8377;<?php echo number_format((float) $item['price'], 0); ?></div>
                  </div>

                  <div class="cart-item__footer">
                    <div class="quantity-pill">
                      <button type="button" onclick="updateQty(<?php echo (int) $item['id']; ?>, 'dec')">
                        <span class="material-symbols-outlined">remove</span>
                      </button>
                      <span><?php echo (int) $item['quantity']; ?></span>
                      <button type="button" onclick="updateQty(<?php echo (int) $item['id']; ?>, 'inc')">
                        <span class="material-symbols-outlined">add</span>
                      </button>
                    </div>

                    <a class="btn btn-danger" href="<?php echo htmlspecialchars(route_url('action.remove_cart', ['id' => (int) $item['id']]), ENT_QUOTES, 'UTF-8'); ?>">
                      <span class="material-symbols-outlined">delete</span>
                      <span>Remove</span>
                    </a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

          <aside class="summary-card glass-panel--raised">
            <h2 class="summary-card__title">Summary</h2>
            <div class="summary-card__divider"></div>

            <div class="summary-row">
              <span>Subtotal</span>
              <strong>&#8377;<?php echo number_format($subtotal, 0); ?></strong>
            </div>

            <div class="summary-row">
              <span>Shipping</span>
              <strong>Calculated at checkout</strong>
            </div>

            <div class="summary-row">
              <span>Taxes</span>
              <strong>&#8377;0</strong>
            </div>

            <div class="summary-card__divider"></div>

            <div class="summary-total">
              <span>Total</span>
              <span>&#8377;<?php echo number_format($subtotal, 0); ?></span>
            </div>

            <a class="btn btn-primary" style="width: 100%; margin-top: 24px;" href="<?php echo htmlspecialchars(route_url('checkout'), ENT_QUOTES, 'UTF-8'); ?>">
              <span>Secure Checkout</span>
              <span class="material-symbols-outlined">lock</span>
            </a>

            <div class="summary-card__icons">
              <span class="material-symbols-outlined">payments</span>
              <span class="material-symbols-outlined">local_shipping</span>
              <span class="material-symbols-outlined">verified</span>
            </div>
          </aside>
        </div>
      <?php else: ?>
        <div class="empty-state glass-panel">
          <div class="empty-state__icon material-symbols-outlined">shopping_bag</div>
          <h2 class="empty-state__title">Your Cart Is Empty</h2>
          <p class="empty-state__text">Looks like you have not added any high-performance gear yet. Hit the streets and find your edge.</p>
          <a class="btn btn-ghost" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">
            <span>Shop Latest Drops</span>
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<script>
function updateQty(cartId, action) {
  fetch(<?php echo json_encode(route_url('action.update_cart')); ?>, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'cart_id=' + encodeURIComponent(cartId) + '&action=' + encodeURIComponent(action)
  })
  .then(function (response) {
    return response.text();
  })
  .then(function (data) {
    if (data.trim() === 'ok') {
      window.location.reload();
    } else {
      console.error(data);
    }
  });
}
</script>

<?php include "../includes/footer.php"; ?>
