<?php
require_once __DIR__ . '/../config/Database.php';

class EmployeeController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // GET /api/employees
    public function getAll() {
        ob_clean();
        try {
            $query = "SELECT * FROM employees ORDER BY last_name ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $employees = $stmt->fetchAll();
            
            echo json_encode($employees);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erreur lors de la récupération des employés."]);
        }
        exit;
    }

    // GET /api/employees/{id}
    public function getOne($id) {
        ob_clean();
        try {
            $query = "SELECT * FROM employees WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $employee = $stmt->fetch();

            if ($employee) {
                echo json_encode($employee);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Employé non trouvé."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Erreur serveur."]);
        }
        exit;
    }
}
