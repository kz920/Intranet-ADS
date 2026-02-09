<?php
require_once __DIR__ . '/../config/Database.php';

class NewsController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // GET /api/news
    public function getAll() {
        ob_clean();
        try {
            $query = "SELECT * FROM news ORDER BY published_date DESC LIMIT 10";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $news = $stmt->fetchAll();
            echo json_encode($news);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de la récupération des actualités."]);
        }
        exit;
    }
}
