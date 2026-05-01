<?php
include "../includes/db.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

$pageTitle = $product ? $product['name'] : 'Product';
$bodyClass = 'theme-store';
$activePage = 'shop';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main">
  <div class="page-shell">
    <?php if ($product): ?>
      <section class="product-detail">
        <div class="product-stage glass-panel">
          <div class="product-stage__frame">
            <img src="<?php echo htmlspecialchars(route_url('upload', ['file' => $product['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
          </div>
        </div>

        <div class="product-panel glass-panel--raised">
          <span class="product-panel__eyebrow">Street Engineering</span>
          <h1 class="product-panel__title"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
          <div class="product-panel__price">&#8377;<?php echo number_format((float) $product['price'], 0); ?></div>
          <p class="product-panel__text">
            <?php echo htmlspecialchars($product['description'] ?: 'Built to match the new AdrenaX storefront with clean lines, bold contrast, and performance-first energy.', ENT_QUOTES, 'UTF-8'); ?>
          </p>

          <form class="purchase-form" action="<?php echo htmlspecialchars(route_url('action.add_to_cart'), ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            <input type="hidden" name="product_id" value="<?php echo (int) $product['id']; ?>">

            <div class="form-grid">
              <div class="field">
                <label for="size">Size</label>
                <select id="size" name="size">
                  <option value="S">S</option>
                  <option value="M">M</option>
                  <option value="L">L</option>
                </select>
              </div>

              <div class="field">
                <label for="quantity">Quantity</label>
                <input id="quantity" type="number" name="quantity" value="1" min="1">
              </div>
            </div>

            <button class="btn btn-primary" type="submit">
              <span>Add to Cart</span>
              <span class="material-symbols-outlined">shopping_cart</span>
            </button>
          </form>
        </div>
      </section>
    <?php else: ?>
      <div class="empty-state glass-panel">
        <div class="empty-state__icon material-symbols-outlined">search_off</div>
        <h2 class="empty-state__title">Product Not Found</h2>
        <p class="empty-state__text">The requested product could not be loaded into the redesigned detail view.</p>
        <a class="btn btn-ghost" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">Back to Shop</a>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
