<?php
include "../includes/session.php";

$pageTitle = 'Create Account';
$bodyClass = 'theme-store';
$activePage = 'register';

include "../includes/head.php";
include "../includes/header.php";
?>

<main class="page-main page-main--auth">
  <div class="page-shell">
    <div class="auth-layout">
      <section class="auth-card glass-panel--raised">
        <h1 class="auth-card__title">Create Account</h1>
        <p class="auth-card__text">Join the redesigned AdrenaX experience with your delivery details and street-ready profile.</p>

        <form class="auth-form" action="<?php echo htmlspecialchars(route_url('action.register'), ENT_QUOTES, 'UTF-8'); ?>" method="POST">
          <div class="field">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" placeholder="Enter name" required>
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Enter email" required>
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Enter password" required>
          </div>

          <div class="field">
            <label for="address">Address</label>
            <textarea id="address" name="address" placeholder="Enter address" required></textarea>
          </div>

          <button class="btn btn-primary" type="submit">Create Account</button>
        </form>

        <p class="auth-alt">Already registered? <a href="<?php echo htmlspecialchars(route_url('login'), ENT_QUOTES, 'UTF-8'); ?>" class="currency-highlight">Login here</a>.</p>
      </section>
    </div>
  </div>
</main>

<?php include "../includes/footer.php"; ?>
