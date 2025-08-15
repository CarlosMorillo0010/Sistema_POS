
/**=====================================
 NUEVO LIBRO DE COMPRAS
======================================**/
$("#nuevoProveedor").change(function(){

    var idProveedor = $(this).val();

    var datos = new FormData();
    datos.append("idProveedor", idProveedor);

    $.ajax({
        url: "ajax/proveedores.ajax.php", 
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(respuesta){
            // Suponiendo que el proveedor tiene un campo para los días de crédito
            if(respuesta && respuesta.dias_credito){
                // Guardamos los días de crédito en un input oculto para enviarlo con el formulario
                $("#diasCredito").val(respuesta.dias_credito);
            }
        }
    });
});

/**=====================================
 EDITAR LIBRO DE COMPRAS
 ======================================**/
$(document).on("click", ".btnEditarLibroCompra", function(){
  let idLibroCompra = $(this).attr("idLibroCompra");
  let datos = new FormData();
  datos.append("idLibroCompra", idLibroCompra);

  $.ajax({
    url: "ajax/libro-compras.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function(response) {
        console.log(response);
        $("#idLibroCompra").val(response.id);
        $("#editarNumFactura").val(response.numfactura);
        $("#editarDescripcion").val(response.descripcion);
        $("#editarProveedor").val(response.proveedor);
        $("#editarDiasCredito").val(response.dias_credito);
        $("#editarMonto").val(response.monto);
        $("#editarIva").val(response.iva);
        $("#editarTotal").val(response.total);
        $("#editarMetodoSelect").val(response.metodo);
        $("#editarFecha").val(response.fecha);
        $("#editarRif").val(response.rif);
        $("#editarNumControl").val(response.numcontrol);
        $("#editarTipoDoc").val(response.tipodoc);
        $("#editarEstado").val(response.estado);
        $("#editarEstado").html(response.estado);
        $("#editarObservacion").val(response.observacion);
    }
  });
});

/**=====================================
 ELIMINAR LIBRO DE COMPRAS
 ======================================**/
$(document).on("click", ".btnEliminarLibroCompra", function(){
  let idLibroCompra = $(this).attr("idLibroCompra");

  Swal.fire({
    title: "¿Está seguro de borrar el registro?",
    text: "¡Si no lo está puede cancelar la acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Si, borrar!",
  }).then((result) => {
    if (result.value) {
      window.location = "index.php?ruta=libro-compras&idLibroCompra=" + idLibroCompra;
    }
  });
});

// Función para actualizar el informacion del proveedor al seleccionar uno
function actualizarDatosProveedor(selectId, fieldIds) {
    const select = document.getElementById(selectId);
    if (!select) return;

    select.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const proveedorId = selectedOption.getAttribute('data-id_proveedor');

        // Obtenemos los campos del formulario usando los IDs proporcionados
        const rifInput = document.getElementById(fieldIds.rif);
        const diasCreditoInput = document.getElementById(fieldIds.diasCredito);
        // const telefonoInput = document.getElementById(fieldIds.telefono);

        if (proveedorId) {
            // Realizamos una petición AJAX para obtener los datos del proveedor
            fetch('ajax/libro-compras.ajax.php?id_proveedor=' + proveedorId)
                .then(response => response.json())
                .then(data => {
                    // Actualizamos los campos del formulario con los datos del proveedor
                    if (rifInput) rifInput.value = data.documento || '';
                    if (diasCreditoInput) diasCreditoInput.value = data.dias_credito || '0';
                    // if (telefonoInput) telefonoInput.value = data.telefono || '';
                })
                .catch(error => console.error("Error al obtener datos del proveedor:", error));
        } else {
            // Limpiamos TODOS los campos si no se selecciona un proveedor
            if (rifInput) rifInput.value = '';
            if (diasCreditoInput) diasCreditoInput.value = '';
            // if (telefonoInput) telefonoInput.value = '';
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    
    // Configuración para el nuevo proveedor
    actualizarDatosProveedor('nuevoProveedor', {
        rif: 'nuevoRif',
        diasCredito: 'diasCredito',
        // telefono: 'telefonoProveedor'
    });
    
    // Configuración para el proveedor en edición
    actualizarDatosProveedor('editarProveedor', {
        rif: 'editarRif',
        diasCredito: 'editarDiasCredito',
        // telefono: 'editarTelefonoProveedor'
    });
});

// Función para calcular el total en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    // Para agregar
    const montoInput = document.getElementById('nuevoMonto');
    const ivaInput = document.getElementById('nuevoIva');
    const totalInput = document.getElementById('nuevoTotal');

    // Para editar
    const montoEditInput = document.getElementById('editarMonto');
    const ivaEditInput = document.getElementById('editarIva');
    const totalEditInput = document.getElementById('editarTotal');

    function calcularTotal(montoElem, ivaElem, totalElem) {
        if (montoElem && ivaElem && totalElem) {
            const monto = parseFloat(montoElem.value) || 0;
            const iva = parseFloat(ivaElem.value) || 0;
            totalElem.value = (monto + iva).toFixed(2);
        }
    }

    if (montoInput && ivaInput && totalInput) {
        montoInput.addEventListener('input', function() {
            calcularTotal(montoInput, ivaInput, totalInput);
        });
        ivaInput.addEventListener('input', function() {
            calcularTotal(montoInput, ivaInput, totalInput);
        });
    }

    if (montoEditInput && ivaEditInput && totalEditInput) {
        montoEditInput.addEventListener('input', function() {
            calcularTotal(montoEditInput, ivaEditInput, totalEditInput);
        });
        ivaEditInput.addEventListener('input', function() {
            calcularTotal(montoEditInput, ivaEditInput, totalEditInput);
        });
    }
});

function setupNumberFormatting(inputEl) {
    // Formatea al salir del campo
    inputEl.addEventListener('blur', function() {
        let rawValue = inputEl.value.replace(/\./g, '').replace(/,/g, '.');
        let value = parseFloat(rawValue);
        if (!isNaN(value) && rawValue !== '') {
            inputEl.value = value.toLocaleString('es-ES', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } else {
            inputEl.value = '';
        }
    });

    // Limpia el formato al entrar al campo
    inputEl.addEventListener('focus', function() {
        if (inputEl.value) {
            let rawValue = inputEl.value.replace(/\./g, '').replace(/,/g, '.');
            inputEl.value = rawValue;
        }
    });
}