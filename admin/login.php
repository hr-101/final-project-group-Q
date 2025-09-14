<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email = $_POST['email'] ?? '';
    $pass = $_POST['password'] ?? '';
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($pass, $user['password'])) {
        // remove password before storing in session
        unset($user['password']);
        $_SESSION['user'] = $user;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="assets/css/admin.css">
<title>Admin Login</title>
</head><body>
<div style="max-width:420px;margin:80px auto;">
<div class="card">
<h2 style="margin-top:0">Admin Login</h2>
<?php if(!empty($error)): ?><div style="color:#ffb3b3;padding:8px;border-radius:6px;background:#2b0b0b;margin-bottom:10px;"><?php echo htmlspecialchars($error);?></div><?php endif; ?>
<form method="post">
<label class="small">Email</label>
<input class="input" name="email" type="email" required>
<label class="small">Password</label>
<input class="input" name="password" type="password" required>
<div style="margin-top:12px"><button class="btn btn-primary" type="submit">Sign in</button></div>
</form>
</div>
<div style="text-align:center;margin-top:12px;color:#94a3b8">login as an admin</div>
</div>
</body></html>
