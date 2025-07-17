
<?php
echo "<h2>XAMPP Portfolio Diagnostic</h2>";
echo "<h3>1. Checking PHP Version:</h3>";
echo "PHP Version: " . phpversion() . "<br>";

echo "<h3>2. Checking SQLite Support:</h3>";
if (extension_loaded('sqlite3')) {
    echo "✓ SQLite3 extension is loaded<br>";
} else {
    echo "✗ SQLite3 extension is NOT loaded<br>";
}

if (extension_loaded('pdo_sqlite')) {
    echo "✓ PDO SQLite extension is loaded<br>";
} else {
    echo "✗ PDO SQLite extension is NOT loaded<br>";
}

echo "<h3>3. Checking Database File:</h3>";
$db_file = __DIR__ . '/admin/portfolio.db';
echo "Database path: " . $db_file . "<br>";
echo "File exists: " . (file_exists($db_file) ? '✓ Yes' : '✗ No') . "<br>";
if (file_exists($db_file)) {
    echo "File size: " . filesize($db_file) . " bytes<br>";
    echo "File readable: " . (is_readable($db_file) ? '✓ Yes' : '✗ No') . "<br>";
    echo "File writable: " . (is_writable($db_file) ? '✓ Yes' : '✗ No') . "<br>";
}

echo "<h3>4. Testing Database Connection:</h3>";
try {
    require_once 'admin/config.php';
    echo "✓ Database connection successful<br>";
    
    $count = $pdo->query("SELECT COUNT(*) FROM profile")->fetchColumn();
    echo "Profile records: " . $count . "<br>";
    
    if ($count > 0) {
        $profile = $pdo->query("SELECT name, title FROM profile LIMIT 1")->fetch();
        echo "Sample profile: " . $profile['name'] . " - " . $profile['title'] . "<br>";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "<br>";
}

echo "<h3>5. Testing API Endpoints:</h3>";
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
$endpoints = ['profile', 'services', 'projects', 'blog'];

foreach ($endpoints as $endpoint) {
    $url = $base_url . '/api.php?action=' . $endpoint;
    echo "Testing: <a href='$url' target='_blank'>$endpoint</a> - ";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);
    
    $result = @file_get_contents($url, false, $context);
    if ($result !== false) {
        $data = json_decode($result, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "✓ OK (" . (is_array($data) ? count($data) : '1') . " records)<br>";
        } else {
            echo "✗ Invalid JSON<br>";
        }
    } else {
        echo "✗ Failed to fetch<br>";
    }
}

echo "<h3>6. Directory Permissions:</h3>";
echo "Current directory: " . __DIR__ . "<br>";
echo "Directory writable: " . (is_writable(__DIR__) ? '✓ Yes' : '✗ No') . "<br>";
echo "Admin directory: " . __DIR__ . '/admin<br>';
echo "Admin directory writable: " . (is_writable(__DIR__ . '/admin') ? '✓ Yes' : '✗ No') . "<br>";

echo "<hr>";
echo "<p><strong>Quick Actions:</strong></p>";
echo "<p><a href='init_db.php'>Run Database Initialization</a></p>";
echo "<p><a href='index.html'>View Portfolio</a></p>";
echo "<p><a href='admin/'>Admin Panel</a></p>";
?>
