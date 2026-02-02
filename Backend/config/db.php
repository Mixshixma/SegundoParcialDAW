<?php
class Database {
    // Estos datos los sacas de la pestaña "Overview" en Aiven
    private $host = "mysql-1c9f17ee-herlenisalive-a91b.j.aivencloud.com"; 
    private $db_name = "defaultdb"; // El nombre que veas en Aiven
    private $username = "avnadmin";
    private $password = "AVNS_z8APT2lXkJQWgAPKCUG";
    private $port = "18850"; // El puerto que te asigne Aiven
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Es vital incluir el puerto (port) en la cadena DSN
            $this->conn = new PDO("mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Esto ayuda a evitar errores de caracteres especiales
            $this->conn->exec("set names utf8");
            
            // Opcional: Esto ayuda si Render tiene problemas con el certificado SSL de Aiven
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $exception) {
            echo "Error de conexión: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>