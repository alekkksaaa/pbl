<?php
// Clear Bootstrap Cache
$bootstrapCacheDir = __DIR__ . '/bootstrap/cache';

if (file_exists($bootstrapCacheDir . '/services.php')) {
    unlink($bootstrapCacheDir . '/services.php');
}
if (file_exists($bootstrapCacheDir . '/packages.php')) {
    unlink($bootstrapCacheDir . '/packages.php');
}

// Reload index.php
require_once 'public/index.php';
