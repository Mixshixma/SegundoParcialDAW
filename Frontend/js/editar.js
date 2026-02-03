const URL_BACKEND = "https://anuncios-php.onrender.com/app/controllers/AnuncioController.php";

// 1. CARGAR DATOS AL INICIAR
window.onload = async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    if (!id) {
        alert("ID de anuncio no proporcionado.");
        window.location.href = "carteles.html";
        return;
    }

    try {
        const resp = await fetch(`${URL_BACKEND}?action=detalle&id=${id}`);
        const rawText = await resp.text();
        
        // Limpiamos posibles errores de PHP antes de convertir a JSON
        const cleanJson = rawText.substring(rawText.indexOf('{'));
        const data = JSON.parse(cleanJson);

        if (data) {
            document.getElementById('anuncio_id').value = data.id || '';
            document.getElementById('titulo').value = data.titulo || '';
            document.getElementById('categoria').value = data.categoria || 'Tecnología';
            document.getElementById('precio').value = data.precio || '';
            document.getElementById('estado').value = data.estado || 'Nuevo';
            document.getElementById('descripcion').value = data.descripcion || '';
            document.getElementById('pais').value = data.pais || 'Ecuador';
            document.getElementById('contacto').value = data.contacto || '';
            document.getElementById('imagen_url').value = data.imagen_url || '';
        }
    } catch (e) {
        console.error("Error cargando datos iniciales:", e);
    }
};

// 2. ENVIAR CAMBIOS
document.getElementById('formEditar').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    // Forzamos el ID por si acaso
    const urlParams = new URLSearchParams(window.location.search);
    formData.append('id', urlParams.get('id'));

    try {
        const response = await fetch(`${URL_BACKEND}?action=actualizar`, {
            method: 'POST',
            body: formData
        });

        const rawText = await response.text();
        // Limpiamos basura de PHP (Deprecated notices) para que no rompa el JSON
        const cleanJson = rawText.substring(rawText.indexOf('{'));
        const result = JSON.parse(cleanJson);

        if (result.status === "success") {
            alert("¡Anuncio actualizado con éxito!");
            window.location.href = "carteles.html";
        } else {
            alert("Error: " + result.message);
        }
    } catch (error) {
        console.error("Error técnico:", error);
        alert("Se guardaron los cambios, pero el servidor envió un formato inesperado.");
    }
});
