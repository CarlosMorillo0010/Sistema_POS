/**
 * =================================================================================
 * SCRIPT DE GESTIÓN DE PRODUCTOS (productos.js) - VERSIÓN CORREGIDA
 * =================================================================================
 * NOTA: Este script depende del gestor de configuración global 'APP_CONFIG'
 * que debe ser cargado previamente (a través de config-updater.js).
 *
 * Funcionalidades:
 * 1.  Cálculo dinámico de precios en los modales de "Agregar" y "Editar".
 * 2.  Usa APP_CONFIG para obtener la Tasa BCV y el IVA en tiempo real.
 * 3.  Escucha el evento 'configUpdated' para reaccionar a cambios en la tasa.
 * 4.  Manejo de DataTables para la visualización y búsqueda de productos.
 * 5.  Lógica para los modales de agregar, editar y eliminar productos.
 * 6.  Previsualización de imágenes.
 * =================================================================================
 */

$(document).ready(function() {

    // --- SECCIÓN 1: FUNCIONES AUXILIARES ---

    function parseNumber(str) {
        if (typeof str !== 'string' || str.trim() === '') return 0;
        return parseFloat(String(str).replace(/\./g, '').replace(',', '.')) || 0;
    }

    function formatNumber(num) {
        if (typeof num !== 'number' || isNaN(num)) return "0,00";
        return num.toLocaleString('es-VE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // --- SECCIÓN 2: LÓGICA DE CÁLCULO DE PRECIOS ---

    function calcularTodosLosPrecios(prefix) {
        const TASA_BCV_ACTUAL = window.APP_CONFIG.get('tasa_bcv');
        const modal = (prefix === 'nuevo') ? $('#modalAgregarProducto') : $('#modalEditarProducto');
        
        const monedaActiva = modal.find('.btn-moneda-entrada.active').data('moneda');
        const costoInput = parseNumber(modal.find(`#${prefix}PrecioCosto`).val());
        const gananciaPorc = parseNumber(modal.find(`#${prefix}PorcentajeGanancia`).val());
        const ivaTasaPorc = parseFloat(modal.find(`#${prefix}TipoIva option:selected`).data('tasa')) || 0;

        let costoEnUsd = 0;
        let costoEnBs = 0;

        if (monedaActiva === 'USD') {
            costoEnUsd = costoInput;
            costoEnBs = costoInput * TASA_BCV_ACTUAL;
        } else {
            costoEnBs = costoInput;
            costoEnUsd = (TASA_BCV_ACTUAL > 0) ? costoInput / TASA_BCV_ACTUAL : 0;
        }

        const ventaBaseUsd = costoEnUsd * (1 + (gananciaPorc / 100));
        const ventaBaseBs = costoEnBs * (1 + (gananciaPorc / 100));
        const montoIvaUsd = ventaBaseUsd * (ivaTasaPorc / 100);
        const montoIvaBs = ventaBaseBs * (ivaTasaPorc / 100);
        const pvpFinalUsd = ventaBaseUsd + montoIvaUsd;
        const pvpFinalBs = ventaBaseBs + montoIvaBs;

        const suffix = (prefix === 'editar') ? '_editar' : '_nuevo';
        modal.find(`#resumenCostoUsd${suffix}`).val(formatNumber(costoEnUsd));
        modal.find(`#resumenVentaUsd${suffix}`).val(formatNumber(ventaBaseUsd));
        modal.find(`#resumenIvaUsd${suffix}`).val(formatNumber(montoIvaUsd));
        modal.find(`#resumenPvpUsd${suffix}`).val(formatNumber(pvpFinalUsd));
        modal.find(`#resumenCostoBs${suffix}`).val(formatNumber(costoEnBs));
        modal.find(`#resumenVentaBs${suffix}`).val(formatNumber(ventaBaseBs));
        modal.find(`#resumenIvaBs${suffix}`).val(formatNumber(montoIvaBs));
        modal.find(`#resumenPvpBs${suffix}`).val(formatNumber(pvpFinalBs));
        modal.find(`#monedaEntrada_${prefix}`).val(monedaActiva);
    }
    
    // --- SECCIÓN 3: EVENT HANDLERS (ESCUCHADORES DE EVENTOS) ---

    $(document).on('click', '.btn-moneda-entrada', function() {
        const boton = $(this);
        if (boton.hasClass('active')) return;

        const modal = boton.closest('.modal');
        const prefix = modal.attr('id').includes('Agregar') ? 'nuevo' : 'editar';
        
        modal.find('.btn-moneda-entrada').removeClass('active btn-primary').addClass('btn-default');
        boton.addClass('active btn-primary').removeClass('btn-default');
        
        const moneda = boton.data('moneda');
        const labelSuffix = (prefix === 'editar') ? '-editar' : '';
        const textoLabel = `<span style="color: red;">*</span> Costo en ${moneda === 'USD' ? 'Dólares' : 'Bolívares'} (Sin IVA)`;
        modal.find(`#label-costo${labelSuffix}`).html(textoLabel);
        
        calcularTodosLosPrecios(prefix);
    });

    const camposCalculo = '#nuevoPrecioCosto, #nuevoPorcentajeGanancia, #nuevoTipoIva, #editarPrecioCosto, #editarPorcentajeGanancia, #editarTipoIva';
    $(document).on('input change keyup', camposCalculo, function() {
        const prefix = $(this).attr('id').startsWith('nuevo') ? 'nuevo' : 'editar';
        calcularTodosLosPrecios(prefix);
    });

    // --- SECCIÓN 4: GESTIÓN DE MODALES Y DATATABLES ---

    $('#modalAgregarProducto').on('show.bs.modal', function () {
        const modal = $(this);
        modal.find('form')[0].reset();
        modal.find('.select2').val(null).trigger('change');
        modal.find('.previsualizar').attr('src', 'view/img/products/default/anonymous.png');
        
        // <-- !! CORRECCIÓN APLICADA AQUÍ !!
        // Definimos la constante MONEDA_PRINCIPAL para que esté disponible en el ámbito de esta función.
        const MONEDA_PRINCIPAL = window.APP_CONFIG.get('moneda_principal');
        
        modal.find('.btn-moneda-entrada').removeClass('active btn-primary').addClass('btn-default');
        const defaultMonedaBtn = modal.find(`.btn-moneda-entrada[data-moneda="${MONEDA_PRINCIPAL}"]`);
        defaultMonedaBtn.addClass('active btn-primary').removeClass('btn-default');
        
        const textoLabel = `<span style="color: red;">*</span> Costo en ${MONEDA_PRINCIPAL === 'USD' ? 'Dólares' : 'Bolívares'} (Sin IVA)`;
        modal.find('#label-costo').html(textoLabel);
        modal.find("#nuevoPorcentajeGanancia").val("30");
        modal.find("#nuevoTipoIva").val("gravado");

        calcularTodosLosPrecios('nuevo');
    });
    
    $('.tablaProductos').DataTable({
        "ajax": "ajax/datatable-productos.ajax.php?perfilOculto="+$("#perfilOculto").val(),
        "deferRender": true, 
        "retrieve": true, 
        "processing": true, 
        "order": [[0, "desc"]],
        "language": { "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json" }
    });

    $(".tablaProductos").on("click", ".btnEditarProducto", function() {
        const idProducto = $(this).attr("idProducto");
        
        $.ajax({
            url: "ajax/productos.ajax.php", 
            method: "POST", 
            data: { idProducto: idProducto }, 
            dataType: "json",
            success: function(respuesta) {
                if (!respuesta) {
                    Swal.fire({ title: "Error", text: "No se pudo encontrar la información del producto.", icon: "error" });
                    return;
                }
                
                const modal = $('#modalEditarProducto');
                
                modal.find("#idProducto").val(respuesta.id_producto);
                modal.find("#editarCodigo").val(respuesta.codigo);
                modal.find("#editarDescripcion").val(respuesta.descripcion);
                modal.find("#editarStock").val(respuesta.stock);
                modal.find("#editarAno").val(respuesta.ano);
                modal.find("#editarUbicacion").val(respuesta.ubicacion);
                modal.find("#imagenActual").val(respuesta.imagen);
                
                modal.find("#editarCategoria").val(respuesta.id_categoria);
                modal.find("#editarMarca").val(respuesta.marca).trigger('change');
                modal.find("#editarModelo").val(respuesta.modelo).trigger('change');
                
                // Aquí ya estaba definido correctamente, por eso el modal de editar funcionaba bien.
                const MONEDA_PRINCIPAL = window.APP_CONFIG.get('moneda_principal');
                const monedaGuardada = respuesta.moneda_costo || MONEDA_PRINCIPAL;
                
                modal.find('.btn-moneda-entrada').removeClass('active btn-primary').addClass('btn-default');
                modal.find(`.btn-moneda-entrada[data-moneda="${monedaGuardada}"]`).addClass('active btn-primary').removeClass('btn-default');
                modal.find('#label-costo-editar').html(`<span style="color: red;">*</span> Costo en ${monedaGuardada === 'USD' ? 'Dólares' : 'Bolívares'} (Sin IVA)`);

                let costoAEditar = (monedaGuardada === 'USD') ? parseFloat(respuesta.costo_referencia) : parseFloat(respuesta.precio_costo);
                modal.find("#editarPrecioCosto").val(formatNumber(costoAEditar));

                modal.find("#editarPorcentajeGanancia").val(String(respuesta.porcentaje_ganancia).replace('.',','));
                modal.find("#editarTipoIva").val(parseInt(respuesta.id_impuesto) === 0 ? "exento" : "gravado");

                calcularTodosLosPrecios('editar');
                
                modal.find(".previsualizar").attr("src", (respuesta.imagen && respuesta.imagen !== "") ? respuesta.imagen : "view/img/products/default/anonymous.png");
                modal.modal('show');
            },
            error: function() {
                Swal.fire({ title: "Error de comunicación", text: "No se pudieron cargar los datos del producto. Revise la conexión.", icon: "error" });
            }
        });
    });

    $(".tablaProductos").on("click", ".btnEliminarProducto", function() {
        const idProducto = $(this).attr("idProducto");
        const codigo = $(this).attr("codigo");
        const imagen = $(this).attr("imagen");

        Swal.fire({
            title: '¿Está seguro de borrar el producto?', 
            text: "¡Si no lo está, puede cancelar la acción!", 
            icon: 'warning',
            showCancelButton: true, 
            confirmButtonColor: '#3085d6', 
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar', 
            confirmButtonText: '¡Sí, borrar producto!'
        }).then(function(result) {
            if (result.value) {
                window.location = "index.php?ruta=productos&idProducto=" + idProducto + "&imagen=" + imagen + "&codigo=" + codigo;
            }
        });
    });

    // --- SECCIÓN 5: PREVISUALIZACIÓN DE IMAGEN ---

    $(".imagenProducto").change(function() {
        const imagen = this.files[0];
        if (!imagen) return;
        
        if (imagen.type !== "image/jpeg" && imagen.type !== "image/png") {
            $(this).val("");
            Swal.fire({ title: "Error de formato", text: "La imagen debe estar en formato JPG o PNG", type: "error", confirmButtonText: "Cerrar" });
        } else if (imagen.size > 2000000) {
            $(this).val("");
            Swal.fire({ title: "Error de tamaño", text: "La imagen no debe pesar más de 2MB", type: "error", confirmButtonText: "Cerrar" });
        } else {
            const datosImagen = new FileReader();
            datosImagen.readAsDataURL(imagen);
            $(datosImagen).on("load", function(event) {
                $(this).closest('.form-group, .modal-body').find('.previsualizar').attr("src", event.target.result);
            }.bind(this));
        }
    });

    // --- SECCIÓN 6: LISTENER GLOBAL PARA ACTUALIZACIONES DE CONFIGURACIÓN ---
    
    document.addEventListener('configUpdated', function(e) {
        const nuevaTasa = e.detail.newConfig.tasa_bcv;
        const tasaFormateada = formatNumber(nuevaTasa);

        $('h4:contains("Tasa BCV:")').text(`Estructura de Precios (Tasa BCV: ${tasaFormateada})`);

        if ($('#modalAgregarProducto').is(':visible')) {
            calcularTodosLosPrecios('nuevo');
        }
        if ($('#modalEditarProducto').is(':visible')) {
            calcularTodosLosPrecios('editar');
        }
        
        Swal.fire({
            title: 'Tasa de Cambio Actualizada',
            text: `La nueva tasa del BCV es ${tasaFormateada}. Los cálculos se han ajustado automáticamente.`,
            icon: 'info',
            timer: 4000,
            showConfirmButton: false
        });
    });
});