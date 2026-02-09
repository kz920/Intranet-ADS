<?php
// Capture de tout le contenu généré avant l'envoi du JSON
ob_start();

// Désactiver l'affichage des erreurs HTML pour ne pas corrompre le JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Démarrage de la session PHP pour stocker l'état de connexion
session_start();

// Headers CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'controllers/EmployeeController.php';
require_once 'controllers/NewsController.php';
require_once 'controllers/AuthController.php';

$request_uri = $_SERVER['REQUEST_URI'];
$path = str_replace('/api', '', parse_url($request_uri, PHP_URL_PATH));
$method = $_SERVER['REQUEST_METHOD'];

switch ($path) {
    case '/auth/login':
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->login();
        }
        break;

    // Nouvelle route pour vérifier si l'utilisateur est déjà connecté (SSO ou Session)
    case '/auth/check':
        $controller = new AuthController();
        if ($method === 'GET') {
            $controller->checkSession();
        }
        break;

    // Nouvelle route pour se déconnecter
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
        // Nettoyage du buffer avant envoi
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
            echo json_encode(["message" => "Endpoint introuvable"]);
            exit;
        }
        break;
}
