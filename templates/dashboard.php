<?php $title = 'Dashboard'; ob_start(); ?>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <div>
        <span><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</span>
        <a href="/logout" class="btn btn-small">Logout</a>
    </div>
</div>

<div class="card">
    <h2>Your Refresh Token</h2>

    <div class="alert alert-warning">
        <strong>WARNING:</strong> Treat your refresh token like a password. Never share it with anyone you do not trust.
        If this token is compromised, an attacker can gain access to your account. If you suspect misuse, immediately
        log out and revoke access in your Microsoft account settings.
    </div>

    <?php if ($refreshToken): ?>
        <p>Copy this token and give it to your bot. The bot uses it to request new access tokens via the API.</p>
        <div class="token-display">
            <code id="refresh-token"><?= htmlspecialchars($refreshToken) ?></code>
            <button type="button" class="btn btn-small" onclick="copyToken()">Copy</button>
        </div>
    <?php else: ?>
        <div class="alert alert-error">
            No refresh token was issued. Make sure <code>offline_access</code> is included in the scopes.
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <h2>API Usage</h2>
    <p>Your bot can refresh the access token by calling:</p>
    <pre><code>POST /api/token/refresh
Content-Type: application/json

{"refresh_token": "YOUR_REFRESH_TOKEN"}</code></pre>
    <p>Response:</p>
    <pre><code>{"access_token": "...", "refresh_token": "..."}</code></pre>
    <p><strong>Note:</strong> Each refresh returns a new refresh token. The bot must store the latest one.</p>
</div>

<script>
function copyToken() {
    var token = document.getElementById('refresh-token').textContent;
    navigator.clipboard.writeText(token).then(function() {
        alert('Token copied to clipboard!');
    });
}
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
