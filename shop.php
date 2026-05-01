<?php
require_once "../includes/db.php";

$products = [];
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$pageTitle = 'All Products';
$bodyClass = 'theme-store';
$activePage = 'shop';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main">
  <div class="page-shell">
    <section>
      <header class="section-header">
        <div>
          <h1 class="section-header__title">All Products</h1>
          <p class="section-header__text">Engineered for the streets. Built for velocity.</p>
        </div>

        <div class="sort-pill">
          <strong>Sort by:</strong>
          <span>Latest Drops</span>
          <span class="material-symbols-outlined">expand_more</span>
        </div>
      </header>

      <?php if (!empty($products)): ?>
        <div class="product-grid">
          <?php foreach ($products as $index => $product): ?>
            <a class="product-card" href="<?php echo htmlspecialchars(route_url('product', ['id' => (int) $product['id']]), ENT_QUOTES, 'UTF-8'); ?>">
              <div class="product-card__media">
                <img class="product-card__image" src="<?php echo htmlspecialchars(route_url('upload', ['file' => $product['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>

              <div class="product-card__content">
                <?php if ($index === 1): ?>
                  <span class="card-badge">New Drop</span>
                <?php elseif ($index === 4): ?>
                  <span class="card-badge">Limited Edition</span>
                <?php endif; ?>

                <h2 class="product-card__title"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h2>

                <div class="product-card__price-row">
                  <span class="product-card__price">&#8377; <?php echo number_format((float) $product['price'], 0); ?></span>
                  <span class="product-card__quick">
                    <span class="material-symbols-outlined">shopping_cart</span>
                  </span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state glass-panel">
          <div class="empty-state__icon material-symbols-outlined">inventory_2</div>
          <h2 class="empty-state__title">No Products Yet</h2>
          <p class="empty-state__text">Your storefront is wired into the new redesign, but there are no products in the catalog yet.</p>
        </div>
      <?php endif; ?>
    </section>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
