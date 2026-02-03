<?php
class Anuncio {
    private $conn;
    private $table_name = "anuncios";

    public $id;
    public $titulo;
    public $categoria;
    public $descripcion;
    public $precio;
    public $estado;
    public $pais;
    public $contacto;
    public $imagen_url;
    public $token;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Mejoramos el read para que acepte parámetros de filtrado desde la URL
    public function read($pais = null, $categoria = null, $estado = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";

        if ($pais) $query .= " AND pais = :pais";
        if ($categoria) $query .= " AND categoria = :categoria";
        if ($estado) $query .= " AND estado = :estado";

        $query .= " ORDER BY id DESC"; // Ordenamos por el más reciente

        $stmt = $this->conn->prepare($query);

        if ($pais) $stmt->bindParam(":pais", $pais);
        if ($categoria) $stmt->bindParam(":categoria", $categoria);
        if ($estado) $stmt->bindParam(":estado", $estado);

        $stmt->execute();
        return $stmt;
    }

    public function create() {
        if(empty($this->token)) {
            $this->token = bin2hex(random_bytes(4));
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  SET titulo=:titulo, categoria=:categoria, descripcion=:descripcion, 
                      precio=:precio, estado=:estado, pais=:pais, 
                      contacto=:contacto, imagen_url=:imagen_url, token=:token";

        $stmt = $this->conn->prepare($query);
        $this->sanitize();

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":pais", $this->pais);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":imagen_url", $this->imagen_url);
        $stmt->bindParam(":token", $this->token);

        return $stmt->execute();
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET titulo=:titulo, categoria=:categoria, descripcion=:descripcion, 
                      precio=:precio, estado=:estado, pais=:pais, contacto=:contacto 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);
        $this->sanitize();
        
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":descripcion", $this->descripcion);
        $stmt->bindParam(":precio", $this->precio);
        $stmt->bindParam(":estado", $this->estado);
        $stmt->bindParam(":pais", $this->pais);
        $stmt->bindParam(":contacto", $this->contacto);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }

    // Extendemos el sanitize para cumplir con la validación de backend 
    private function sanitize() {
    $this->titulo = htmlspecialchars(strip_tags($this->titulo ?? ''));
    $this->categoria = htmlspecialchars(strip_tags($this->categoria ?? ''));
    $this->descripcion = htmlspecialchars(strip_tags($this->descripcion ?? ''));
    $this->precio = htmlspecialchars(strip_tags($this->precio ?? ''));
    $this->estado = htmlspecialchars(strip_tags($this->estado ?? ''));
    $this->pais = htmlspecialchars(strip_tags($this->pais ?? ''));
    $this->contacto = htmlspecialchars(strip_tags($this->contacto ?? ''));
    $this->imagen_url = htmlspecialchars(strip_tags($this->imagen_url ?? ''));
    }
}

?>
