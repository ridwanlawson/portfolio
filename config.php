
<?php
// Main configuration file
define('ROOT_PATH', __DIR__);
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('DB_PATH', ADMIN_PATH . '/portfolio.db');

// Ensure database file exists and is writable
if (!file_exists(DB_PATH)) {
    if (!is_dir(ADMIN_PATH)) {
        mkdir(ADMIN_PATH, 0755, true);
    }
    touch(DB_PATH);
    chmod(DB_PATH, 0666);
}

try {
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Enable foreign keys
    $pdo->exec("PRAGMA foreign_keys = ON");
    $pdo->exec("PRAGMA journal_mode = WAL");

    // Create all tables
    require_once ADMIN_PATH . '/config.php';
    
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
