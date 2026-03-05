<?php $title = 'Login'; ob_start(); ?>

<h1>Login</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="/login">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Login</button>
</form>

<p>Don't have an account? <a href="/register">Register</a></p>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
