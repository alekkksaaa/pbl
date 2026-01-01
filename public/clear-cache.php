<?php
// Force clear all Laravel cache
exec('cd ' . __DIR__ . ' && php artisan cache:clear 2>&1', $output);
exec('cd ' . __DIR__ . ' && php artisan config:clear 2>&1', $output);

if (function_exists('opcache_reset')) {
    opcache_reset();
}

echo "<pre>";
echo "Cache cleared.\n";
echo implode("\n", $output);
echo "</pre>";
echo "<p><a href='/pbl4/public/api/admin/login'>Back to app</a></p>";
