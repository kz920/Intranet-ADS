<?php
require_once __DIR__ . '/../config/Ldap.php';

class AuthController {
    private $ldap;

    public function __construct() {
        $this->ldap = new Ldap();
    }

    // Vérifie si l'utilisateur est déjà connecté (Session ou SSO Nginx)
    public function checkSession() {
        // Nettoyage impératif du buffer de sortie avant d'envoyer du JSON
        ob_clean();

        // 1. Vérifier si une session PHP existe déjà
        if (isset($_SESSION['user'])) {
            echo json_encode([
                "authenticated" => true,
                "user" => $_SESSION['user']
            ]);
            exit;
        }

        // 2. Vérifier si Nginx a authentifié l'utilisateur (SSO Kerberos)
        // La variable REMOTE_USER est remplie par Nginx s'il est configuré pour l'auth Windows
        if (isset($_SERVER['REMOTE_USER']) && !empty($_SERVER['REMOTE_USER'])) {
            // Nettoyage du nom (ex: DOMAINE\user -> user)
            $username = $_SERVER['REMOTE_USER'];
            if (strpos($username, '\\') !== false) {
                $parts = explode('\\', $username);
                $username = end($parts);
            }

            // On crée la session automatiquement
            $user = [
                "username" => $username,
                "role" => "user",
                "source" => "sso"
            ];
            $_SESSION['user'] = $user;

            echo json_encode([
                "authenticated" => true,
                "user" => $user
            ]);
            exit;
        }

        // Si rien n'est trouvé
        echo json_encode(["authenticated" => false]);
        exit;
    }

    public function login() {
        $json = file_get_contents("php://input");
        $data = json_decode($json);

        ob_clean(); // Nettoyage avant réponse

        if (!$data || !isset($data->username) || !isset($data->password)) {
            http_response_code(400);
            echo json_encode(["message" => "Données incomplètes."]);
            exit;
        }

        try {
            if ($this->ldap->authenticate($data->username, $data->password)) {
                
                $user = [
                    "username" => $data->username,
                    "role" => "user",
                    "source" => "ldap_form"
                ];

                // Sauvegarde en session
                $_SESSION['user'] = $user;

                echo json_encode([
                    "success" => true,
                    "user" => $user
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["success" => false, "message" => "Identifiants invalides."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Erreur : " . $e->getMessage()]);
        }
        exit;
    }

    public function logout() {
        session_destroy();
        ob_clean();
        echo json_encode(["success" => true]);
        exit;
    }
}
