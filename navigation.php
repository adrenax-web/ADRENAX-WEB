<?php
if (!function_exists('renderAdminShellStart')) {
    function adminNavClass($activeAdminPage, $targetPage)
    {
        return $activeAdminPage === $targetPage ? 'is-active' : '';
    }

    function renderAdminShellStart($activeAdminPage, $showSidebar = true)
    {
        ?>
        <header class="admin-topbar">
          <div class="admin-topbar__inner">
            <a class="admin-brand" href="<?php echo htmlspecialchars(route_url('admin.dashboard'), ENT_QUOTES, 'UTF-8'); ?>">AdrenaX</a>

            <nav class="admin-topnav">
              <a class="admin-topnav__link <?php echo adminNavClass($activeAdminPage, 'dashboard'); ?>" href="<?php echo htmlspecialchars(route_url('admin.dashboard'), ENT_QUOTES, 'UTF-8'); ?>">Dashboard</a>
              <a class="admin-topnav__link <?php echo adminNavClass($activeAdminPage, 'products'); ?>" href="<?php echo htmlspecialchars(route_url('admin.products'), ENT_QUOTES, 'UTF-8'); ?>">Products</a>
              <a class="admin-topnav__link <?php echo adminNavClass($activeAdminPage, 'add-product'); ?>" href="<?php echo htmlspecialchars(route_url('admin.add_product'), ENT_QUOTES, 'UTF-8'); ?>">Add Product</a>
              <a class="admin-topnav__link <?php echo adminNavClass($activeAdminPage, 'orders'); ?>" href="<?php echo htmlspecialchars(route_url('admin.orders'), ENT_QUOTES, 'UTF-8'); ?>">Orders</a>
              <a class="admin-topnav__link <?php echo adminNavClass($activeAdminPage, 'users'); ?>" href="<?php echo htmlspecialchars(route_url('admin.users'), ENT_QUOTES, 'UTF-8'); ?>">Users</a>
              <a class="admin-topnav__link" href="<?php echo htmlspecialchars(route_url('logout'), ENT_QUOTES, 'UTF-8'); ?>">Logout</a>
            </nav>
          </div>
        </header>

        <div class="admin-layout<?php echo $showSidebar ? '' : ' admin-layout--single'; ?>">
          <?php if ($showSidebar): ?>
            <aside class="admin-sidebar">
              <div class="admin-sidebar__section">
                <a class="admin-sidebar__link <?php echo adminNavClass($activeAdminPage, 'dashboard'); ?>" href="<?php echo htmlspecialchars(route_url('admin.dashboard'), ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="material-symbols-outlined">home</span>
                  <span>Dashboard</span>
                </a>
              </div>

              <div class="admin-sidebar__section">
                <p class="admin-sidebar__label">Catalog</p>
                <a class="admin-sidebar__link <?php echo adminNavClass($activeAdminPage, 'products'); ?>" href="<?php echo htmlspecialchars(route_url('admin.products'), ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="material-symbols-outlined">inventory_2</span>
                  <span>All Products</span>
                </a>
                <a class="admin-sidebar__link <?php echo adminNavClass($activeAdminPage, 'add-product'); ?>" href="<?php echo htmlspecialchars(route_url('admin.add_product'), ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="material-symbols-outlined">add</span>
                  <span>Add Product</span>
                </a>
              </div>

              <div class="admin-sidebar__section">
                <p class="admin-sidebar__label">Sales</p>
                <a class="admin-sidebar__link <?php echo adminNavClass($activeAdminPage, 'orders'); ?>" href="<?php echo htmlspecialchars(route_url('admin.orders'), ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="material-symbols-outlined">shopping_cart</span>
                  <span>Orders</span>
                </a>
                <a class="admin-sidebar__link <?php echo adminNavClass($activeAdminPage, 'users'); ?>" href="<?php echo htmlspecialchars(route_url('admin.users'), ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="material-symbols-outlined">group</span>
                  <span>Users</span>
                </a>
              </div>

              <div class="admin-sidebar__section">
                <p class="admin-sidebar__label">Access</p>
                <a class="admin-sidebar__link" href="<?php echo htmlspecialchars(route_url('logout'), ENT_QUOTES, 'UTF-8'); ?>">
                  <span class="material-symbols-outlined">logout</span>
                  <span>Logout</span>
                </a>
              </div>
            </aside>
          <?php endif; ?>

          <main class="admin-main<?php echo $showSidebar ? '' : ' admin-main--centered'; ?>">
        <?php
    }

    function renderAdminShellEnd()
    {
        ?>
          </main>
        </div>
        </body>
        </html>
        <?php
    }
}
