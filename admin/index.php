<?php
require_once 'config.php';
session_start();

// Simple authentication (in production, use proper password hashing)
$admin_password = 'admin123';

if (isset($_POST['login'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Invalid password';
    }
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
            .login-form { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 4px; }
            button { width: 100%; padding: 10px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; }
            button:hover { background: #005a8a; }
            .error { color: red; margin-top: 10px; }
        </style>
    </head>
    <body>
        <div class="login-form">
            <h2>Admin Login</h2>
            <form method="post">
                <input type="password" name="password" placeholder="Enter password" required>
                <button type="submit" name="login">Login</button>
                <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .header { background: #333; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; }
        .nav { 
            display: flex; 
            gap: 10px; 
            flex-wrap: wrap;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .nav a { 
            color: #333; 
            text-decoration: none; 
            padding: 8px 12px; 
            border-radius: 4px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        .nav a:hover { 
            background: #e9ecef;
            border-color: #007cba;
        }
        .nav a.active { 
            background: #007cba; 
            color: white;
            border-color: #007cba;
        }
        .container { max-width: 1200px; margin: 20px auto; padding: 0 20px; }
        .card { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin: 15px 0; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .form-group textarea { height: 100px; resize: vertical; }
        .btn { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        .btn:hover { background: #005a8a; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .table th, .table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .table th { background: #f8f9fa; }
        .section { display: none; }
        .section.active { display: block; }
        .success { color: green; padding: 10px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin: 10px 0; }
        .error { color: red; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <div>
            <form method="post" style="display: inline;">
                <button type="submit" name="logout" class="btn">Logout</button>
            </form>
        </div>
    </div>

    <div class="container">
        <div class="nav">
            <a href="#" onclick="showSection('profile')" class="active">Profile</a>
            <a href="#" onclick="showSection('social')">Social Media</a>
            <a href="#" onclick="showSection('what-doing')">What I'm Doing</a>
            <a href="#" onclick="showSection('testimonials')">Testimonials</a>
            <a href="#" onclick="showSection('skills')">Skills</a>
            <a href="#" onclick="showSection('services')">Services</a>
            <a href="#" onclick="showSection('projects')">Projects</a>
            <a href="#" onclick="showSection('blog')">Blog</a>
            <a href="#" onclick="showSection('experience')">Experience</a>
            <a href="#" onclick="showSection('contact')">Contact</a>
            <a href="#" onclick="showSection('map')">Map</a>
            <a href="#" onclick="showSection('clients')">Clients</a>
            <a href="#" onclick="showSection('messages')">Messages</a>
        </div>

        <div id="profile" class="section active">
            <div class="card">
                <h2>Profile Management</h2>
                <?php
                if (isset($_POST['update_profile'])) {
                    $stmt = $pdo->prepare("UPDATE profile SET name = ?, title = ?, about_text = ?, email = ?, phone = ?, birthday = ?, location = ? WHERE id = 1");
                    $stmt->execute([$_POST['name'], $_POST['title'], $_POST['about_text'], $_POST['email'], $_POST['phone'], $_POST['birthday'], $_POST['location']]);
                    echo "<div class='success'>Profile updated successfully!</div>";
                }

                $profile = $pdo->query("SELECT * FROM profile WHERE id = 1")->fetch();
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($profile['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($profile['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>About Text:</label>
                        <textarea name="about_text" required><?php echo htmlspecialchars($profile['about_text']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($profile['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($profile['phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Birthday:</label>
                        <input type="text" name="birthday" value="<?php echo htmlspecialchars($profile['birthday']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Location:</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($profile['location']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn">Update Profile</button>
                </form>
            </div>
        </div>

        <div id="social" class="section">
            <div class="card">
                <h2>Social Media Management</h2>
                <?php
                if (isset($_POST['add_social'])) {
                    $stmt = $pdo->prepare("INSERT INTO social_media (platform, username, url, icon) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$_POST['platform'], $_POST['username'], $_POST['url'], $_POST['icon']]);
                    echo "<div class='success'>Social media added successfully!</div>";
                }

                if (isset($_POST['delete_social'])) {
                    $stmt = $pdo->prepare("DELETE FROM social_media WHERE id = ?");
                    $stmt->execute([$_POST['social_id']]);
                    echo "<div class='success'>Social media deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Platform:</label>
                        <input type="text" name="platform" placeholder="e.g., Facebook" required>
                    </div>
                    <div class="form-group">
                        <label>Username:</label>
                        <input type="text" name="username" placeholder="e.g., richardhanrick" required>
                    </div>
                    <div class="form-group">
                        <label>URL:</label>
                        <input type="url" name="url" placeholder="https://facebook.com/richardhanrick" required>
                    </div>
                    <div class="form-group">
                        <label>Icon Class:</label>
                        <input type="text" name="icon" placeholder="e.g., ion-logo-facebook" required onchange="previewIcon(this, 'social-icon-preview')">
                        <div id="social-icon-preview" style="margin-top: 10px; font-size: 24px;"></div>
                    </div>
                    <button type="submit" name="add_social" class="btn">Add Social Media</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Platform</th>
                            <th>Username</th>
                            <th>URL</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $socials = $pdo->query("SELECT * FROM social_media")->fetchAll();
                        foreach ($socials as $social) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($social['platform']) . "</td>";
                            echo "<td>" . htmlspecialchars($social['username']) . "</td>";
                            echo "<td><a href='" . htmlspecialchars($social['url']) . "' target='_blank'>" . htmlspecialchars(substr($social['url'], 0, 30)) . "...</a></td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='social_id' value='" . $social['id'] . "'>";
                            echo "<button type='submit' name='delete_social' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="what-doing" class="section">
            <div class="card">
                <h2>What I'm Doing Section</h2>
                <?php
                if (isset($_POST['update_what_doing'])) {
                    $stmt = $pdo->prepare("UPDATE what_i_do SET title = ?, description = ?, updated_at = CURRENT_TIMESTAMP WHERE id = 1");
                    $stmt->execute([$_POST['title'], $_POST['description']]);
                    echo "<div class='success'>What I'm doing section updated successfully!</div>";
                }

                $what_doing = $pdo->query("SELECT * FROM what_i_do WHERE id = 1")->fetch();
                if (!$what_doing) {
                    $pdo->exec("INSERT INTO what_i_do (title, description) VALUES ('What I''m Doing', 'Description about what you are currently doing.')");
                    $what_doing = $pdo->query("SELECT * FROM what_i_do WHERE id = 1")->fetch();
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Section Title:</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($what_doing['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" required><?php echo htmlspecialchars($what_doing['description']); ?></textarea>
                    </div>
                    <button type="submit" name="update_what_doing" class="btn">Update What I'm Doing</button>
                </form>
            </div>
        </div>

        <div id="testimonials" class="section">
            <div class="card">
                <h2>Testimonials Management</h2>
                <?php
                if (isset($_POST['add_testimonial'])) {
                    $stmt = $pdo->prepare("INSERT INTO testimonials (name, position, avatar, content) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$_POST['name'], $_POST['position'], $_POST['avatar'], $_POST['content']]);
                    echo "<div class='success'>Testimonial added successfully!</div>";
                }

                if (isset($_POST['delete_testimonial'])) {
                    $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
                    $stmt->execute([$_POST['testimonial_id']]);
                    echo "<div class='success'>Testimonial deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Position:</label>
                        <input type="text" name="position" required>
                    </div>
                    <div class="form-group">
                        <label>Avatar:</label>
                        <input type="text" name="avatar" placeholder="e.g., avatar-1.png" required>
                    </div>
                    <div class="form-group">
                        <label>Testimonial Content:</label>
                        <textarea name="content" required></textarea>
                    </div>
                    <button type="submit" name="add_testimonial" class="btn">Add Testimonial</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Content</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $testimonials = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC")->fetchAll();
                        foreach ($testimonials as $testimonial) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($testimonial['name']) . "</td>";
                            echo "<td>" . htmlspecialchars($testimonial['position']) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($testimonial['content'], 0, 50)) . "...</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='testimonial_id' value='" . $testimonial['id'] . "'>";
                            echo "<button type='submit' name='delete_testimonial' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="skills" class="section">
            <div class="card">
                <h2>Skills Management</h2>
                <?php
                if (isset($_POST['add_skill'])) {
                    $stmt = $pdo->prepare("INSERT INTO skills (name, percentage, category) VALUES (?, ?, ?)");
                    $stmt->execute([$_POST['name'], $_POST['percentage'], $_POST['category']]);
                    echo "<div class='success'>Skill added successfully!</div>";
                }

                if (isset($_POST['delete_skill'])) {
                    $stmt = $pdo->prepare("DELETE FROM skills WHERE id = ?");
                    $stmt->execute([$_POST['skill_id']]);
                    echo "<div class='success'>Skill deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Skill Name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Percentage (0-100):</label>
                        <input type="number" name="percentage" min="0" max="100" required>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select name="category" required>
                            <option value="technical">Technical</option>
                            <option value="creative">Creative</option>
                            <option value="soft">Soft Skills</option>
                        </select>
                    </div>
                    <button type="submit" name="add_skill" class="btn">Add Skill</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Skill Name</th>
                            <th>Percentage</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $skills = $pdo->query("SELECT * FROM skills ORDER BY category, name")->fetchAll();
                        foreach ($skills as $skill) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($skill['name']) . "</td>";
                            echo "<td>" . $skill['percentage'] . "%</td>";
                            echo "<td>" . htmlspecialchars($skill['category']) . "</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='skill_id' value='" . $skill['id'] . "'>";
                            echo "<button type='submit' name='delete_skill' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="services" class="section">
            <div class="card">
                <h2>Services Management</h2>
                <?php
                if (isset($_POST['add_service'])) {
                    $stmt = $pdo->prepare("INSERT INTO services (title, description, icon) VALUES (?, ?, ?)");
                    $stmt->execute([$_POST['title'], $_POST['description'], $_POST['icon']]);
                    echo "<div class='success'>Service added successfully!</div>";
                }

                if (isset($_POST['delete_service'])) {
                    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
                    $stmt->execute([$_POST['service_id']]);
                    echo "<div class='success'>Service deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Icon:</label>
                        <input type="text" name="icon" placeholder="e.g., icon-design.svg" required onchange="previewServiceIcon(this, 'service-icon-preview')">
                        <div id="service-icon-preview" style="margin-top: 10px; width: 40px; height: 40px;"></div>
                    </div>
                    <button type="submit" name="add_service" class="btn">Add Service</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Icon</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $services = $pdo->query("SELECT * FROM services")->fetchAll();
                        foreach ($services as $service) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($service['title']) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($service['description'], 0, 50)) . "...</td>";
                            echo "<td>" . htmlspecialchars($service['icon']) . "</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='service_id' value='" . $service['id'] . "'>";
                            echo "<button type='submit' name='delete_service' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="projects" class="section">
            <div class="card">
                <h2>Projects Management</h2>
                <?php
                if (isset($_POST['add_project'])) {
                    $stmt = $pdo->prepare("INSERT INTO projects (title, category, description, image, link) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['title'], $_POST['category'], $_POST['description'], $_POST['image'], $_POST['link']]);
                    echo "<div class='success'>Project added successfully!</div>";
                }

                if (isset($_POST['delete_project'])) {
                    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
                    $stmt->execute([$_POST['project_id']]);
                    echo "<div class='success'>Project deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <select name="category" required>
                            <option value="web development">Web Development</option>
                            <option value="web design">Web Design</option>
                            <option value="applications">Applications</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image:</label>
                        <input type="text" name="image" placeholder="e.g., project-1.jpg" required>
                    </div>
                    <div class="form-group">
                        <label>Link:</label>
                        <input type="url" name="link" placeholder="https://">
                    </div>
                    <button type="submit" name="add_project" class="btn">Add Project</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $projects = $pdo->query("SELECT * FROM projects")->fetchAll();
                        foreach ($projects as $project) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($project['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($project['category']) . "</td>";
                            echo "<td>" . htmlspecialchars($project['image']) . "</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='project_id' value='" . $project['id'] . "'>";
                            echo "<button type='submit' name='delete_project' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="blog" class="section">
            <div class="card">
                <h2>Blog Management</h2>
                <?php
                if (isset($_POST['add_blog'])) {
                    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, category, content, image) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$_POST['title'], $_POST['category'], $_POST['content'], $_POST['image']]);
                    echo "<div class='success'>Blog post added successfully!</div>";
                }

                if (isset($_POST['delete_blog'])) {
                    $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
                    $stmt->execute([$_POST['blog_id']]);
                    echo "<div class='success'>Blog post deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Category:</label>
                        <input type="text" name="category" value="Design" required>
                    </div>
                    <div class="form-group">
                        <label>Content:</label>
                        <textarea name="content" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Image:</label>
                        <input type="text" name="image" placeholder="e.g., blog-1.jpg" required>
                    </div>
                    <button type="submit" name="add_blog" class="btn">Add Blog Post</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $blogs = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC")->fetchAll();
                        foreach ($blogs as $blog) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($blog['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($blog['category']) . "</td>";
                            echo "<td>" . date('M j, Y', strtotime($blog['created_at'])) . "</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='blog_id' value='" . $blog['id'] . "'>";
                            echo "<button type='submit' name='delete_blog' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="experience" class="section">
            <div class="card">
                <h2>Experience Management</h2>
                <?php
                if (isset($_POST['add_experience'])) {
                    $stmt = $pdo->prepare("INSERT INTO experience (title, company, period, description, type) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$_POST['title'], $_POST['company'], $_POST['period'], $_POST['description'], $_POST['type']]);
                    echo "<div class='success'>Experience added successfully!</div>";
                }

                if (isset($_POST['delete_experience'])) {
                    $stmt = $pdo->prepare("DELETE FROM experience WHERE id = ?");
                    $stmt->execute([$_POST['experience_id']]);
                    echo "<div class='success'>Experience deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" required>
                    </div>
                    <div class="form-group">
                        <label>Company/Institution:</label>
                        <input type="text" name="company" required>
                    </div>
                    <div class="form-group">
                        <label>Period:</label>
                        <input type="text" name="period" placeholder="e.g., 2020 - Present" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <textarea name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Type:</label>
                        <select name="type" required>
                            <option value="experience">Experience</option>
                            <option value="education">Education</option>
                        </select>
                    </div>
                    <button type="submit" name="add_experience" class="btn">Add Experience</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Period</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $experiences = $pdo->query("SELECT * FROM experience ORDER BY id DESC")->fetchAll();
                        foreach ($experiences as $exp) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($exp['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($exp['company']) . "</td>";
                            echo "<td>" . htmlspecialchars($exp['period']) . "</td>";
                            echo "<td>" . htmlspecialchars($exp['type']) . "</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='experience_id' value='" . $exp['id'] . "'>";
                            echo "<button type='submit' name='delete_experience' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="contact" class="section">
            <div class="card">
                <h2>Contact Information Management</h2>
                <?php
                if (isset($_POST['update_contact'])) {
                    $stmt = $pdo->prepare("UPDATE contact_info SET email = ?, phone = ?, birthday = ?, location = ?, address = ? WHERE id = 1");
                    $stmt->execute([$_POST['email'], $_POST['phone'], $_POST['birthday'], $_POST['location'], $_POST['address']]);
                    echo "<div class='success'>Contact information updated successfully!</div>";
                }

                $contact = $pdo->query("SELECT * FROM contact_info WHERE id = 1")->fetch();
                if (!$contact) {
                    // Insert default contact if not exists
                    $pdo->exec("INSERT INTO contact_info (email, phone, birthday, location, address) VALUES 
                        ('richard@example.com', '+1 (213) 352-2795', 'June 23, 1982', 'Sacramento, California, USA', '2321 New Design Str, Lorem City, California, USA')");
                    $contact = $pdo->query("SELECT * FROM contact_info WHERE id = 1")->fetch();
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($contact['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Phone:</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($contact['phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Birthday:</label>
                        <input type="text" name="birthday" value="<?php echo htmlspecialchars($contact['birthday']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Location:</label>
                        <input type="text" name="location" value="<?php echo htmlspecialchars($contact['location']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Address:</label>
                        <textarea name="address" required><?php echo htmlspecialchars($contact['address']); ?></textarea>
                    </div>
                    <button type="submit" name="update_contact" class="btn">Update Contact Info</button>
                </form>
            </div>
        </div>

        <div id="map" class="section">
            <div class="card">
                <h2>Map Information Management</h2>
                <?php
                if (isset($_POST['update_map'])) {
                    $stmt = $pdo->prepare("UPDATE map_info SET title = ?, embed_code = ?, address = ?, updated_at = CURRENT_TIMESTAMP WHERE id = 1");
                    $stmt->execute([$_POST['title'], $_POST['embed_code'], $_POST['address']]);
                    echo "<div class='success'>Map information updated successfully!</div>";
                }

                $map = $pdo->query("SELECT * FROM map_info WHERE id = 1")->fetch();
                if (!$map) {
                    $pdo->exec("INSERT INTO map_info (title, embed_code, address) VALUES 
                        ('My Location', '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63245.517334049314!2d112.68708302167967!3d-7.275398999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fbf8381ac47f%3A0x3027a76e352be40!2sSurabaya%2C%20East%20Java!5e0!3m2!1sen!2sid!4v1647854923456!5m2!1sen!2sid\" width=\"100%\" height=\"300\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\"></iframe>', 'Surabaya, East Java, Indonesia')");
                    $map = $pdo->query("SELECT * FROM map_info WHERE id = 1")->fetch();
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Map Title:</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($map['title']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Google Maps Embed Code:</label>
                        <textarea name="embed_code" rows="5" required><?php echo htmlspecialchars($map['embed_code']); ?></textarea>
                        <small>Paste the complete iframe embed code from Google Maps</small>
                    </div>
                    <div class="form-group">
                        <label>Address:</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($map['address']); ?>" required>
                    </div>
                    <button type="submit" name="update_map" class="btn">Update Map Info</button>
                </form>

                <div style="margin-top: 20px;">
                    <h3>Preview:</h3>
                    <div style="border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                        <?php echo $map['embed_code']; ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="clients" class="section">
            <div class="card">
                <h2>Clients Management</h2>
                <?php
                if (isset($_POST['add_client'])) {
                    $stmt = $pdo->prepare("INSERT INTO clients (name, logo, website) VALUES (?, ?, ?)");
                    $stmt->execute([$_POST['name'], $_POST['logo'], $_POST['website']]);
                    echo "<div class='success'>Client added successfully!</div>";
                }

                if (isset($_POST['delete_client'])) {
                    $stmt = $pdo->prepare("DELETE FROM clients WHERE id = ?");
                    $stmt->execute([$_POST['client_id']]);
                    echo "<div class='success'>Client deleted successfully!</div>";
                }
                ?>
                <form method="post">
                    <div class="form-group">
                        <label>Client Name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Logo (filename):</label>
                        <input type="text" name="logo" placeholder="e.g., logo-1-color.png" required>
                    </div>
                    <div class="form-group">
                        <label>Website:</label>
                        <input type="url" name="website" placeholder="https://example.com">
                    </div>
                    <button type="submit" name="add_client" class="btn">Add Client</button>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Website</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $clients = $pdo->query("SELECT * FROM clients ORDER BY name")->fetchAll();
                        foreach ($clients as $client) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($client['name']) . "</td>";
                            echo "<td><img src='/assets/images/" . htmlspecialchars($client['logo']) . "' alt='Logo' style='width: 50px; height: 30px; object-fit: contain;'></td>";
                            echo "<td><a href='" . htmlspecialchars($client['website']) . "' target='_blank'>" . htmlspecialchars($client['website']) . "</a></td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='client_id' value='" . $client['id'] . "'>";
                            echo "<button type='submit' name='delete_client' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="messages" class="section">
            <div class="card">
                <h2>Contact Messages</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll();
                        foreach ($messages as $message) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($message['fullname']) . "</td>";
                            echo "<td>" . htmlspecialchars($message['email']) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($message['message'], 0, 50)) . "...</td>";
                            echo "<td>" . date('M j, Y h:i A', strtotime($message['created_at'])) . "</td>";
                            echo "<td>";
                            echo "<form method='post' style='display: inline;'>";
                            echo "<input type='hidden' name='message_id' value='" . $message['id'] . "'>";
                            echo "<button type='submit' name='delete_message' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => section.classList.remove('active'));

            // Show selected section
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // Update nav links
            const navLinks = document.querySelectorAll('.nav a');
            navLinks.forEach(link => link.classList.remove('active'));

            // Add active class to clicked link
            const clickedLink = event ? event.target : document.querySelector(`[onclick*="${sectionId}"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }
        }

        // Initialize first section as active on page load
        document.addEventListener('DOMContentLoaded', function() {
            showSection('profile');
        });

        // Icon preview functions
        function previewIcon(input, previewId) {
            const preview = document.getElementById(previewId);
            const iconClass = input.value.trim();
            if (iconClass) {
                preview.innerHTML = '<ion-icon name="' + iconClass.replace('ion-', '').replace('logo-', '') + '"></ion-icon>';
            } else {
                preview.innerHTML = '';
            }
        }

        function previewServiceIcon(input, previewId) {
            const preview = document.getElementById(previewId);
            const iconPath = input.value.trim();
            if (iconPath) {
                preview.innerHTML = '<img src="/assets/images/' + iconPath + '" alt="Icon" style="width: 40px; height: 40px; object-fit: contain;">';
            } else {
                preview.innerHTML = '';
            }
        }
    </script>
</body>
</html>
<?php
    if (isset($_POST['delete_message'])) {
        $message_id = $_POST['message_id'];
        // Perform database deletion here using $message_id
        $pdo->query("DELETE FROM contact_messages WHERE id = $message_id");
        echo "<script>alert('Message deleted successfully.'); window.location.href='index.php';</script>";
    }
?>