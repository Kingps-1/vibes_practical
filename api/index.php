<?php

// Ensure /tmp writable directories exist for Laravel on Vercel serverless
if (!file_exists('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}
if (!file_exists('/tmp/sessions')) {
    mkdir('/tmp/sessions', 0755, true);
}

// Copy pre-seeded SQLite database to writable /tmp directory if not present
$sourceDb = __DIR__ . '/../database/database.sqlite';
$targetDb = '/tmp/database.sqlite';
if (file_exists($sourceDb) && !file_exists($targetDb)) {
    copy($sourceDb, $targetDb);
}

// Forward request to Laravel's public entry point
require __DIR__ . '/../public/index.php';
