<?php
require_once __DIR__ . '/functions.php';
require_login();
$pdo = getPDO();

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $title = $_POST['title']; $content = $_POST['content'];
        $stmt = $pdo->prepare('INSERT INTO posts (title,content,created_at) VALUES (?,?,NOW())');
        $stmt->execute([$title,$content]);
        header('Location: posts.php'); exit;
    }
    if (isset($_POST['update'])) {
        $id = intval($_POST['id']); $title = $_POST['title']; $content = $_POST['content'];
        $stmt = $pdo->prepare('UPDATE posts SET title=?, content=?, updated_at=NOW() WHERE id=?');
        $stmt->execute([$title,$content,$id]);
        header('Location: posts.php'); exit;
    }
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: posts.php'); exit;
    }
}

// Fetch posts
$posts = $pdo->query('SELECT id,title,created_at,updated_at FROM posts ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="assets/css/admin.css"><title>Manage Content</title></head><body>
<div class="header">
  <div class="brand"><div class="logo">A</div><div><strong>Admin Panel</strong></div></div>
  <div><a href="logout.php" class="btn">Logout</a></div>
</div>
<div class="container">
  <aside class="sidebar">
    <nav class="menu">
      <a href="dashboard.php">Dashboard</a>
      <a href="users.php">Users</a>
      <a href="posts.php" class="active">Content</a>
    </nav>
  </aside>
  <main class="content">
    <div class="card">
      <h3>Create content item</h3>
      <form method="post">
        <input class="input" name="title" placeholder="Title" required>
        <textarea class="input" name="content" rows="6" placeholder="Content" required></textarea>
        <div style="margin-top:8px"><button class="btn btn-primary" name="create" type="submit">Create</button></div>
      </form>
    </div>

    <div class="card">
      <h3>Existing items</h3>
      <table class="table"><thead><tr><th>ID</th><th>Title</th><th>Created</th><th>Updated</th><th>Actions</th></tr></thead><tbody>
      <?php foreach($posts as $p): ?>
        <tr>
          <td><?php echo $p['id'];?></td>
          <td><?php echo htmlspecialchars($p['title']);?></td>
          <td><?php echo $p['created_at'];?></td>
          <td><?php echo $p['updated_at'];?></td>
          <td>
            <button onclick="editPost(<?php echo $p['id'];?>,'<?php echo htmlspecialchars(addslashes($p['title']));?>')" class="btn small">Edit</button>
            <form method="post" style="display:inline" onsubmit="return confirm('Delete item?')">
              <input type="hidden" name="id" value="<?php echo $p['id'];?>">
              <button class="btn btn-danger" name="delete" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach;?>
      </tbody></table>
    </div>

    <div class="card" id="editCard" style="display:none">
      <h3>Edit item</h3>
      <form method="post">
        <input type="hidden" name="id" id="edit_id">
        <input class="input" id="edit_title" name="title" placeholder="Title" required>
        <textarea class="input" id="edit_content" name="content" rows="6" placeholder="Content" required></textarea>
        <div style="margin-top:8px"><button class="btn btn-primary" name="update" type="submit">Update</button></div>
      </form>
    </div>

  </main>
</div>
<script>
function editPost(id,title){
  document.getElementById('editCard').style.display='block';
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_title').value = title;
  // fetch content via AJAX? simple approach: fill content with placeholder
  document.getElementById('edit_content').value = 'Open the item to edit content in the DB or implement AJAX.';
  window.scrollTo({top:0,behavior:'smooth'});
}
</script>
</body></html>
