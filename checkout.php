<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id'])) {
    redirect_to_route('login');
}

include __DIR__ . '/../includes/db.php';

$uid = (int) $_SESSION['user_id'];
$delivery = 150;
$subtotal = 0;
$cartItems = [];

$userStmt = $conn->prepare("SELECT name, address FROM users WHERE id = ?");
$userStmt->bind_param("i", $uid);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

$cartStmt = $conn->prepare("
    SELECT cart.*, products.name, products.price, products.image
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$cartStmt->bind_param("i", $uid);
$cartStmt->execute();
$cartResult = $cartStmt->get_result();

while ($row = $cartResult->fetch_assoc()) {
    $cartItems[] = $row;
    $subtotal += (float) $row['price'] * (int) $row['quantity'];
}

$grandTotal = $subtotal + ($subtotal > 0 ? $delivery : 0);

$pageTitle = 'Checkout';
$bodyClass = 'theme-store';
$activePage = 'cart';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main">
  <div class="page-shell">
    <section>
      <header class="section-header">
        <div>
          <h1 class="section-header__title">Secure Checkout</h1>
          <p class="section-header__text">Confirm your delivery details and launch the final order flow.</p>
        </div>
      </header>

      <?php if (!empty($cartItems)): ?>
        <div class="checkout-layout">
          <div class="stack-card-list">
            <article class="checkout-card glass-panel--raised">
              <h2 class="checkout-card__title">Delivery Address</h2>
              <p class="checkout-card__text"><strong><?php echo htmlspecialchars($user['name'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?></strong></p>
              <p class="checkout-card__text"><?php echo nl2br(htmlspecialchars($user['address'] ?? 'Address not available', ENT_QUOTES, 'UTF-8')); ?></p>
            </article>

            <article class="checkout-card glass-panel--raised">
              <h2 class="checkout-card__title">Order Review</h2>

              <?php foreach ($cartItems as $item): ?>
                <?php $itemSize = trim((string) ($item['size'] ?? '')); ?>
                <div class="checkout-row">
                  <div>
                    <strong><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></strong>
                    <div class="checkout-card__text">
                      Qty: <?php echo (int) $item['quantity']; ?>
                      |
                      Size: <?php echo htmlspecialchars($itemSize !== '' ? $itemSize : 'Standard', ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                  </div>

                  <div class="currency-highlight">&#8377;<?php echo number_format((float) $item['price'] * (int) $item['quantity'], 0); ?></div>
                </div>
              <?php endforeach; ?>
            </article>
          </div>

          <aside class="summary-card glass-panel--raised">
            <h2 class="summary-card__title">Price Details</h2>
            <div class="summary-card__divider"></div>

            <div class="summary-row">
              <span>Subtotal</span>
              <strong>&#8377;<?php echo number_format($subtotal, 0); ?></strong>
            </div>

            <div class="summary-row">
              <span>Delivery</span>
              <strong>&#8377;<?php echo number_format($delivery, 0); ?></strong>
            </div>

            <div class="summary-card__divider"></div>

            <div class="summary-total">
              <span>Total</span>
              <span>&#8377;<?php echo number_format($grandTotal, 0); ?></span>
            </div>

            <form action="<?php echo htmlspecialchars(route_url('action.place_order'), ENT_QUOTES, 'UTF-8'); ?>" method="POST" style="margin-top: 24px;">
              <button class="btn btn-primary" style="width: 100%;" type="submit">
                <span>Place Order</span>
                <span class="material-symbols-outlined">rocket_launch</span>
              </button>
            </form>
          </aside>
        </div>
      <?php else: ?>
        <div class="empty-state glass-panel">
          <div class="empty-state__icon material-symbols-outlined">shopping_cart_off</div>
          <h2 class="empty-state__title">Nothing to Checkout</h2>
          <p class="empty-state__text">Your secure checkout screen is ready, but your cart is empty.</p>
          <a class="btn btn-ghost" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">Return to Shop</a>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
