<?php $title = 'Welcome'; ob_start(); ?>

<h1>Refresh Token Service</h1>
<p>Log in with your Microsoft account to get a refresh token for your bot.</p>
<a href="/login" class="btn">Login with Microsoft</a>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
