<?php
$title = 'Dashboard';
$baseUrl = (isBehindHttps() ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
ob_start();
?>

<div class="dashboard-header">
    <h1>Dashboard</h1>
    <div class="dashboard-user">
        <span><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</span>
        <a href="/logout" class="btn btn-small">Logout</a>
    </div>
</div>

<div class="card">
    <h2>Your Refresh Token</h2>

    <div class="alert alert-warning">
        <strong>WARNING:</strong> Treat your refresh token like a password. Never share it with anyone you do not trust.
        If this token is compromised, an attacker can gain access to your account.
    </div>

    <?php if ($refreshToken): ?>
        <?php $visibleLength = (int) ceil(strlen($refreshToken) * 0.25); ?>
        <p>Copy this token and give it to your bot. The bot uses it to request new access tokens via the API.</p>
        <div class="token-display">
            <code id="refresh-token-preview"><?= htmlspecialchars(substr($refreshToken, 0, $visibleLength)) ?><span class="token-masked"><?= str_repeat('•', min(40, strlen($refreshToken) - $visibleLength)) ?></span></code>
            <input type="hidden" id="refresh-token-full" value="<?= htmlspecialchars($refreshToken) ?>">
            <button type="button" class="btn btn-small" onclick="copyToken()">Copy</button>
        </div>
    <?php else: ?>
        <div class="alert alert-error">
            No refresh token was issued. Make sure <code>offline_access</code> is included in the scopes.
        </div>
    <?php endif; ?>
</div>

<div class="card card-danger">
    <h2>Emergency: Revoke All Sessions</h2>
    <p>If you believe your token has been compromised, immediately revoke all active sessions.
       This will sign you out everywhere and invalidate all tokens.</p>
    <form method="post" action="/revoke" onsubmit="return confirm('This will revoke ALL active sessions and sign you out everywhere. Continue?');">
        <button type="submit" class="btn btn-danger">Revoke All Sessions &amp; Sign Out</button>
    </form>
</div>

<div class="card">
    <h2>API Usage</h2>
    <p>Your bot can refresh the access token by calling:</p>
    <pre><code>POST <?= htmlspecialchars($baseUrl) ?>/api/token/refresh
Content-Type: application/json

{"refresh_token": "YOUR_REFRESH_TOKEN"}</code></pre>
    <p>Response:</p>
    <pre><code>{"access_token": "...", "refresh_token": "..."}</code></pre>
    <p><strong>Note:</strong> Each refresh returns a new refresh token. The bot must store the latest one.</p>
</div>

<script>
function copyToken() {
    var token = document.getElementById('refresh-token-full').value;
    navigator.clipboard.writeText(token).then(function() {
        var btn = document.querySelector('.token-display .btn');
        btn.textContent = 'Copied!';
        setTimeout(function() { btn.textContent = 'Copy'; }, 2000);
    });
}
</script>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
