$(document).ready(function () {
  "use strict";

  //======================================================================
  // == 0. CONFIGURACIÓN INICIAL Y VARIABLES GLOBALES
  //======================================================================
  const configDiv = $("#config-vars");
  const TASA_BCV = parseFloat(configDiv.data("tasa-bcv")) || 0;
  const IVA_PORCENTAJE = parseFloat(configDiv.data("iva-porcentaje")) || 16;

  // if (TASA_BCV === 0) {
  //   // console.error(
  //   //   "¡Atención! La Tasa BCV es cero. Los cálculos no serán correctos."
  //   // );
  //   swal({
  //     title: "Error de Configuración",
  //     text: "La tasa de cambio (BCV) no está disponible o es cero. Por favor, verifique la configuración del sistema.",
  //     type: "error",
  //     confirmButtonText: "Entendido",
  //   });
  // }

  var listaProductosOculta = [];
  var granTotalUsdModal = 0;

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
            producto.imagen &&
            producto.imagen !== "" &&
            producto.imagen !== "view/img/products/default/anonymous.png"
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
                    <span class="meta-item"> Marca: ${producto.marca}</span>
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
          '<div class="col-xs-12 alert alert-danger">Error al cargar los productos.</div>'
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

  cargarProductos("todos");

  //======================================================================
  // == 2. LÓGICA DEL CARRITO DE COMPRAS (AÑADIR, MODIFICAR, QUITAR)
  //======================================================================

  $(".pos-container").on("click", ".agregarProducto", function () {
    const idProducto = $(this).attr("idProducto");
    const itemExistente = $(`.order-item[idProducto="${idProducto}"]`);

    if (itemExistente.length > 0) {
      itemExistente.find(".btn-agregar-uno").trigger("click");
      return;
    }

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
  // == 3. CÁLCULO DE TOTALES Y ACTUALIZACIÓN DE LA VISTA
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

    $("#nuevoTotalVenta").val(granTotalUsd.toFixed(2));
    $("#totalVenta").val(granTotalUsd.toFixed(2));

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
  // == 4. FLUJO DE PAGO Y FORMATO DE MONEDA
  //======================================================================

  $("#modalMetodoPago").on("show.bs.modal", function (e) {
    if ($(".order-items .order-item").length === 0) {
      swal(
        "Carrito vacío",
        "¡Debes agregar productos antes de proceder al pago!",
        "error"
      );
      return e.preventDefault();
    }
    const granTotalUsd = parseFloat($("#nuevoTotalVenta").val()) || 0;
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

  $("#modalMetodoPago").on("click", ".payment-option", function () {
    $("#modalMetodoPago").modal("hide");
    $("#modalConfirmacionPago").modal("show");
  });

  $("#modalConfirmacionPago").on("show.bs.modal", function () {
    granTotalUsdModal = parseFloat($("#nuevoTotalVenta").val()) || 0;
    const granTotalBsModal = granTotalUsdModal * TASA_BCV;
    const formatoVeLocale = {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    };

    $("#totalPagarModalUsd").text("$" + granTotalUsdModal.toFixed(2));
    $("#totalPagarModalBs").text(
      granTotalBsModal.toLocaleString("es-VE", formatoVeLocale) + " Bs"
    );

    $("#montoRecibidoUsd, #montoRecibidoBs").val("");
    $(".conversion-display").removeClass("visible");
    $(".vuelto-container").hide();
    $("#alertaMontoInsuficiente").hide();
    $("#btnConfirmarYCrearVenta").prop("disabled", true);

    calcularPagosYVuelto();

    setTimeout(() => $("#montoRecibidoUsd").focus(), 500);
  });

  // -- 4.1 FUNCIONES Y EVENTOS PARA FORMATEAR MONEDA EN TIEMPO REAL --

  function getNumericValueUsd(formattedValue) {
    return parseFloat(String(formattedValue)) || 0;
  }

  function getNumericValueBs(formattedValue) {
    if (!formattedValue) return 0;
    const cleanValue = String(formattedValue)
      .replace(/\./g, "")
      .replace(",", ".");
    return parseFloat(cleanValue) || 0;
  }

  $("#montoRecibidoUsd").on("input", function (e) {
    let value = $(this).val();
    value = value.replace(/[^0-9.]/g, "");
    const parts = value.split(".");
    if (parts.length > 2) {
      value = parts[0] + "." + parts.slice(1).join("");
    }
    if (parts.length > 1 && parts[1].length > 2) {
      value = parts[0] + "." + parts[1].substring(0, 2);
    }
    $(this).val(value);
  });

  // =========================================================================
  // == FUNCIÓN CORREGIDA Y REFINADA PARA EL FORMATO DE BOLÍVARES (Bs) ==
  // =========================================================================
  $("#montoRecibidoBs").on("input", function (e) {
    const input = e.target;
    let value = input.value;

    // Guardar la posición original del cursor
    const originalCursorPos = input.selectionStart;
    const originalLength = value.length;

    // 1. Limpiar el valor: quitar todo excepto números y UNA coma.
    let cleanValue = value.replace(/[^0-9,]/g, "");
    const firstCommaIndex = cleanValue.indexOf(",");
    if (firstCommaIndex !== -1) {
      cleanValue =
        cleanValue.substring(0, firstCommaIndex + 1) +
        cleanValue.substring(firstCommaIndex + 1).replace(/,/g, "");
    }

    // 2. Separar parte entera y decimal
    let [integerPart, decimalPart] = cleanValue.split(",");

    // 3. Formatear la parte entera con puntos como separadores de miles
    // Quitar puntos existentes para reformatear correctamente
    integerPart = integerPart.replace(/\./g, "");
    const formattedIntegerPart = integerPart
      ? new Intl.NumberFormat("de-DE").format(integerPart)
      : "";

    // 4. Limitar los decimales a 2 dígitos
    if (decimalPart !== undefined) {
      decimalPart = decimalPart.substring(0, 2);
    }

    // 5. Reconstruir el valor final
    let finalValue = formattedIntegerPart;
    if (decimalPart !== undefined) {
      finalValue += "," + decimalPart;
    }

    // Si el valor original era solo una coma, mostrar "0,"
    if (value === ",") {
      finalValue = "0,";
    }

    // 6. Actualizar el valor y restaurar la posición del cursor de forma inteligente
    input.value = finalValue;
    const newLength = finalValue.length;
    const newCursorPos = originalCursorPos + (newLength - originalLength);

    // Asegurarse de que el cursor no se posicione en un lugar inválido
    input.setSelectionRange(newCursorPos, newCursorPos);
  });

  // -- 4.2 CÁLCULO DE PAGOS Y VUELTOS CON LOS VALORES FORMATEADOS --

  $("#montoRecibidoUsd, #montoRecibidoBs").on(
    "input keyup",
    calcularPagosYVuelto
  );

  function calcularPagosYVuelto() {
    const recibidoUsd = getNumericValueUsd($("#montoRecibidoUsd").val());
    const recibidoBs = getNumericValueBs($("#montoRecibidoBs").val());

    const formatoVeLocale = {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    };

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

    const totalRecibidoEnUsd =
      recibidoUsd + (TASA_BCV > 0 ? recibidoBs / TASA_BCV : 0);
    const diferencia = totalRecibidoEnUsd - granTotalUsdModal;

    if (diferencia >= -0.009) {
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
    const recibidoUsd = getNumericValueUsd($("#montoRecibidoUsd").val());
    const recibidoBs = getNumericValueBs($("#montoRecibidoBs").val());

    let metodoPagoDesc = "";
    if (recibidoUsd > 0 && recibidoBs > 0) {
      metodoPagoDesc = "Pago Mixto";
    } else if (recibidoUsd > 0) {
      metodoPagoDesc = "Pago en Dólares";
    } else if (recibidoBs > 0) {
      metodoPagoDesc = "Pago en Bolívares";
    } else {
      const diferencia =
        recibidoUsd +
        (TASA_BCV > 0 ? recibidoBs / TASA_BCV : 0) -
        granTotalUsdModal;
      if (Math.abs(diferencia) < 0.01) {
        metodoPagoDesc = "Pago Exacto";
      } else {
        metodoPagoDesc = "Venta a Crédito";
      }
    }
    $("#listaMetodoPago").val(metodoPagoDesc);

    if ($("#seleccionarCliente").val() === "") {
      $("#modalConfirmacionPago").modal("hide");
      swal({
        title: "Cliente no seleccionado",
        text: "Por favor, selecciona un cliente para continuar con la venta.",
        type: "warning",
        confirmButtonText: "Entendido",
      }).then((result) => {
        if (result.value) {
          $("#modalConfirmacionPago").modal("show");
        }
      });
      return;
    }

    $("#formularioVentas").submit();
  });
});
