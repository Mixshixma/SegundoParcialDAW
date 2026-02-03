document.getElementById('formAnuncio').addEventListener('submit', async function(e) {
    e.preventDefault();

    // 1. Obtener el ID del anuncio desde la URL (ejemplo: editar.html?id=5)
    const urlParams = new URLSearchParams(window.location.search);
    const idAnuncio = urlParams.get('id');

    // 2. Validaciones básicas
    let titulo = document.getElementById('titulo').value;
    let precio = document.getElementById('precio').value;
    let contacto = document.getElementById('contacto').value;

    if (titulo.trim() === "" || precio <= 0 || contacto.trim() === "") {
        alert("Por favor, completa los campos obligatorios.");
        return;
    }

    // 3. Preparar los datos
    const formData = new FormData(this);
    
    // IMPORTANTE: Agregamos manualmente el ID y el Token que el controlador PHP espera
    formData.append('id', idAnuncio);
    
    // El token lo recuperamos de donde lo hayas guardado (ej. localStorage)
    const tokenSeguridad = localStorage.getItem('ultimo_token');
    formData.append('token_usuario', tokenSeguridad); 

    // 4. URL apuntando a la acción de ACTUALIZAR
    const urlBackend = "https://anuncios-php.onrender.com/app/controllers/AnuncioController.php?action=actualizar";

    try {
        const response = await fetch(urlBackend, {
        method: 'POST',
        body: formData
    });

    // Diagnóstico: Vamos a ver qué respondió el servidor antes de intentar convertirlo a JSON
    const text = await response.text(); 
    console.log("Respuesta bruta del servidor:", text);

    // Intentamos convertir ese texto a JSON manualmente
    const data = JSON.parse(text);

    if (data.status === "success") {
        alert("¡Anuncio actualizado con éxito!");
        window.location.href = "carteles.html";
    } else {
        alert("Error: " + data.message);
    }
} catch (error) {
    console.error("Error al procesar la respuesta:", error);
    alert("Error de conexión o formato de respuesta incorrecto.");
    }
});
