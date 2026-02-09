<?php
// Capture de tout le contenu généré avant l'envoi du JSON
// DOIT ETRE LA PREMIERE LIGNE
ob_start();

// Désactiver l'affichage des erreurs HTML pour ne pas corrompre le JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Démarrage de la session PHP pour stocker l'état de connexion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    ob_clean(); // Nettoyage par sécurité
    http_response_code(200);
    exit();
}

require_once 'controllers/EmployeeController.php';
require_once 'controllers/NewsController.php';
require_once 'controllers/AuthController.php';

$request_uri = $_SERVER['REQUEST_URI'];
// Gestion basique du routing si le serveur ne réécrit pas parfaitement l'URL
$path = parse_url($request_uri, PHP_URL_PATH);
// Si l'URL contient /api, on l'enlève pour le switch
if (strpos($path, '/api') === 0) {
    $path = substr($path, 4);
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($path) {
        case '/auth/login':
            $controller = new AuthController();
            if ($method === 'POST') {
                $controller->login();
            }
            break;

        case '/auth/check':
            $controller = new AuthController();
            if ($method === 'GET') {
                $controller->checkSession();
            }
            break;

        case '/auth/logout':
            $controller = new AuthController();
            if ($method === 'POST') {
                $controller->logout();
            }
            break;

        case '/employees':
            $controller = new EmployeeController();
            if ($method === 'GET') $controller->getAll();
            break;

        case '/news':
            $controller = new NewsController();
            if ($method === 'GET') $controller->getAll();
            break;
            
        case '/health':
            ob_clean();
            echo json_encode(["status" => "ok"]);
            exit;
            break;

        default:
            if (preg_match('/^\/employees\/(\d+)$/', $path, $matches)) {
                $controller = new EmployeeController();
                if ($method === 'GET') $controller->getOne($matches[1]);
            } else {
                ob_clean();
                http_response_code(404);
                echo json_encode(["message" => "Endpoint introuvable: " . $path]);
                exit;
            }
            break;
    }
} catch (Exception $e) {
    ob_clean();
    http_response_code(500);
    echo json_encode(["error" => "Erreur interne du serveur"]);
    exit;
}
