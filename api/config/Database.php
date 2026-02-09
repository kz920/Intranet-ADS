<?php
class Database {
    // Modifier ces informations selon votre configuration VM
    private $host = "localhost";
    private $db_name = "intranet_ads";
    private $username = "root"; 
    private $password = "votre_mot_de_passe";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configuration des erreurs et du mode de fetch par défaut
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // En production, évitez d'afficher l'erreur brute
            error_log("Connection error: " . $exception->getMessage());
            echo json_encode(["error" => "Erreur de connexion à la base de données."]);
            exit;
        }

        return $this->conn;
    }
}
