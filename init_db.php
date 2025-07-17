
<?php
// Database initialization script
// Run this file once after extracting to ensure database is properly set up

echo "<!DOCTYPE html><html><head><title>Database Initialization</title>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.success { color: green; }
.error { color: red; }
.info { color: blue; }
</style>
</head><body>";
echo "<h2>Portfolio Database Initialization</h2>";

// Check if admin/config.php exists
if (!file_exists('admin/config.php')) {
    echo "<p class='error'>✗ admin/config.php file not found!</p>";
    echo "<p>Please make sure you extracted all files correctly.</p>";
    echo "</body></html>";
    exit;
}

try {
    require_once 'admin/config.php';
    echo "<p class='success'>✓ Configuration file loaded successfully</p>";
    
    // Test database connection
    $test = $pdo->query("SELECT COUNT(*) FROM profile")->fetchColumn();
    
    if ($test > 0) {
        echo "<p class='success'>✓ Database is properly initialized with data!</p>";
        echo "<p class='info'>Profile records: " . $test . "</p>";
        
        // Test other tables
        $services_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
        $projects_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $blog_count = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        
        echo "<p class='info'>Services: " . $services_count . "</p>";
        echo "<p class='info'>Projects: " . $projects_count . "</p>";
        echo "<p class='info'>Blog posts: " . $blog_count . "</p>";
        
        echo "<p><strong>Your portfolio is ready to use!</strong></p>";
        echo "<p><a href='index.html' target='_blank'>View Portfolio</a> | <a href='admin/' target='_blank'>Admin Panel</a></p>";
        
        // Test API endpoints
        echo "<h3>Testing API Endpoints:</h3>";
        $endpoints = ['profile', 'services', 'projects'];
        foreach ($endpoints as $endpoint) {
            $url = "api.php?action=" . $endpoint;
            echo "<p>• <a href='$url' target='_blank'>$endpoint API</a></p>";
        }
        
    } else {
        echo "<p class='error'>✗ Database tables exist but no data found</p>";
        echo "<p>This might be normal on first run. Try refreshing this page.</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>Error: " . $e->getMessage() . "</p>";
    echo "<h3>Debugging Information:</h3>";
    echo "<p>Current directory: " . __DIR__ . "</p>";
    echo "<p>Database file path: " . (isset($db_file) ? $db_file : 'Not set') . "</p>";
    
    if (isset($db_file)) {
        echo "<p>File exists: " . (file_exists($db_file) ? 'Yes' : 'No') . "</p>";
        if (file_exists($db_file)) {
            echo "<p>File size: " . filesize($db_file) . " bytes</p>";
            echo "<p>File readable: " . (is_readable($db_file) ? 'Yes' : 'No') . "</p>";
            echo "<p>File writable: " . (is_writable($db_file) ? 'Yes' : 'No') . "</p>";
        }
    }
    
    echo "<p><strong>Try:</strong></p>";
    echo "<ol>";
    echo "<li>Check that Apache is running in XAMPP</li>";
    echo "<li>Make sure SQLite is enabled in PHP</li>";
    echo "<li>Check file permissions</li>";
    echo "<li>Run <a href='xampp_check.php'>XAMPP Diagnostic Tool</a></li>";
    echo "</ol>";
}

echo "</body></html>";
?>
