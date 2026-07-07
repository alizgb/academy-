<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/config/auth.php';

if (is_logged_in()) { header('Location: /dashboard.php'); exit; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        header('Location: ' . ($user['role'] === 'admin' ? '/admin/index.php' : '/dashboard.php'));
        exit;
    } else {
        $errors[] = 'Incorrect email or password.';
    }
}

$page_title = 'Login';
include __DIR__ . '/includes/header.php';
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <a href="/index.php" class="logo">
                <span class="logo-mark">TC</span>
            </a>
            <h1>Welcome back</h1>
            <p>Log in to access your courses.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="form-error"><?= htmlspecialchars(implode(' ', $errors)) ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="/register.php">Enroll now</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
