document.addEventListener("DOMContentLoaded", function() {
    // Manejar el envío del formulario de solicitud de mantenimiento
    document.getElementById("envioMante").addEventListener("click", function(event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, enviar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Crear el objeto FormData a partir del formulario
                var form = document.getElementById("EnvioFormulario");
                var formData = new FormData(form);

                // Enviar los datos al servidor
                fetch("../Personal/PHP/RegistrarSolicitudMantenimiento.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => {
                    // Verificar si la respuesta es JSON válida
                    const contentType = response.headers.get("content-type");
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    if (contentType && contentType.includes("application/json")) {
                        return response.json();
                    } else {
                        throw new Error('La respuesta no es JSON válida');
                    }
                })
                .then(data => {
                    if (data.status === "success") {
                        Swal.fire('Éxito', data.message, 'success');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire('Error', `Ocurrió un problema: ${error.message}`, 'error');
                });
            }
        });
    });
});

/* document.getElementById("envioMante").addEventListener("click", function(event) {
    event.preventDefault();
    const form = document.getElementById("EnvioFormulario");
    const formData = new FormData(form);

    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
}); */