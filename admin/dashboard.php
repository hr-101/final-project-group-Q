<?php
require_once __DIR__ . '/functions.php';
require_login();
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="assets/css/admin.css">
<title>Admin Dashboard</title>
</head><body>
<div class="header">
  <div class="brand"><div class="logo">A</div><div><strong>Admin Panel</strong><div class="small">Welcome, <?php echo htmlspecialchars(current_user()['name']);?></div></div></div>
  <div><a href="logout.php" class="btn">Logout</a></div>
</div>
<div class="container">
  <aside class="sidebar">
    <nav class="menu">
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="posts.php">Content</a>
      <a href="../index.html" target="_blank">View Site</a>
    </nav>
    <div class="small" style="margin-top:12px">Role: <?php echo htmlspecialchars(current_user()['role']);?></div>
  </aside>
  <main class="content">
    <div class="card">
      <h3>Overview</h3>
      <div class="form-row">
        <?php
        $pdo = getPDO();
        $userCount = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
        $postCount = $pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
        ?>
        <div style="flex:1;padding:10px"><strong><?php echo $userCount;?></strong><div class="small">Users</div></div>
        <div style="flex:1;padding:10px"><strong><?php echo $postCount;?></strong><div class="small">Content Items</div></div>
      </div>
    </div>
    <div class="card">
      <h3>Quick actions</h3>
      <a class="btn btn-primary" href="users.php">Manage Users</a>
      <a class="btn" href="posts.php" style="margin-left:8px">Manage Content</a>
    </div>
  </main>
</div>
<div class="footer">Made with ❤️ — Admin</div>
</body></html>
