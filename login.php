<?php
include "../includes/session.php";

$pageTitle = 'Login';
$bodyClass = 'theme-store';
$activePage = 'login';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main page-main--auth">
  <div class="page-shell">
    <div class="auth-layout">
      <section class="auth-card glass-panel--raised">
        <h1 class="auth-card__title">Login</h1>
        <p class="auth-card__text">Access the redesigned AdrenaX storefront and pick up right where your build left off.</p>

        <form class="auth-form" action="<?php echo htmlspecialchars(route_url('action.login'), ENT_QUOTES, 'UTF-8'); ?>" method="POST">
          <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Enter email" required>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter password" required>
          </div>

          <button class="btn btn-primary" type="submit">Login</button>
        </form>

        <p class="auth-alt">Do not have an account? <a href="<?php echo htmlspecialchars(route_url('register'), ENT_QUOTES, 'UTF-8'); ?>" class="currency-highlight">Create one</a>.</p>
      </section>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
