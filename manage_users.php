<?php
include "../includes/session.php";

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    redirect_to_route('login');
}

include "../includes/db.php";

if (isset($_GET['delete'])) {
    $deleteId = (int) $_GET['delete'];

    if ($deleteId !== (int) $_SESSION['user_id']) {
        $deleteStmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $deleteStmt->bind_param("i", $deleteId);
        $deleteStmt->execute();
    }

    redirect_to_route('admin.users');
}

$users = [];
$userQuery = $conn->query("SELECT * FROM users ORDER BY id DESC");

if ($userQuery) {
    while ($row = $userQuery->fetch_assoc()) {
        $users[] = $row;
    }
}

$pageTitle = 'Manage Users';
$bodyClass = 'theme-admin';

include "../includes/head.php";
include __DIR__ . "/includes/navigation.php";

renderAdminShellStart('users');
?>

<section class="admin-page-head">
  <div>
    <h1 class="admin-page-title">Users</h1>
    <p class="admin-page-text">Monitor account roles, creation dates, and remove inactive accounts without leaving the redesigned admin flow.</p>
  </div>
</section>

<section class="admin-panel">
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Email</th>
          <th>Role</th>
          <th>Created</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($users)): ?>
          <?php foreach ($users as $user): ?>
            <tr>
              <td class="admin-table__id">#<?php echo (int) $user['id']; ?></td>
              <td><?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></td>
              <td>
                <span class="status-pill <?php echo ($user['role'] ?? 'user') === 'admin' ? 'status-pill--processing' : 'status-pill--shipped'; ?>">
                  <?php echo htmlspecialchars(ucfirst($user['role'] ?? 'user'), ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </td>
              <td class="subtle-text">
                <?php
                if (!empty($user['created_at'])) {
                    $createdAt = new DateTime($user['created_at']);
                    echo htmlspecialchars($createdAt->format('d/m/Y h:i A'), ENT_QUOTES, 'UTF-8');
                } else {
                    echo 'Not available';
                }
                ?>
              </td>
              <td>
                <?php if ((int) $user['id'] !== (int) $_SESSION['user_id']): ?>
                  <a class="admin-action-link" href="<?php echo htmlspecialchars(route_url('admin.users'), ENT_QUOTES, 'UTF-8'); ?>?delete=<?php echo (int) $user['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
                <?php else: ?>
                  <span class="subtle-text">Current admin</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">No users found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<?php renderAdminShellEnd(); ?>
