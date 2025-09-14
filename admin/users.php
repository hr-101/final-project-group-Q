<?php
require_once __DIR__ . '/functions.php';
require_login();
$pdo = getPDO();

// Handle create/update/delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        $name = $_POST['name']; $email = $_POST['email']; $role = $_POST['role'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role,created_at) VALUES (?,?,?,?,NOW())');
        $stmt->execute([$name,$email,$password,$role]);
        header('Location: users.php'); exit;
    }
    if (isset($_POST['update'])) {
        $id = intval($_POST['id']); $name = $_POST['name']; $email = $_POST['email']; $role = $_POST['role'];
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, password=?, role=? WHERE id=?');
            $stmt->execute([$name,$email,$password,$role,$id]);
        } else {
            $stmt = $pdo->prepare('UPDATE users SET name=?, email=?, role=? WHERE id=?');
            $stmt->execute([$name,$email,$role,$id]);
        }
        header('Location: users.php'); exit;
    }
    if (isset($_POST['delete'])) {
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: users.php'); exit;
    }
}

// Fetch users
$users = $pdo->query('SELECT id,name,email,role,created_at FROM users ORDER BY id DESC')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="assets/css/admin.css"><title>Manage Users</title></head><body>
<div class="header">
  <div class="brand"><div class="logo">A</div><div><strong>Admin Panel</strong></div></div>
  <div><a href="logout.php" class="btn">Logout</a></div>
</div>
<div class="container">
  <aside class="sidebar">
    <nav class="menu">
      <a href="dashboard.php">Dashboard</a>
      <a href="users.php" class="active">Users</a>
      <a href="posts.php">Content</a>
    </nav>
  </aside>
  <main class="content">
    <div class="card">
      <h3>Create user</h3>
      <form method="post">
        <div class="form-row" style="gap:8px">
          <input class="input" name="name" placeholder="Full name" required>
          <input class="input" name="email" type="email" placeholder="Email" required>
          <input class="input" name="password" type="password" placeholder="Password" required>
          <select name="role" class="input">
            <option value="editor">Editor</option>
            <option value="author">Author</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div style="margin-top:8px"><button class="btn btn-primary" name="create" type="submit">Create</button></div>
      </form>
    </div>

    <div class="card">
      <h3>Existing users</h3>
      <table class="table"><thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr></thead><tbody>
      <?php foreach($users as $u): ?>
        <tr>
          <td><?php echo $u['id'];?></td>
          <td><?php echo htmlspecialchars($u['name']);?></td>
          <td><?php echo htmlspecialchars($u['email']);?></td>
          <td><?php echo htmlspecialchars($u['role']);?></td>
          <td><?php echo $u['created_at'];?></td>
          <td>
            <button onclick="editUser(<?php echo $u['id'];?>,'<?php echo htmlspecialchars(addslashes($u['name']));?>','<?php echo htmlspecialchars($u['email']);?>','<?php echo $u['role'];?>')" class="btn small">Edit</button>
            <form method="post" style="display:inline" onsubmit="return confirm('Delete user?')">
              <input type="hidden" name="id" value="<?php echo $u['id'];?>">
              <button class="btn btn-danger" name="delete" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach;?>
      </tbody></table>
    </div>

    <div class="card" id="editCard" style="display:none">
      <h3>Edit user</h3>
      <form method="post">
        <input type="hidden" name="id" id="edit_id">
        <div class="form-row">
          <input class="input" id="edit_name" name="name" placeholder="Full name" required>
          <input class="input" id="edit_email" name="email" type="email" placeholder="Email" required>
          <input class="input" name="password" type="password" placeholder="Leave blank to keep password">
          <select name="role" id="edit_role" class="input">
            <option value="editor">Editor</option>
            <option value="author">Author</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <div style="margin-top:8px"><button class="btn btn-primary" name="update" type="submit">Update</button></div>
      </form>
    </div>

  </main>
</div>
<script>
function editUser(id,name,email,role){
  document.getElementById('editCard').style.display='block';
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_name').value = name;
  document.getElementById('edit_email').value = email;
  document.getElementById('edit_role').value = role;
  window.scrollTo({top:0,behavior:'smooth'});
}
</script>
</body></html>
