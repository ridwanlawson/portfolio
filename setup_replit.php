
<?php
echo "<!DOCTYPE html><html><head><title>Portfolio Setup for Replit</title></head><body>";
echo "<h2>Setting up Portfolio for Replit...</h2>";

// Force regenerate database
$db_file = __DIR__ . '/admin/portfolio.db';
if (file_exists($db_file)) {
    unlink($db_file);
    echo "<p>Removed existing database...</p>";
}

// Include config to recreate database
require_once 'admin/config.php';

echo "<h3>Database Status:</h3>";
try {
    // Test all tables
    $tables = ['profile', 'services', 'projects', 'blog_posts', 'experience', 'contact_info', 'social_media', 'testimonials', 'skills', 'what_i_do'];
    
    foreach ($tables as $table) {
        try {
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "<p>✓ $table: $count records</p>";
        } catch (Exception $e) {
            echo "<p>✗ $table: Error - " . $e->getMessage() . "</p>";
        }
    }
    
    echo "<h3>Testing API Endpoints:</h3>";
    echo "<p><a href='api.php?action=profile' target='_blank'>Test Profile API</a></p>";
    echo "<p><a href='api.php?action=services' target='_blank'>Test Services API</a></p>";
    echo "<p><a href='api.php?action=projects' target='_blank'>Test Projects API</a></p>";
    
    echo "<h3>Ready!</h3>";
    echo "<p><a href='index.html' target='_blank'>Open Portfolio</a></p>";
    echo "<p><a href='admin/' target='_blank'>Open Admin Panel</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "</body></html>";
?>
