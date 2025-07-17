
<?php
// Database initialization script
// Run this file once after extracting to ensure database is properly set up

require_once 'admin/config.php';

echo "<!DOCTYPE html><html><head><title>Database Initialization</title></head><body>";
echo "<h2>Portfolio Database Initialization</h2>";

try {
    // Test database connection
    $test = $pdo->query("SELECT COUNT(*) FROM profile")->fetchColumn();
    
    if ($test > 0) {
        echo "<p style='color: green;'>✓ Database is properly initialized with data!</p>";
        echo "<p>Profile records: " . $test . "</p>";
        
        // Test other tables
        $services_count = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();
        $projects_count = $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn();
        $blog_count = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        
        echo "<p>Services: " . $services_count . "</p>";
        echo "<p>Projects: " . $projects_count . "</p>";
        echo "<p>Blog posts: " . $blog_count . "</p>";
        
        echo "<p><strong>Your portfolio is ready to use!</strong></p>";
        echo "<p><a href='index.html'>View Portfolio</a> | <a href='admin/'>Admin Panel</a></p>";
        
    } else {
        echo "<p style='color: red;'>✗ Database needs initialization...</p>";
        echo "<p>Please check your file permissions and try again.</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
    echo "<p>Database file location: " . $db_file . "</p>";
    echo "<p>File exists: " . (file_exists($db_file) ? 'Yes' : 'No') . "</p>";
    echo "<p>File writable: " . (is_writable($db_file) ? 'Yes' : 'No') . "</p>";
}

echo "</body></html>";
?>
