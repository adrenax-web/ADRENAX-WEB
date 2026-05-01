<?php
include "../includes/session.php";
include "../includes/auth.php";
include "../includes/db.php";

requireLogin();

$products = [];
$productQuery = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 6");

if ($productQuery) {
    while ($row = $productQuery->fetch_assoc()) {
        $products[] = $row;
    }
}

$heroProduct = $products[0] ?? null;
$featurePrimary = $products[1] ?? $heroProduct;
$featureCards = array_slice($products, 2, 2);
$latestDrops = array_slice($products, 0, 6);

$pageTitle = 'Home';
$bodyClass = 'theme-store';
$activePage = 'home';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main">
  <div class="page-shell">
    <section class="hero-section">
      <div class="hero-grid">
        <div class="hero-copy">
          <div class="eyebrow">New Drop Available</div>
          <h1 class="hero-title">ADRENAX</h1>
          <p class="hero-subtitle">Unleash Your Street Power</p>
          <p class="hero-text">
            High-octane engineering meets street grit. Precision crafted drops for those who push the redline after dark.
          </p>
          <a class="btn btn-primary" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">
            <span>Shop Now</span>
            <span class="material-symbols-outlined">arrow_forward</span>
          </a>
        </div>

        <div class="hero-visual">
          <div class="hero-frame">
            <?php if ($heroProduct): ?>
              <img src="<?php echo htmlspecialchars(route_url('upload', ['file' => $heroProduct['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($heroProduct['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <?php else: ?>
              <img src="<?php echo htmlspecialchars(app_url('__redesign_reference/stitch_initial_ui_redesign/homepage_redesign/screen.png'), ENT_QUOTES, 'UTF-8'); ?>" alt="AdrenaX hero reference">
            <?php endif; ?>
          </div>

          <div class="hero-metric glass-panel">
            <div class="hero-metric__label">
              <span class="material-symbols-outlined">speed</span>
              <span>Performance</span>
            </div>
            <div class="hero-metric__value">MAX</div>
          </div>
        </div>
      </div>
    </section>

    <?php if ($featurePrimary): ?>
      <section>
        <h2 class="kicker-title">Featured Tech</h2>

        <div class="feature-grid">
          <article class="feature-card feature-card--primary glass-panel">
            <img class="feature-card__image" src="<?php echo htmlspecialchars(route_url('upload', ['file' => $featurePrimary['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($featurePrimary['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="feature-card__overlay"></div>
            <div class="feature-card__content">
              <span class="feature-card__chip">Featured Drop</span>
              <h3 class="feature-card__title"><?php echo htmlspecialchars($featurePrimary['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
              <p class="feature-card__text">
                <?php echo htmlspecialchars($featurePrimary['description'] ?: 'Precision-built streetwear with the same high-contrast energy as the new redesign.', ENT_QUOTES, 'UTF-8'); ?>
              </p>
              <a class="feature-card__link" href="<?php echo htmlspecialchars(route_url('product', ['id' => (int) $featurePrimary['id']]), ENT_QUOTES, 'UTF-8'); ?>">
                <span>Explore</span>
                <span class="material-symbols-outlined">arrow_outward</span>
              </a>
            </div>
          </article>

          <div class="feature-card--mini-stack">
            <?php foreach ($featureCards as $index => $featureCard): ?>
              <article class="feature-card feature-card--mini glass-panel">
                <div class="feature-card__icon">
                  <span class="material-symbols-outlined"><?php echo $index === 0 ? 'bolt' : 'tune'; ?></span>
                </div>
                <div class="feature-card__index"><?php echo str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT); ?></div>
                <div class="feature-card__content">
                  <h3 class="feature-card__title feature-card__title--mini"><?php echo htmlspecialchars($featureCard['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                  <p class="feature-card__text">
                    <?php echo htmlspecialchars($featureCard['description'] ?: 'Dialed for velocity and built to stand out in the new AdrenaX visual system.', ENT_QUOTES, 'UTF-8'); ?>
                  </p>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <?php if (!empty($latestDrops)): ?>
      <section>
        <div class="section-header">
          <div>
            <h2 class="section-header__title">Latest Drops</h2>
            <p class="section-header__text">A curated first look at the newest products now wrapped in the redesigned storefront experience.</p>
          </div>
        </div>

        <div class="product-grid">
          <?php foreach ($latestDrops as $index => $product): ?>
            <a class="product-card" href="<?php echo htmlspecialchars(route_url('product', ['id' => (int) $product['id']]), ENT_QUOTES, 'UTF-8'); ?>">
              <div class="product-card__media">
                <img class="product-card__image" src="<?php echo htmlspecialchars(route_url('upload', ['file' => $product['image']]), ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>

              <div class="product-card__content">
                <?php if ($index === 0): ?>
                  <span class="card-badge">New Drop</span>
                <?php elseif ($index === 4): ?>
                  <span class="card-badge">Limited Edition</span>
                <?php endif; ?>

                <h3 class="product-card__title"><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>

                <div class="product-card__price-row">
                  <span class="product-card__price">&#8377; <?php echo number_format((float) $product['price'], 0); ?></span>
                  <span class="product-card__quick">
                    <span class="material-symbols-outlined">arrow_forward</span>
                  </span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
