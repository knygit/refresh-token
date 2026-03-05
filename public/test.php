<?php

echo "<h1>Server Debug Info</h1>";
echo "<pre>";

echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SERVER_SOFTWARE: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "HTTPS: " . ($_SERVER['HTTPS'] ?? 'not set') . "\n";
echo "HTTP_CF_CONNECTING_IP: " . ($_SERVER['HTTP_CF_CONNECTING_IP'] ?? 'not set') . "\n";
echo "HTTP_CF_VISITOR: " . ($_SERVER['HTTP_CF_VISITOR'] ?? 'not set') . "\n";
echo "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";

echo "\n--- .htaccess check ---\n";
echo ".htaccess exists: " . (file_exists(__DIR__ . '/.htaccess') ? 'YES' : 'NO') . "\n";
echo "mod_rewrite loaded: " . (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules()) ? 'YES' : 'unknown (not Apache handler)') . "\n";

echo "\n--- File structure ---\n";
echo "index.php exists: " . (file_exists(__DIR__ . '/index.php') ? 'YES' : 'NO') . "\n";
echo "Parent dir files:\n";
foreach (scandir(dirname(__DIR__)) as $f) {
    if ($f[0] !== '.') echo "  $f\n";
}

echo "\n--- Current dir files ---\n";
foreach (scandir(__DIR__) as $f) {
    if ($f[0] !== '.') echo "  $f\n";
}

echo "</pre>";
