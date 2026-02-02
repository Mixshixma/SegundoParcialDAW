<?php
header("Content-Type: application/json");
echo json_encode([
    "mensaje" => "Bienvenido a la API de Carteles Anónimos",
    "estado" => "En funcionamiento",
    "documentacion" => "Usa los endpoints correspondientes para gestionar anuncios."
]);
