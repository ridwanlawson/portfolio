<?php
// Database configuration
$db_file = __DIR__ . '/portfolio.db';

// Ensure admin directory exists
if (!is_dir(__DIR__)) {
    mkdir(__DIR__, 0755, true);
}

// Create database file if it doesn't exist
if (!file_exists($db_file)) {
    touch($db_file);
    chmod($db_file, 0666);
}

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Enable foreign keys
    $pdo->exec("PRAGMA foreign_keys = ON");
    $pdo->exec("PRAGMA journal_mode = WAL");

    // Create tables if they don't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS profile (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        title TEXT NOT NULL,
        about_text TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT NOT NULL,
        birthday TEXT NOT NULL,
        location TEXT NOT NULL,
        avatar TEXT NOT NULL DEFAULT 'my-avatar.png'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        icon TEXT NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS projects (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        category TEXT NOT NULL,
        description TEXT NOT NULL,
        image TEXT NOT NULL,
        link TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        category TEXT NOT NULL,
        content TEXT NOT NULL,
        image TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS experience (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        company TEXT NOT NULL,
        period TEXT NOT NULL,
        description TEXT NOT NULL,
        type TEXT NOT NULL DEFAULT 'experience'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_info (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL,
        phone TEXT NOT NULL,
        birthday TEXT NOT NULL,
        location TEXT NOT NULL,
        address TEXT NOT NULL DEFAULT ''
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS social_media (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        platform TEXT NOT NULL,
        username TEXT NOT NULL,
        url TEXT NOT NULL,
        icon TEXT NOT NULL
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        position TEXT NOT NULL,
        avatar TEXT NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS skills (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        percentage INTEGER NOT NULL,
        category TEXT NOT NULL DEFAULT 'technical'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS what_i_do (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        description TEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS map_info (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL DEFAULT 'My Location',
        embed_code TEXT NOT NULL,
        address TEXT NOT NULL,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS clients (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        logo TEXT NOT NULL,
        website TEXT,
        description TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create map table
    $pdo->exec("CREATE TABLE IF NOT EXISTS map (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        embed_url TEXT NOT NULL,
        address TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create contact messages table
    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        fullname TEXT NOT NULL,
        email TEXT NOT NULL,
        message TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status TEXT DEFAULT 'unread'
    )");

    // Insert sample data if tables are empty
    $count = $pdo->query("SELECT COUNT(*) FROM profile")->fetchColumn();
    if ($count == 0) {
        $pdo->exec("INSERT INTO profile (name, title, about_text, email, phone, birthday, location) VALUES 
            ('Richard Hanrick', 'Web Developer', 'I''m Creative Director and UI/UX Designer from Sydney, Australia, working in web development and print media. I enjoy turning complex problems into simple, beautiful and intuitive designs.', 'richard@example.com', '+1 (213) 352-2795', 'June 23, 1982', 'Sacramento, California, USA')");
    }

    // Insert sample data for services
    $stmt = $pdo->query("SELECT COUNT(*) FROM services");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO services (title, description, icon) VALUES 
            ('Web design', 'The most modern and high-quality design made at a professional level.', 'icon-design.svg'),
            ('Web development', 'High-quality development of sites at the professional level.', 'icon-dev.svg'),
            ('Mobile apps', 'Professional development of applications for iOS and Android.', 'icon-app.svg'),
            ('Photography', 'I make high-quality photos of any category at a professional level.', 'icon-photo.svg')");
    }

    // Insert sample data for projects
    $stmt = $pdo->query("SELECT COUNT(*) FROM projects");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO projects (title, category, description, image, link) VALUES 
            ('Finance Dashboard', 'web development', 'A modern dashboard for financial management with real-time data visualization.', 'project-1.jpg', 'https://example.com/finance'),
            ('E-commerce Website', 'web development', 'Full-featured online store with payment integration and inventory management.', 'project-2.png', 'https://example.com/shop'),
            ('Mobile Banking App', 'applications', 'Secure mobile banking application with biometric authentication.', 'project-3.jpg', 'https://example.com/banking'),
            ('Brand Identity Design', 'web design', 'Complete brand identity package including logo, colors, and typography.', 'project-4.png', 'https://example.com/brand')");
    }

    // Insert sample data for blog
    $stmt = $pdo->query("SELECT COUNT(*) FROM blog_posts");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO blog_posts (title, category, content, image) VALUES 
            ('Best Practices for Web Development', 'Development', 'Learn about the latest best practices in modern web development including performance optimization and security.', 'blog-1.jpg'),
            ('UI/UX Design Trends 2025', 'Design', 'Explore the latest design trends that will shape user experiences in 2025.', 'blog-2.jpg'),
            ('Mobile-First Design Approach', 'Design', 'Why mobile-first design is essential in today''s digital landscape.', 'blog-3.jpg'),
            ('JavaScript Performance Tips', 'Development', 'Optimize your JavaScript code for better performance and user experience.', 'blog-4.jpg')");
    }

    // Insert sample experience data
    $stmt = $pdo->query("SELECT COUNT(*) FROM experience");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO experience (title, company, period, description, type) VALUES 
            ('Creative director', 'Company ABC', '2015 — Present', 'Leading creative teams and managing design projects for major clients.', 'experience'),
            ('Web designer', 'Company XYZ', '2013 — 2015', 'Designed user interfaces and user experiences for web applications.', 'experience'),
            ('University of California', 'Bachelor degree', '2007 — 2013', 'Computer Science and Engineering studies.', 'education'),
            ('New York Academy of Art', 'Art studies', '2006 — 2007', 'Fine arts and digital design fundamentals.', 'education')");
    }

    // Insert contact info
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_info");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO contact_info (email, phone, birthday, location, address) VALUES 
            ('richard@example.com', '+1 (213) 352-2795', 'June 23, 1982', 'Sacramento, California, USA', '2321 New Design Str, Lorem City, California, USA')");
    }

    // Insert sample social media
    $stmt = $pdo->query("SELECT COUNT(*) FROM social_media");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO social_media (platform, username, url, icon) VALUES 
            ('Facebook', 'richardhanrick', 'https://facebook.com/richardhanrick', 'ion-logo-facebook'),
            ('Twitter', 'richardhanrick', 'https://twitter.com/richardhanrick', 'ion-logo-twitter'),
            ('Instagram', 'richardhanrick', 'https://instagram.com/richardhanrick', 'ion-logo-instagram'),
            ('LinkedIn', 'richardhanrick', 'https://linkedin.com/in/richardhanrick', 'ion-logo-linkedin')");
    }

    // Insert sample testimonials
    $stmt = $pdo->query("SELECT COUNT(*) FROM testimonials");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO testimonials (name, position, avatar, content) VALUES 
            ('Daniel Lewis', 'CEO at ABC Company', 'avatar-1.png', 'Richard was hired to create a corporate identity. We were very pleased with the work done. She has a lot of experience and is very concerned about the needs of client.'),
            ('Jessica Miller', 'Marketing Director', 'avatar-2.png', 'Richard was hired to create a corporate identity. We were very pleased with the work done. She has a lot of experience and is very concerned about the needs of client.'),
            ('Emily Evans', 'Product Manager', 'avatar-3.png', 'Richard was hired to create a corporate identity. We were very pleased with the work done. She has a lot of experience and is very concerned about the needs of client.'),
            ('Henry William', 'Tech Lead', 'avatar-4.png', 'Richard was hired to create a corporate identity. We were very pleased with the work done. She has a lot of experience and is very concerned about the needs of client.')");
    }

    // Insert sample skills
    $stmt = $pdo->query("SELECT COUNT(*) FROM skills");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO skills (name, percentage, category) VALUES 
            ('Web design', 80, 'technical'),
            ('Graphic design', 70, 'technical'),
            ('Branding', 90, 'technical'),
            ('WordPress', 50, 'technical')");
    }

    // Insert what I do section
    $stmt = $pdo->query("SELECT COUNT(*) FROM what_i_do");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO what_i_do (title, description) VALUES 
            ('What I''m Doing', 'I am passionate about creating innovative solutions and bringing ideas to life through design and development.')");
    }

    // Insert map info
    $stmt = $pdo->query("SELECT COUNT(*) FROM map_info");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO map_info (title, embed_code, address) VALUES 
            ('My Location', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63245.517334049314!2d112.68708302167967!3d-7.275398999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf8381ac47f%3A0x3027a76e352be40!2sSurabaya%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1647854923456!5m2!1sen!2sid\" width=\"100%\" height=\"300\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 'Surabaya, East Java, Indonesia')");
    }

    // Insert sample clients
    $stmt = $pdo->query("SELECT COUNT(*) FROM clients");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO clients (name, logo, website) VALUES 
            ('Company A', 'logo-1-color.png', 'https://example.com'),
            ('Company B', 'logo-2-color.png', 'https://example.com'),
            ('Company C', 'logo-3-color.png', 'https://example.com'),
            ('Company D', 'logo-4-color.png', 'https://example.com'),
            ('Company E', 'logo-5-color.png', 'https://example.com'),
            ('Company F', 'logo-6-color.png', 'https://example.com')");
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>