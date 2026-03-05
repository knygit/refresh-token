<?php $title = 'Dashboard'; ob_start(); ?>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <div>
        <span>Logged in as <strong><?= htmlspecialchars($user['username']) ?></strong></span>
        <a href="/logout" class="btn btn-small">Logout</a>
    </div>
</div>

<div class="card">
    <h2>Refresh Token</h2>

    <div class="alert alert-warning">
        <strong>WARNING:</strong> Treat your refresh token like a password. Never share it with anyone you do not trust.
        If this token is compromised, an attacker can gain access to your account. If you suspect misuse, immediately use
        the "Log me out everywhere" feature.
    </div>

    <?php if ($refreshToken): ?>
        <p>Here is your new refresh token. <strong>Copy it now</strong> — it will not be shown again.</p>
        <div class="token-display">
            <code id="refresh-token"><?= htmlspecialchars($refreshToken) ?></code>
            <button type="button" class="btn btn-small" onclick="copyToken()">Copy</button>
        </div>
    <?php elseif ($hasToken): ?>
        <p>You already have an active refresh token. Generate a new one to replace it.</p>
    <?php else: ?>
        <p>You have no active refresh token. Generate one to use with external bots.</p>
    <?php endif; ?>

    <form method="POST" action="/dashboard">
        <input type="hidden" name="generate_token" value="1">
        <button type="submit" class="btn"><?= $hasToken ? 'Regenerate Token' : 'Generate Token' ?></button>
    </form>
</div>

<div class="card">
    <h2>Security</h2>
    <p>Click the button below to invalidate all your sessions and refresh tokens across all devices.</p>
    <form method="POST" action="/dashboard/logout-everywhere">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure? This will log you out of all devices and invalidate all tokens.')">
            Log me out everywhere
        </button>
    </form>
</div>

<script>
function copyToken() {
    const token = document.getElementById('refresh-token').textContent;
    navigator.clipboard.writeText(token).then(function() {
        alert('Token copied to clipboard!');
    });
}
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
