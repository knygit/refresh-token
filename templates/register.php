<?php $title = 'Register'; ob_start(); ?>

<h1>Register</h1>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="POST" action="/register">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autofocus
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="password">Password (min. 8 characters)</label>
        <input type="password" id="password" name="password" required minlength="8">
    </div>
    <div class="form-group">
        <label for="password_confirm">Confirm Password</label>
        <input type="password" id="password_confirm" name="password_confirm" required>
    </div>
    <button type="submit" class="btn">Register</button>
</form>

<p>Already have an account? <a href="/login">Login</a></p>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
