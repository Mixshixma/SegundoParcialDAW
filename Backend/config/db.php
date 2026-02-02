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
    // Modificación sugerida dentro de getConnection()
    $this->conn = new PDO(
    "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name, 
    $this->username, 
    $this->password,
    array(
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // Útil si hay líos con certificados en Render
    )
);
    }
}
?>
