<?php
// Disable error display to prevent HTML in JSON response
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

try {
    // Always use admin config for consistency
    require_once __DIR__ . '/admin/config.php';

    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'profile':
            $stmt = $pdo->prepare("SELECT * FROM profile WHERE id = 1");
            $stmt->execute();
            $profile = $stmt->fetch();
            if ($profile === false) {
                echo json_encode(['error' => 'No profile found']);
            } else {
                echo json_encode($profile);
            }
            break;

        case 'services':
            $stmt = $pdo->prepare("SELECT * FROM services");
            $stmt->execute();
            $services = $stmt->fetchAll();
            echo json_encode($services ?: []);
            break;

        case 'projects':
            $stmt = $pdo->prepare("SELECT * FROM projects");
            $stmt->execute();
            $projects = $stmt->fetchAll();
            echo json_encode($projects ?: []);
            break;

        case 'blog':
            $stmt = $pdo->prepare("SELECT * FROM blog_posts ORDER BY created_at DESC");
            $stmt->execute();
            $blogs = $stmt->fetchAll();
            echo json_encode($blogs ?: []);
            break;

        case 'experience':
            $stmt = $pdo->prepare("SELECT * FROM experience ORDER BY id DESC");
            $stmt->execute();
            $experiences = $stmt->fetchAll();
            echo json_encode($experiences ?: []);
            break;

        case 'contact':
            $stmt = $pdo->prepare("SELECT * FROM contact_info WHERE id = 1");
            $stmt->execute();
            $contact = $stmt->fetch();
            if ($contact === false) {
                echo json_encode(['error' => 'No contact info found']);
            } else {
                echo json_encode($contact);
            }
            break;

        case 'social':
            $stmt = $pdo->prepare("SELECT * FROM social_media");
            $stmt->execute();
            $social = $stmt->fetchAll();
            echo json_encode($social ?: []);
            break;

        case 'testimonials':
            $stmt = $pdo->prepare("SELECT * FROM testimonials ORDER BY created_at DESC");
            $stmt->execute();
            $testimonials = $stmt->fetchAll();
            echo json_encode($testimonials ?: []);
            break;

        case 'skills':
            $stmt = $pdo->prepare("SELECT * FROM skills ORDER BY category, name");
            $stmt->execute();
            $skills = $stmt->fetchAll();
            echo json_encode($skills ?: []);
            break;

        case 'what-doing':
            $stmt = $pdo->prepare("SELECT * FROM what_i_do WHERE id = 1");
            $stmt->execute();
            $what_doing = $stmt->fetch();
            if ($what_doing === false) {
                echo json_encode(['error' => 'No what I am doing info found']);
            } else {
                echo json_encode($what_doing);
            }
            break;

        case 'clients':
            $stmt = $pdo->prepare("SELECT * FROM clients ORDER BY created_at DESC");
            $stmt->execute();
            echo json_encode($stmt->fetchAll());
            break;

        case 'map':
            $stmt = $pdo->prepare("SELECT * FROM map WHERE id = 1");
            $stmt->execute();
            echo json_encode($stmt->fetch() ?: ['embed_url' => '', 'address' => '']);
            break;

        case 'contact':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                $fullname = $input['fullname'] ?? '';
                $email = $input['email'] ?? '';
                $message = $input['message'] ?? '';

                if (empty($fullname) || empty($email) || empty($message)) {
                    echo json_encode(['error' => 'All fields are required']);
                    break;
                }

                $stmt = $pdo->prepare("INSERT INTO contact_messages (fullname, email, message) VALUES (?, ?, ?)");
                $stmt->execute([$fullname, $email, $message]);
                echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
            } else {
                echo json_encode(['error' => 'Invalid request method']);
            }
            break;

        default:
            echo json_encode(['error' => 'Unknown action']);
            break;
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
?>