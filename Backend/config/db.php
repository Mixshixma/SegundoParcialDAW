<?php
class Database {
    private $host = "mysql-1c9f17ee-herlenisalive-a91b.j.aivencloud.com"; 
    private $db_name = "defaultdb"; 
    private $username = "avnadmin";
    private $password = "AVNS_z8APT2lXkJQWgAPKCUG";
    private $port = "18850"; 
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password,
                array(
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // Esto te ayudará a ver el error real en los logs de Render si falla
            error_log("Error de conexión: " . $exception->getMessage());
        }

        return $this->conn; // <--- ¡ESTO ES VITAL!
    }
}
?>
