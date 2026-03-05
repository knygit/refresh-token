<?php $title = 'Error'; ob_start(); ?>

<h1>Something went wrong</h1>
<p><?= htmlspecialchars($error ?? 'An unexpected error occurred.') ?></p>
<a href="/" class="btn">Back to home</a>

<?php $content = ob_get_clean(); require __DIR__ . '/layout.php'; ?>
