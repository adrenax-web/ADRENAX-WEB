<?php
include_once __DIR__ . "/session.php";

$activePage = $activePage ?? '';
$showSearch = $showSearch ?? false;
$isLoggedIn = isset($_SESSION['user_id']);
$brandHref = $isLoggedIn ? route_url('home') : route_url('shop');
?>
<header class="site-header">
  <div class="site-header__inner">
    <a class="brand-mark" href="<?php echo $brandHref; ?>">ADRENAX</a>

    <button class="site-menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-label="Toggle navigation">
      <span class="material-symbols-outlined">menu</span>
    </button>

    <nav class="site-nav" data-menu>
      <?php if ($isLoggedIn): ?>
        <a class="site-nav__link <?php echo $activePage === 'home' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('home'), ENT_QUOTES, 'UTF-8'); ?>">Home</a>
        <a class="site-nav__link <?php echo $activePage === 'shop' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">Shop</a>
        <a class="site-nav__link <?php echo $activePage === 'orders' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('orders'), ENT_QUOTES, 'UTF-8'); ?>">Orders</a>
        <a class="site-nav__link <?php echo $activePage === 'cart' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('cart'), ENT_QUOTES, 'UTF-8'); ?>">Cart</a>
      <?php else: ?>
        <a class="site-nav__link <?php echo $activePage === 'shop' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('shop'), ENT_QUOTES, 'UTF-8'); ?>">Shop</a>
        <a class="site-nav__link <?php echo $activePage === 'login' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('login'), ENT_QUOTES, 'UTF-8'); ?>">Login</a>
        <a class="site-nav__link <?php echo $activePage === 'register' ? 'is-active' : ''; ?>" href="<?php echo htmlspecialchars(route_url('register'), ENT_QUOTES, 'UTF-8'); ?>">Create Account</a>
      <?php endif; ?>
    </nav>

    <div class="site-header__actions">
      <?php if ($showSearch): ?>
        <button class="site-icon-link" type="button" aria-label="Search">
          <span class="material-symbols-outlined">search</span>
        </button>
      <?php endif; ?>

      <?php if ($isLoggedIn): ?>
        <a class="site-icon-link" href="<?php echo htmlspecialchars(route_url('logout'), ENT_QUOTES, 'UTF-8'); ?>" aria-label="Logout">
          <span class="material-symbols-outlined">logout</span>
        </a>
      <?php else: ?>
        <a class="btn btn-ghost" href="<?php echo htmlspecialchars(route_url('login'), ENT_QUOTES, 'UTF-8'); ?>">Login</a>
      <?php endif; ?>
    </div>
  </div>
</header>
