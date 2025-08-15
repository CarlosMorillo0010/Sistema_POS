$(document).ready(function () {
  "use strict";

  //======================================================================
  // == 0. CONFIGURACIÓN INICIAL Y VARIABLES GLOBALES
  //======================================================================
  const configDiv = $("#config-vars");
  const TASA_BCV = parseFloat(configDiv.data("tasa-bcv")) || 0;
  const IVA_PORCENTAJE = parseFloat(configDiv.data("iva-porcentaje")) || 16;
  var listaProductosOculta = [];
  var granTotalUsdModal = 0;

  // VALIDACIÓN CRÍTICA PARA PRODUCCIÓN
  if (TASA_BCV === 0) {
    swal({
      title: "Error de Configuración Crítico",
      text: "La tasa de cambio (BCV) no está disponible o es cero. No se pueden procesar ventas para evitar errores de cálculo. Por favor, contacte al administrador del sistema.",
      type: "error",
      confirmButtonText: "Entendido",
      allowOutsideClick: false,
    });
    // Deshabilitamos toda la interacción para prevenir cualquier operación.
    $(".pos-container")
      .find("button, .product-card, input, select")
      .prop("disabled", true)
      .css({ cursor: "not-allowed", opacity: 0.5 });
    return; // Detiene la ejecución del resto del script.
  }

  //======================================================================
  // == 1. LÓGICA DE CARGA DE PRODUCTOS Y CATEGORÍAS
  //======================================================================
  function cargarProductos(idCategoria) {
    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: { idCategoriaFiltro: idCategoria },
      dataType: "json",
      beforeSend: function () {
        $("#productosCategoria").html(
          '<div class="col-xs-12 text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-3x"></i></div>'
        );
      },
      success: function (productos) {
        $("#productosCategoria").empty();
        if (!productos || productos.length === 0) {
          $("#productosCategoria").html(
            '<div class="col-xs-12 alert alert-info">No hay productos disponibles en esta categoría.</div>'
          );
          return;
        }
        $.each(productos, function (i, producto) {
          const precioFormateado = parseFloat(producto.pvp_referencia).toFixed(
            2
          );
          const imagenSrc =
            producto.imagen && producto.imagen !== ""
              ? producto.imagen
              : "view/img/products/default/anonymous.png";
          const productCardHtml = `
              <div class="product-card agregarProducto" idProducto="${
                producto.id_producto
              }" style="cursor: pointer;">
                <img src="${imagenSrc}" alt="${
            producto.descripcion
          }" onerror="this.onerror=null;this.src='view/img/products/default/anonymous.png';">
                <div class="product-info">
                  <h3 class="product-name">${producto.descripcion}</h3>
                  <div class="product-meta">
                    <span class="meta-item"> ${
                      producto.categoria || "Sin Cat."
                    }</span>
                    <span class="meta-item"> Marca: ${
                      producto.marca || "N/A"
                    }</span>
                    <span class="meta-item"> Código: ${
                      producto.codigo || "S/C"
                    }</span>
                    <span class="meta-item"> Stock: ${producto.stock}</span>
                  </div>
                </div>
                <div class="product-price">$${precioFormateado}</div>
              </div>`;
          $("#productosCategoria").append(productCardHtml);
        });
      },
      error: function (xhr) {
        $("#productosCategoria").html(
          '<div class="col-xs-12 alert alert-danger">Error al cargar los productos. Por favor, recargue la página.</div>'
        );
        console.error("Error en AJAX al cargar productos:", xhr.responseText);
      },
    });
  }

  $(".category-tabs").on("click", ".btn-categoria", function () {
    $(".btn-categoria").removeClass("active");
    $(this).addClass("active");
    cargarProductos($(this).data("id-categoria"));
  });

  // Carga inicial de todos los productos
  cargarProductos("todos");

  //======================================================================
  // == 2. LÓGICA DEL CARRITO DE COMPRAS (AÑADIR, MODIFICAR, QUITAR)
  //======================================================================
  $(".product-grid").on("click", ".agregarProducto", function () {
    const idProducto = $(this).attr("idProducto");
    const itemExistente = $(`.order-item[idProducto="${idProducto}"]`);

    // Si el producto ya está en el carrito, simplemente incrementa la cantidad.
    if (itemExistente.length > 0) {
      itemExistente.find(".btn-agregar-uno").trigger("click");
      return;
    }

    // Si es un producto nuevo, lo busca por AJAX.
    const datos = new FormData();
    datos.append("idProducto", idProducto);
    $.ajax({
      url: "ajax/productos.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function (respuesta) {
        if (parseInt(respuesta.stock) <= 0) {
          swal(
            "Sin Stock",
            "No hay unidades disponibles de este producto.",
            "error"
          );
          return;
        }
        const itemHtml = `
            <div class="order-item" idProducto="${respuesta.id_producto}">
              <img src="${respuesta.imagen}" alt="${respuesta.descripcion}">
              <div class="item-details">
                <div class="item-name">${respuesta.descripcion}</div>
                <div class="quantity-controls">
                  <button type="button" class="btn-quitar-uno">-</button>
                  <input type="number" class="cantidad-producto" value="1" min="1" stock="${
                    respuesta.stock
                  }">
                  <button type="button" class="btn-agregar-uno">+</button>
                </div>
              </div>
              <div class="item-price" pvp_referencia="${
                respuesta.pvp_referencia
              }">$${parseFloat(respuesta.pvp_referencia).toFixed(2)}</div>
              <button type="button" class="btn-remover-item quitarProducto"><i class="fa fa-times"></i></button>
            </div>`;
        $(".order-items").append(itemHtml);
        actualizarTotales();
      },
    });
  });

  // Delegación de eventos para los controles de cantidad y eliminación en el carrito.
  $(".order-items")
    .on("click", ".btn-agregar-uno", function () {
      const input = $(this).siblings(".cantidad-producto");
      const stock = parseInt(input.attr("stock"));
      let nuevaCantidad = parseInt(input.val()) + 1;
      if (nuevaCantidad <= stock) {
        input.val(nuevaCantidad).trigger("change");
      } else {
        swal("Stock máximo", "No hay más unidades disponibles.", "warning");
      }
    })
    .on("click", ".btn-quitar-uno", function () {
      const input = $(this).siblings(".cantidad-producto");
      let nuevaCantidad = parseInt(input.val()) - 1;
      if (nuevaCantidad >= 1) {
        input.val(nuevaCantidad).trigger("change");
      }
    })
    .on("change keyup", ".cantidad-producto", function () {
      let cantidad = parseInt($(this).val());
      const stock = parseInt($(this).attr("stock"));
      if (isNaN(cantidad) || cantidad < 1) {
        $(this).val(1);
      } else if (cantidad > stock) {
        swal(
          "Stock máximo",
          `Solo hay ${stock} unidades disponibles.`,
          "error"
        );
        $(this).val(stock);
      }
      actualizarTotales();
    })
    .on("click", ".quitarProducto", function () {
      $(this).closest(".order-item").remove();
      actualizarTotales();
    });

  //======================================================================
  // == 3. CÁLCULO DE TOTALES Y ACTUALIZACIÓN DE DATOS
  //======================================================================
  function actualizarTotales() {
    let subtotalUsd = 0;
    $(".order-item").each(function () {
      const cantidad = parseInt($(this).find(".cantidad-producto").val()) || 1;
      const precioUnitario = parseFloat(
        $(this).find(".item-price").attr("pvp_referencia")
      );
      subtotalUsd += cantidad * precioUnitario;
    });

    const montoIvaUsd = subtotalUsd * (IVA_PORCENTAJE / 100);
    const granTotalUsd = subtotalUsd + montoIvaUsd;
    const subtotalBs = subtotalUsd * TASA_BCV;
    const ivaBs = montoIvaUsd * TASA_BCV;
    const granTotalBs = granTotalUsd * TASA_BCV;
    const formatoVeLocale = {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    };

    // Actualizar la interfaz de usuario (lo que ve el cajero)
    $("#subtotalUsdDisplay").text("$" + subtotalUsd.toFixed(2));
    $("#subtotalBsDisplay").text(
      "(" + subtotalBs.toLocaleString("es-VE", formatoVeLocale) + " Bs)"
    );
    $("#ivaUsdDisplay").text("$" + montoIvaUsd.toFixed(2));
    $("#ivaBsDisplay").text(
      "(" + ivaBs.toLocaleString("es-VE", formatoVeLocale) + " Bs)"
    );
    $("#displayTotalBs").text(
      granTotalBs.toLocaleString("es-VE", formatoVeLocale) + " Bs"
    );
    $("#displayTotalVenta").text("$" + granTotalUsd.toFixed(2));

    // Actualizar los inputs ocultos que se enviarán al servidor
    $("#tasaDelDia").val(TASA_BCV);
    $("#subtotalUsd").val(subtotalUsd.toFixed(2));
    $("#subtotalBs").val(subtotalBs.toFixed(2));
    $("#ivaUsd").val(montoIvaUsd.toFixed(2));
    $("#ivaBs").val(ivaBs.toFixed(2));
    $("#totalUsd").val(granTotalUsd.toFixed(2));
    $("#totalBs").val(granTotalBs.toFixed(2));

    // Preparar el JSON de productos para el backend
    listarProductosParaBackend();
  }

  function listarProductosParaBackend() {
    listaProductosOculta = [];
    $(".order-item").each(function () {
      const item = $(this);
      const cantidad = item.find(".cantidad-producto").val();
      const precioUnitario = item.find(".item-price").attr("pvp_referencia");
      listaProductosOculta.push({
        id: item.attr("idProducto"),
        descripcion: item.find(".item-name").text().trim(),
        cantidad: cantidad,
        stock: item.find(".cantidad-producto").attr("stock"),
        pvp_referencia: precioUnitario,
        total: (parseFloat(cantidad) * parseFloat(precioUnitario)).toFixed(2),
      });
    });
    $("#listaProductosCaja").val(JSON.stringify(listaProductosOculta));
  }

  //======================================================================
  // == 4. FLUJO DE PAGO Y MODALES
  //======================================================================

  // -- Modal 1: Selección de Método de Pago --
  $("#modalMetodoPago").on("show.bs.modal", function (e) {
    if ($(".order-items .order-item").length === 0) {
      swal(
        "Carrito vacío",
        "¡Debes agregar productos antes de proceder al pago!",
        "error"
      );
      return e.preventDefault();
    }
    // **AQUÍ LA CORRECCIÓN CLAVE**: Se lee desde el input oculto #totalUsd
    const granTotalUsd = parseFloat($("#totalUsd").val()) || 0;
    const granTotalBs = granTotalUsd * TASA_BCV;
    const formatoVeLocale = {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    };

    $("#totalPagarUsd").text("$" + granTotalUsd.toFixed(2));
    $("#totalPagarBs").text(
      granTotalBs.toLocaleString("es-VE", formatoVeLocale) + " Bs"
    );
  });

  // Al seleccionar un método, pasamos al siguiente modal
  $("#modalMetodoPago").on("click", ".payment-option", function () {
    $("#modalMetodoPago").modal("hide");
    $("#modalConfirmacionPago").modal("show");
  });

  // -- Modal 2: Confirmación de Pago y Vuelto --
  $("#modalConfirmacionPago").on("show.bs.modal", function () {
    // **AQUÍ LA CORRECCIÓN CLAVE**: Se lee desde el input oculto #totalUsd
    granTotalUsdModal = parseFloat($("#totalUsd").val()) || 0;
    const granTotalBsModal = granTotalUsdModal * TASA_BCV;
    const formatoVeLocale = {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    };

    // Actualizar totales en este modal
    $("#totalPagarModalUsd").text("$" + granTotalUsdModal.toFixed(2));
    $("#totalPagarModalBs").text(
      granTotalBsModal.toLocaleString("es-VE", formatoVeLocale) + " Bs"
    );

    // Resetear el estado del modal para una nueva operación
    $("#montoRecibidoUsd, #montoRecibidoBs").val("");
    $(".conversion-display").removeClass("visible").val("");
    $(".vuelto-container").hide();
    $("#alertaMontoInsuficiente").hide();
    $("#btnConfirmarYCrearVenta").prop("disabled", true);

    calcularPagosYVuelto();
    setTimeout(() => $("#montoRecibidoUsd").focus(), 500); // Foco para agilizar
  });

  // Formato y cálculo de pagos
  $("#montoRecibidoUsd, #montoRecibidoBs").on(
    "input keyup",
    calcularPagosYVuelto
  );

  function calcularPagosYVuelto() {
    const recibidoUsd =
      parseFloat(
        $("#montoRecibidoUsd")
          .val()
          .replace(/[^0-9.]/g, "")
      ) || 0;
    const recibidoBs =
      parseFloat(
        $("#montoRecibidoBs").val().replace(/\./g, "").replace(",", ".")
      ) || 0;
    const formatoVeLocale = {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    };

    // Mostrar conversiones en tiempo real
    if (recibidoUsd > 0) {
      const conversionBs = recibidoUsd * TASA_BCV;
      $("#conversionDisplayBs")
        .val(`≈ ${conversionBs.toLocaleString("es-VE", formatoVeLocale)} Bs`)
        .addClass("visible");
    } else {
      $("#conversionDisplayBs").removeClass("visible");
    }
    if (recibidoBs > 0) {
      const conversionUsd = TASA_BCV > 0 ? recibidoBs / TASA_BCV : 0;
      $("#conversionDisplayUsd")
        .val(`≈ $${conversionUsd.toFixed(2)}`)
        .addClass("visible");
    } else {
      $("#conversionDisplayUsd").removeClass("visible");
    }

    // Calcular el total recibido y el vuelto
    const totalRecibidoEnUsd =
      recibidoUsd + (TASA_BCV > 0 ? recibidoBs / TASA_BCV : 0);
    const diferencia = totalRecibidoEnUsd - granTotalUsdModal;

    if (diferencia >= -0.009) {
      // Tolerancia para errores de redondeo
      const vueltoEnUsd = diferencia > 0 ? diferencia : 0;
      const vueltoEnBs = vueltoEnUsd * TASA_BCV;
      $("#alertaMontoInsuficiente").hide();
      $(".vuelto-container").show();
      $("#vueltoDisplayUsd").text("$" + vueltoEnUsd.toFixed(2));
      $("#vueltoDisplayBs").text(
        vueltoEnBs.toLocaleString("es-VE", formatoVeLocale) + " Bs"
      );
      $("#btnConfirmarYCrearVenta").prop("disabled", false);
    } else {
      $("#alertaMontoInsuficiente").show();
      $(".vuelto-container").hide();
      $("#btnConfirmarYCrearVenta").prop("disabled", true);
    }
  }

  //======================================================================
  // == 5. ACCIONES FINALES DEL FORMULARIO DE VENTA
  //======================================================================
  $("#btnVolverMetodos").on("click", function () {
    $("#modalConfirmacionPago").modal("hide");
    $("#modalMetodoPago").modal("show");
  });

  $("#btnConfirmarYCrearVenta").on("click", function () {
    const boton = $(this);
    // Deshabilitar botón para prevenir doble envío
    boton
      .prop("disabled", true)
      .html('<i class="fa fa-spinner fa-spin"></i> CREANDO VENTA...');

    // Determinar el método de pago para el backend
    const recibidoUsd = parseFloat($("#montoRecibidoUsd").val()) || 0;
    const recibidoBs = parseFloat($("#montoRecibidoBs").val()) || 0;
    let metodoPagoDesc = "Pago Exacto"; // Valor por defecto
    if (recibidoUsd > 0 && recibidoBs > 0) {
      metodoPagoDesc = "Pago Mixto";
    } else if (recibidoUsd > 0) {
      metodoPagoDesc = "Pago en Dólares";
    } else if (recibidoBs > 0) {
      metodoPagoDesc = "Pago en Bolívares";
    }
    $("#listaMetodoPago").val(metodoPagoDesc);

    // Validación final: Cliente seleccionado
    if ($("#seleccionarCliente").val() === "") {
      $("#modalConfirmacionPago").modal("hide");
      swal({
        title: "Cliente no seleccionado",
        text: "Por favor, selecciona un cliente para continuar.",
        type: "warning",
        confirmButtonText: "Entendido",
      }).then(() => {
        // Al cerrar la alerta, se vuelve a mostrar el modal de pago
        $("#modalConfirmacionPago").modal("show");
      });
      // Reactivar el botón si la validación falla
      boton
        .prop("disabled", false)
        .html('<i class="fa fa-check-circle"></i> CREAR VENTA');
      return;
    }

    // Si todo está bien, enviar el formulario
    $("#formularioVentas").submit();
  });
});
