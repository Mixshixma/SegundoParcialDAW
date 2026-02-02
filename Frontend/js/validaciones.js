document.getElementById('formAnuncio').addEventListener('submit', async function(e) {
    e.preventDefault(); // Detenemos el envío tradicional siempre

    // 1. TUS VALIDACIONES ORIGINALES (Requisito 3.3)
    let titulo = document.getElementById('titulo').value;
    let precio = document.getElementById('precio').value;
    let contacto = document.getElementById('contacto').value;

    if (titulo.trim() === "" || precio <= 0 || contacto.trim() === "") {
        alert("Por favor, completa los campos obligatorios y asegúrate que el precio sea mayor a 0.");
        return; // Salimos de la función si falla la validación
    }

    // 2. ENVÍO A RENDER (Para el despliegue separado)
    const formData = new FormData(this);
    
    // Aquí pondrás la URL que te asigne Render al desplegar tu PHP
    const urlBackend = "https://anuncios-php.onrender.com/app/controllers/AnuncioController.php?action=crear";

    try {
        const response = await fetch(urlBackend, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.status === "success") {
            // Guardamos el token para mostrarlo en la vista de éxito
            localStorage.setItem('ultimo_token', data.token);
            window.location.href = "exito.html";
        } else {
            alert("Error del servidor: " + data.message);
        }
    } catch (error) {
        console.error("Error de conexión:", error);
        alert("No se pudo conectar con el servidor de Render.");
    }

});


