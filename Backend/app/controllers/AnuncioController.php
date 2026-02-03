<?php
// Permitir que Netlify acceda a este backend
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();
// Si $_POST está vacío, intentamos leer el cuerpo de la petición (JSON)
if (empty($_POST)) {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if ($data) {
        $_POST = $data;
    }
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../models/Anuncio.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$database = new Database();
$db = $database->getConnection();
$anuncio = new Anuncio($db);

// --- CASO 1: CREAR ---
if ($action == 'crear') {
    $nuevo_token = bin2hex(random_bytes(3)); 
    $anuncio->token = $nuevo_token;

    $anuncio->titulo = $_POST['titulo'] ?? '';
    $anuncio->categoria = $_POST['categoria'] ?? '';
    $anuncio->descripcion = $_POST['descripcion'] ?? '';
    $anuncio->precio = $_POST['precio'] ?? '';
    $anuncio->estado = $_POST['estado'] ?? '';
    $anuncio->pais = $_POST['pais'] ?? '';
    $anuncio->contacto = $_POST['contacto'] ?? '';
    $anuncio->imagen_url = $_POST['imagen_url'] ?? '';

    // Validación Backend (Requisito 3.4) [cite: 32, 33]
    if (empty($anuncio->titulo) || empty($anuncio->precio) || empty($anuncio->contacto)) {
        echo json_encode(["status" => "error", "message" => "Campos obligatorios vacíos."]);
        exit;
    }

    if ($anuncio->create()) {
        // En lugar de redirección, enviamos éxito y el token al frontend
        echo json_encode([
            "status" => "success", 
            "message" => "Anuncio creado", 
            "token" => $nuevo_token
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al publicar."]);
    }
} 

// --- CASO 2: ELIMINAR ---
else if ($action == 'eliminar') {
    $id = $_POST['id'] ?? '';
    $token_usuario = $_POST['token'] ?? '';

    $query = "SELECT token FROM anuncios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $anuncio_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($anuncio_db && $anuncio_db['token'] === $token_usuario) {
        $anuncio->id = $id;
        if ($anuncio->delete()) {
            echo json_encode(["status" => "success", "message" => "Eliminado correctamente."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Token incorrecto."]);
    }
}

// --- CASO 3: ACTUALIZAR ---
else if ($action == 'actualizar') {
    $id = $_POST['id'] ?? '';
    $token_usuario = $_POST['token_usuario'] ?? '';

    $query = "SELECT token FROM anuncios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $anuncio_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($anuncio_db && $anuncio_db['token'] === $token_usuario) {
        $anuncio->id = $id;
        $anuncio->titulo = $_POST['titulo'];
        $anuncio->categoria = $_POST['categoria'];
        $anuncio->descripcion = $_POST['descripcion'];
        $anuncio->precio = $_POST['precio'];
        $anuncio->estado = $_POST['estado'];
        $anuncio->pais = $_POST['pais'];
        $anuncio->contacto = $_POST['contacto'];
        
        if ($anuncio->update()) {
            echo json_encode(["status" => "success", "message" => "Actualizado con éxito."]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Token incorrecto."]);
    }
}

// --- CASO 4: LEER (LISTAR) ---
else if ($action == 'leer') {
    $f_pais = $_GET['f_pais'] ?? null;
    $f_cat  = $_GET['f_cat'] ?? null;
    $f_est  = $_GET['f_est'] ?? null;

    $stmt = $anuncio->read($f_pais, $f_cat, $f_est);
    $anuncios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($anuncios);
}

// --- CASO 5: DETALLE DE UN ANUNCIO ---
else if ($action == 'detalle') {
    $id = $_GET['id'] ?? null;
    $query = "SELECT * FROM anuncios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
}

?>

