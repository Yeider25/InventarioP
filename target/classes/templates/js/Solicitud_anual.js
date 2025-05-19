
$(document).ready(function() {
    // Función para inicializar valores y configuraciones al cargar la página
    function init() {
        hideDeleteButton();
        // Evento click para añadir un nuevo artículo
        $("#nuevo_articulo").click(addNewRow);
        // Evento click para eliminar un artículo
        $("#eliminar_fila").click(deleteRow);
        // Evento submit para enviar el formulario
        $("#informeForm").submit(submitForm);
    }
    // Función para ocultar el botón de eliminar fila
    function hideDeleteButton() {
        $("#eliminar_fila").hide();
    }
    function addNewRow() {
        var newRowHtml = '<tr>' +
            '<td><select class="form-control" type="text" name="nom_elemento[]" ></td>' +
            '<td><input class="form-control" type="text" name="unidad[]" ></td>' +
            '<td><input class="form-control" type="text" name="cantidad[]" ></td>' +
            '<td><input class="form-control" type="number" name="solicitada[]" ></td>' +
            '</tr>';
        $("#body_elemento").append(newRowHtml);
        $("#eliminar_fila").show();
    }
   // Función para manejar el envío del formulario directamente de Solicitud_anual,php
   function submitForm(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
    var form = $(this);
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json', // <-- MUY IMPORTANTE
        success: function(response) {
            try {
                console.log("Respuesta del servidor:", response);
                if (response.status === "success" && response.id_solicitud) {
                    $("#id_anual").val(response.id_solicitud);
                    toastr.success(response.message, 'Éxito');
                } else {
                    toastr.error("No se pudo generar el ID de la solicitud.", "Error");
                }
            } catch (e) {
                console.error("Error al procesar la respuesta del servidor:", e);
                toastr.error("Respuesta inválida del servidor.", "Error");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX:", xhr.responseText);
            toastr.error('Ocurrió un problema al procesar la solicitud. Intenta nuevamente.', 'Error');
        }
    });
}
   // Función para eliminar la fila seleccionada de la tabla de artículos
    function deleteRow() {
        // Obtener la fila seleccionada
        var selectedRow = $("#body_elemento tr:last-child");
        // Eliminar la fila seleccionada
        selectedRow.remove();
        // Si ya no hay filas, ocultar el botón de eliminar fila
        if ($("#body_elemento tr").length === 0) {
            $("#eliminar_fila").hide();
        }
    }
    // Llamar a la función de inicialización al cargar la página
    init();
});


$(document).off('change', '#body_elemento input[name="solicitada[]"]').on('change', '#body_elemento input[name="solicitada[]"]', function () {
    var id_anual = $("#id_anual").val();

    /*if (!id_anual) {
        toastr.warning("Por favor, envía el formulario principal antes de realizar actualizaciones.");
        console.warn("Aún no existe un id_solicitud. Espera a que se genere con el envío.");
        return;
    }*/

    var $fila = $(this).closest('tr');
    var id_elemento = $fila.find('select[name="nom_elemento[]"]').val();
    var cantidad_solicitada = parseInt($(this).val(), 10);

    if (!id_elemento || cantidad_solicitada === "" || isNaN(cantidad_solicitada)) {
        console.error("Error: Datos incompletos para la actualización.", {
            id_solicitud: id_anual,
            id_elemento: id_elemento,
            solicitada: cantidad_solicitada
        });
        return;
    }

    if (cantidad_solicitada <= 0) {
        toastr.error("La cantidad solicitada debe ser mayor que cero.");
        return;
    }

    var formData = {
        id_solicitud: id_anual,
        id_elemento: id_elemento,
        solicitada: cantidad_solicitada
    };

    $.ajax({
        url: "/InventarioPHP/src/main/resources/templates/Personal/PHP/actualizar_inventario.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            console.log("Respuesta del servidor:", response);
            if (response.status === "success") {
                toastr.success("Actualización exitosa.");
            } else {
                toastr.error(response.message, "Error");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la actualización:", xhr.responseText);
        }
    });
});

