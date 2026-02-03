const URL_BACKEND = "https://anuncios-php.onrender.com/app/controllers/AnuncioController.php";

// 1. CARGAR DATOS AL INICIAR
window.onload = async () => {
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    if (!id) {
        window.location.href = "carteles.html";
        return;
    }

    try {
        const resp = await fetch(`${URL_BACKEND}?action=detalle&id=${id}`);
        const data = await resp.json();

        if (data) {
            document.getElementById('anuncio_id').value = data.id;
            document.getElementById('titulo').value = data.titulo;
            document.getElementById('categoria').value = data.categoria;
            document.getElementById('precio').value = data.precio;
            document.getElementById('estado').value = data.estado;
            document.getElementById('descripcion').value = data.descripcion;
            document.getElementById('pais').value = data.pais;
            document.getElementById('contacto').value = data.contacto;
            document.getElementById('imagen_url').value = data.imagen_url;
        }
    } catch (e) {
        console.error("Error cargando datos:", e);
    }
};

// 2. PROCESAR EL FORMULARIO
document.getElementById('formEditar').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    // Aseguramos que el ID se incluya si no está en el FormData
    const urlParams = new URLSearchParams(window.location.search);
    formData.append('id', urlParams.get('id'));

    try {
        const response = await fetch(`${URL_BACKEND}?action=actualizar`, {
            method: 'POST',
            body: formData
        });

        // Intentamos obtener el texto primero por si PHP manda un error de texto
        const rawResponse = await response.text();
        console.log("Respuesta cruda del servidor:", rawResponse);

        const data = JSON.parse(rawResponse);

        if (data.status === "success") {
            alert("¡Anuncio actualizado con éxito!");
            window.location.href = "carteles.html";
        } else {
            alert("Error: " + data.message);
        }
    } catch (error) {
        console.error("Fallo técnico:", error);
        alert("Parece que hubo un error al procesar la respuesta del servidor. Revisa la consola (F12).");
    }
});
