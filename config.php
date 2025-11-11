<?php
// Cargar variables de entorno (sin Composer)
require_once __DIR__ . '/load_env.php';
loadEnv(__DIR__);

// Configuración de la base de datos (desde .env)
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PORT', env('DB_PORT', '3306'));
define('DB_PASS', env('DB_PASS', ''));
define('DB_NAME', env('DB_NAME', 'thefacebook'));

// Configuración del sitio
define('SITE_URL', env('SITE_URL', 'http://localhost/thefacebook'));
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', env('MAX_FILE_SIZE', 5242880));

// Crear carpeta de uploads si no existe
if (!file_exists(UPLOAD_PATH)) {
    mkdir(UPLOAD_PATH, 0777, true);
}

// Conectar a la base de datos
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $conn->set_charset("utf8mb4");
        return $conn;
    } catch (Exception $e) {
        die("Database error: " . $e->getMessage());
    }
}

// Iniciar sesión de forma segura
function startSecureSession() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', env('SESSION_SECURE', 0));
        session_start();
    }
}

// Verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Redirigir si no está logueado
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Validar dominio universitario
function isUniversityEmail($email) {
    $conn = getDBConnection();
    $domain = substr(strrchr($email, "@"), 1);
    
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM allowed_domains WHERE domain = ?");
    $stmt->bind_param("s", $domain);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $row['count'] > 0;
}

// Sanitizar entrada
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

startSecureSession();
?>