//Funcion para que se cargue el id de la solicitud anual. 
$(document).ready(function () {
    // Obtener el último id_anual del servidor
    $.ajax({
        url: "/InventarioPHP/src/main/resources/templates/Personal/PHP/obtener_id_anual.php", // Crea un archivo PHP para esta consulta
        type: "GET",
        dataType: "json",
        success: function (response) {
            if (response.status === "success" && response.id_anual) {
                $("#id_anual").val(response.id_anual); // Asignar el valor al campo oculto
                console.log("ID Anual obtenido:", response.id_anual);
            } else {
                console.warn("No se pudo obtener el ID Anual:", response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error al obtener el ID Anual:", xhr.responseText);
        }
    });
});


//Funcion para envio del formulario de Solicitud_anual.php
/* $("#enviar_informe").on("click", function (event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado del botón

    if (!$("#id_anual").val()) {
        toastr.warning("El ID Anual no está disponible. Por favor, intenta nuevamente.");
        return;
    }

    var formData = $("#informeForm").serialize(); // Serializar los datos del formulario
    $("#loadingMessage").show(); // Mostrar mensaje de carga

    $.ajax({
        url: $("#informeForm").attr("action"), // URL definida en el atributo "action" del formulario
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            $("#loadingMessage").hide(); // Ocultar mensaje de carga
            if (response.status === "success" && response.id_anual) {
                $("#id_anual").val(response.id_anual); // Actualizar el campo oculto con el nuevo ID
                toastr.success(response.message, "Éxito");
                console.log("ID Anual actualizado:", response.id_anual);
            } else {
                toastr.error(response.message || "No se pudo generar el ID de la solicitud.", "Error");
            }
        },
        error: function (xhr, status, error) {
            $("#loadingMessage").hide(); // Ocultar mensaje de carga
            console.error("Error al enviar el formulario:", xhr.responseText);
            toastr.error("Ocurrió un error al enviar el formulario.", "Error");
        }
    });
}); */
$("#enviar_informe").on("click", function (event) {
    event.preventDefault();

    if (!$("#id_anual").val()) {
        toastr.warning("El ID Anual no está disponible. Por favor, intenta nuevamente.");
        return;
    }

    var formData = $("#informeForm").serialize();
    console.log("Datos enviados al servidor:", formData); // Registro para depuración
    $("#loadingMessage").show();

    $.ajax({
        url: $("#informeForm").attr("action"),
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            $("#loadingMessage").hide();
            if (response.status === "success" && response.id_anual) {
                $("#id_anual").val(response.id_anual);
                toastr.success(response.message, "Éxito");
            } else {
                toastr.error(response.message || "No se pudo generar el ID de la solicitud.", "Error");
            }
        },
        error: function (xhr, status, error) {
            $("#loadingMessage").hide();
            console.error("Error al enviar el formulario:", xhr.responseText);
            toastr.error("Ocurrió un error al enviar el formulario.", "Error");
        }
    });
});


//BITACORAAAAS
// Función para validacion de datos antes de su envio y evitar duplicados
$("#informeForm").on("submit", function (event) {
    let elementos = [];
    let duplicado = false;

    $("select[name='nom_elemento[]']").each(function () {
        let valor = $(this).val();
        if (elementos.includes(valor)) {
            duplicado = true;
            return false; // Salir del bucle
        }
        elementos.push(valor);
    });

    if (duplicado) {
        event.preventDefault();
        toastr.error("No puedes agregar elementos duplicados.");
        return false;
    }
});
//BITACORAAAAS
//Funcuion para depurar el flujo de datos 
$("#enviar_informe").on("click", function () {
    $(this).prop("disabled", true);
});

// Función para  eviatar que el  cliente solicite mas unidades de las ya existen
$("input[name='solicitada[]']").on("change", function () {
    let $fila = $(this).closest("tr");
    let cantidadSolicitada = parseInt($(this).val(), 10);
    let cantidadDisponible = parseInt($fila.find("input[name='disponible[]']").val(), 10);

    if (cantidadSolicitada > cantidadDisponible) {
        toastr.error("La cantidad solicitada no puede ser mayor que la cantidad disponible.");
        $(this).val(""); // Limpiar el campo
    }
});