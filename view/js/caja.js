jQuery(function($) {
  "use strict";

  //======================================================================
  // == 0. CONFIGURACIÓN INICIAL Y VARIABLES GLOBALES
  //======================================================================

  const configDiv = $("#config-vars");
  const TASA_BCV = parseFloat(configDiv.data("tasa-bcv")) || 0;
  const IVA_PORCENTAJE = parseFloat(configDiv.data("iva-porcentaje")) || 16;

  let listaProductosOculta = [];
  let granTotalUsdModal = 0;
  let searchTimer; // Temporizador para optimizar la búsqueda (debounce)

  // --- Validación Crítica de Configuración ---
  if (TASA_BCV === 0) {
      Swal.fire({
          title: "Error de Configuración Crítico",
          text: "La tasa de cambio (BCV) no está disponible o es cero. El sistema no puede operar. Contacte al administrador.",
          icon: "error",
          confirmButtonText: "Entendido",
          allowOutsideClick: false,
      });
      // Deshabilitar toda la interfaz para prevenir operaciones
      $(".pos-container").find("button, .product-card, input, select").prop("disabled", true).css({ cursor: "not-allowed", opacity: 0.5 });
      return; // Detiene la ejecución del script por completo.
  }

  //======================================================================
  // == 1. LÓGICA DE CARGA Y BÚSQUEDA DE PRODUCTOS
  //======================================================================

  /**
   * Dibuja las tarjetas de producto en la cuadrícula.
   * @param {Array} productos - El array de objetos de producto a mostrar.
   */
  function renderizarProductos(productos) {
      const productGrid = $("#productosCategoria");
      productGrid.empty(); // Limpia la cuadrícula antes de añadir nuevos productos

      if (!productos || productos.length === 0) {
          productGrid.html('<div class="col-xs-12 alert alert-info text-center">No se encontraron productos que coincidan con los criterios.</div>');
          return;
      }

      $.each(productos, function(i, producto) {
          const precioFormateado = parseFloat(producto.pvp_referencia).toFixed(2);
          const imagenSrc = producto.imagen && producto.imagen !== "" ? producto.imagen : "vistas/img/productos/default/anonymous.png";
          const productCardHtml = `
              <div class="product-card agregarProducto" idProducto="${producto.id_producto}" style="cursor: pointer;">
                  <img src="${imagenSrc}" alt="${producto.descripcion}" onerror="this.onerror=null;this.src='vistas/img/productos/default/anonymous.png';">
                  <div class="product-info">
                      <h3 class="product-name">${producto.descripcion}</h3>
                      <div class="product-meta">
                          <span class="meta-item">${producto.categoria || "Sin Cat."}</span>
                          <span class="meta-item">Stock: ${producto.stock}</span>
                      </div>
                  </div>
                  <div class="product-price">$${precioFormateado}</div>
              </div>`;
          productGrid.append(productCardHtml);
      });
  }

  /**
   * Muestra un estado de carga en la cuadrícula de productos.
   */
  function mostrarCargadorProductos() {
      $("#productosCategoria").html('<div class="col-xs-12 text-center" style="padding:40px;"><i class="fa fa-spinner fa-spin fa-3x"></i><p>Cargando...</p></div>');
  }

  /**
   * Realiza una llamada AJAX para obtener productos y los renderiza.
   * @param {Object} data - Los datos a enviar en la petición AJAX (e.g., {idCategoriaFiltro: 'todos'}).
   */
  function obtenerYRenderizarProductos(data) {
      mostrarCargadorProductos();
      $.ajax({
          url: "ajax/productos.ajax.php",
          method: "POST",
          data: data,
          dataType: "json",
      })
      .done(function(productos) {
          renderizarProductos(productos);
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
          console.error("Error en AJAX:", textStatus, errorThrown, jqXHR.responseText);
          $("#productosCategoria").html('<div class="col-xs-12 alert alert-danger">Error al cargar los productos. Por favor, recargue la página.</div>');
      });
  }

  // --- Manejadores de Eventos ---

  // Al hacer clic en un botón de categoría
  $(".category-tabs").on("click", ".btn-categoria", function() {
      $(".btn-categoria").removeClass("active");
      $(this).addClass("active");
      $("#buscadorProductos").val(""); // Limpia el buscador para evitar confusión
      obtenerYRenderizarProductos({ idCategoriaFiltro: $(this).data("id-categoria") });
  });

  // Al escribir en el campo de búsqueda
  $("#buscadorProductos").on("keyup", function() {
      clearTimeout(searchTimer);
      const termino = $(this).val();

      if (termino.length > 0) {
          $(".btn-categoria").removeClass("active"); // Deselecciona categorías al buscar
      }

      // Espera 300ms después de la última pulsación para ejecutar la búsqueda
      searchTimer = setTimeout(function() {
          if (termino.length >= 2) {
              obtenerYRenderizarProductos({ terminoBusqueda: termino });
          } else if (termino.length === 0) {
              // Si el buscador se vacía, vuelve a la vista de "Todos"
              $(".btn-categoria[data-id-categoria='todos']").click();
          }
      }, 300);
  });

  // Carga inicial de todos los productos
  obtenerYRenderizarProductos({ idCategoriaFiltro: "todos" });

  //======================================================================
  // == 2. LÓGICA DEL CARRITO DE COMPRAS (AÑADIR, MODIFICAR, QUITAR)
  //======================================================================

  // El resto de tu código para el carrito, totales, clientes y modales
  // se mantiene estructuralmente igual, ya que su lógica es sólida.
  // Solo se han añadido las mejoras de manejo de errores en AJAX donde aplica.

  // Delegación de eventos para agregar productos desde la cuadrícula
  $(".product-grid").on("click", ".agregarProducto", function() {
      const idProducto = $(this).attr("idProducto");

      // Si el producto ya está en el carrito, simplemente aumenta su cantidad
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
          success: function(respuesta) {
              if (parseInt(respuesta.stock) <= 0) {
                  Swal.fire("Sin Stock", "No hay unidades disponibles de este producto.", "error");
                  return;
              }
              const itemHtml = `
                  <div class="order-item" idProducto="${respuesta.id_producto}" data-id-impuesto="${respuesta.id_impuesto}">
                      <img src="${respuesta.imagen}" alt="${respuesta.descripcion}">
                      <div class="item-details">
                          <div class="item-name">${respuesta.descripcion}</div>
                          <div class="quantity-controls">
                              <button type="button" class="btn-quitar-uno">-</button>
                              <input type="number" class="cantidad-producto" value="1" min="1" stock="${respuesta.stock}">
                              <button type="button" class="btn-agregar-uno">+</button>
                          </div>
                      </div>
                      <div class="item-price" pvp_referencia="${respuesta.pvp_referencia}">${parseFloat(respuesta.pvp_referencia).toFixed(2)}</div>
                      <button type="button" class="btn-remover-item quitarProducto"><i class="fa fa-times"></i></button>
                  </div>`;
              $(".order-items").append(itemHtml);
              actualizarTotales();
          },
          error: function() {
              Swal.fire("Error", "No se pudo obtener la información del producto.", "error");
          }
      });
  });

  // Delegación de eventos para los controles del carrito
  $(".order-items")
      .on("click", ".btn-agregar-uno", function() {
          const input = $(this).siblings(".cantidad-producto");
          const stock = parseInt(input.attr("stock"));
          let nuevaCantidad = parseInt(input.val()) + 1;
          if (nuevaCantidad <= stock) {
              input.val(nuevaCantidad).trigger("change");
          } else {
              Swal.fire("Stock máximo", "No hay más unidades disponibles.", "warning");
          }
      })
      .on("click", ".btn-quitar-uno", function() {
          const input = $(this).siblings(".cantidad-producto");
          let nuevaCantidad = parseInt(input.val()) - 1;
          if (nuevaCantidad >= 1) {
              input.val(nuevaCantidad).trigger("change");
          }
      })
      .on("change keyup", ".cantidad-producto", function() {
          let cantidad = parseInt($(this).val());
          const stock = parseInt($(this).attr("stock"));
          if (isNaN(cantidad) || cantidad < 1) {
              $(this).val(1);
          } else if (cantidad > stock) {
              Swal.fire("Stock máximo", `Solo hay ${stock} unidades disponibles.`, "error");
              $(this).val(stock);
          }
          actualizarTotales();
      })
      .on("click", ".quitarProducto", function() {
          $(this).closest(".order-item").remove();
          actualizarTotales();
      });

  //======================================================================
  // == 3. CÁLCULO DE TOTALES Y ACTUALIZACIÓN DE DATOS
  //======================================================================
  function actualizarTotales() {
      let subtotalUsd = 0;
      $(".order-item").each(function() {
          const cantidad = parseInt($(this).find(".cantidad-producto").val()) || 1;
          const precioUnitario = parseFloat($(this).find(".item-price").attr("pvp_referencia"));
          subtotalUsd += cantidad * precioUnitario;
      });

      const montoIvaUsd = subtotalUsd * (IVA_PORCENTAJE / 100);
      const granTotalUsd = subtotalUsd + montoIvaUsd;
      const subtotalBs = subtotalUsd * TASA_BCV;
      const ivaBs = montoIvaUsd * TASA_BCV;
      const granTotalBs = granTotalUsd * TASA_BCV;
      const formatoVeLocale = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

      // Actualizar UI
      $("#subtotalUsdDisplay").text("$" + subtotalUsd.toFixed(2));
      $("#subtotalBsDisplay").text("(" + subtotalBs.toLocaleString("es-VE", formatoVeLocale) + " Bs)");
      $("#ivaUsdDisplay").text("$" + montoIvaUsd.toFixed(2));
      $("#ivaBsDisplay").text("(" + ivaBs.toLocaleString("es-VE", formatoVeLocale) + " Bs)");
      $("#displayTotalBs").text(granTotalBs.toLocaleString("es-VE", formatoVeLocale) + " Bs");
      $("#displayTotalVenta").text("$" + granTotalUsd.toFixed(2));

      // Actualizar inputs ocultos del formulario
      $("#tasaDelDia").val(TASA_BCV);
      $("#subtotalUsd").val(subtotalUsd.toFixed(2));
      $("#subtotalBs").val(subtotalBs.toFixed(2));
      $("#ivaUsd").val(montoIvaUsd.toFixed(2));
      $("#ivaBs").val(ivaBs.toFixed(2));
      $("#totalUsd").val(granTotalUsd.toFixed(2));
      $("#totalBs").val(granTotalBs.toFixed(2));

      listarProductosParaBackend();
  }

  function listarProductosParaBackend() {
      listaProductosOculta = [];
      $(".order-item").each(function() {
          const item = $(this);
          listaProductosOculta.push({
              id: item.attr("idProducto"),
              id_impuesto: item.data("id-impuesto"),
              descripcion: item.find(".item-name").text().trim(),
              cantidad: item.find(".cantidad-producto").val(),
              stock: item.find(".cantidad-producto").attr("stock"),
              pvp_referencia: item.find(".item-price").attr("pvp_referencia"),
              total: (parseFloat(item.find(".cantidad-producto").val()) * parseFloat(item.find(".item-price").attr("pvp_referencia"))).toFixed(2),
          });
      });
      $("#listaProductosCaja").val(JSON.stringify(listaProductosOculta));
  }

  //======================================================================
  // == 4. BUSCADOR DE CLIENTES POR CÉDULA
  //======================================================================
  $("#buscadorClienteCedula").on("blur", function() {
      const cedula = $(this).val().trim();
      if (cedula === "") {
          $("#seleccionarCliente").val("");
          $("#nombreClienteDisplay").val("");
          return;
      }

      const datos = new FormData();
      datos.append("buscarCedula", cedula);

      $.ajax({
          url: "ajax/clients.ajax.php",
          method: "POST",
          data: datos,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "json",
      })
      .done(function(respuesta) {
          if (respuesta) {
              $("#nombreClienteDisplay").val(respuesta.nombre);
              $("#seleccionarCliente").val(respuesta.id);
          } else {
              $("#seleccionarCliente").val("");
              $("#nombreClienteDisplay").val("Cliente no encontrado. Registre uno nuevo.");
              $("#modalAgregarCliente").modal("show");
              $('#modalAgregarCliente').find('input[name="nuevoDocumento"]').val(cedula);
          }
      })
      .fail(function(jqXHR, textStatus, errorThrown) {
          console.error("Error buscando cliente:", textStatus, errorThrown);
          Swal.fire("Error de Conexión", "No se pudo comunicar con el servidor para buscar el cliente.", "error");
      });
  });

  //======================================================================
  // == 5. FLUJO DE PAGO Y MODALES
  //======================================================================
  $("#modalMetodoPago").on("show.bs.modal", function(e) {
      if ($(".order-items .order-item").length === 0) {
          Swal.fire("Carrito vacío", "¡Debes agregar productos antes de proceder al pago!", "error");
          return e.preventDefault();
      }
      const granTotalUsd = parseFloat($("#totalUsd").val()) || 0;
      const granTotalBs = granTotalUsd * TASA_BCV;
      const formatoVeLocale = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
      $("#totalPagarUsd").text("$" + granTotalUsd.toFixed(2));
      $("#totalPagarBs").text(granTotalBs.toLocaleString("es-VE", formatoVeLocale) + " Bs");
  });

  $("#modalMetodoPago").on("click", ".payment-option", function() {
      const metodoSeleccionado = $(this).data("value");
      $("#modalConfirmacionPago").data("metodo", metodoSeleccionado);
      $("#modalMetodoPago").modal("hide");
      $("#modalConfirmacionPago").modal("show");
  });

  $("#modalConfirmacionPago").on("show.bs.modal", function() {
      granTotalUsdModal = parseFloat($("#totalUsd").val()) || 0;
      const granTotalBsModal = granTotalUsdModal * TASA_BCV;
      const formatoVeLocale = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

      $("#totalPagarModalUsd").text("$" + granTotalUsdModal.toFixed(2));
      $("#totalPagarModalBs").text(granTotalBsModal.toLocaleString("es-VE", formatoVeLocale) + " Bs");

      // Resetear campos y vistas
      $("#montoRecibidoUsd, #montoRecibidoBs").val("");
      $(".conversion-display").removeClass("visible").val("");
      $(".vuelto-container").hide();
      $("#alertaMontoInsuficiente").hide();
      $("#btnConfirmarYCrearVenta").prop("disabled", true);

      // Lógica para autocompletar el monto
      const metodo = $(this).data("metodo");
      if (metodo === "Pago-Movil" || metodo === "Punto-Venta" || metodo === "Transferencia" || metodo === "Efectivo-BS") {
          const totalBsFormatted = granTotalBsModal.toLocaleString("es-VE", formatoVeLocale);
          $("#montoRecibidoBs").val(totalBsFormatted);
      } else if (metodo === "Efectivo-USD" || metodo === "Zelle") {
          $("#montoRecibidoUsd").val(granTotalUsdModal.toFixed(2));
      }
      
      calcularPagosYVuelto();
      setTimeout(() => {
          if ($("#montoRecibidoBs").val() !== "") {
              $("#montoRecibidoBs").focus();
          } else {
              $("#montoRecibidoUsd").focus();
          }
      }, 500);
  });

  $("#montoRecibidoUsd, #montoRecibidoBs").on("input keyup", calcularPagosYVuelto);

  $("#montoRecibidoUsd").on("keyup", function() {
    const metodo = $("#modalConfirmacionPago").data("metodo");
    const metodosBs = ["Pago-Movil", "Punto-Venta", "Transferencia", "Efectivo-BS"];

    if (metodosBs.includes(metodo)) {
        const recibidoUsd = parseFloat($(this).val().replace(/[^0-9.]/g, "")) || 0;
        
        let restanteEnUsd = granTotalUsdModal - recibidoUsd;
        if (restanteEnUsd < 0) {
            restanteEnUsd = 0;
        }

        const restanteEnBs = restanteEnUsd * TASA_BCV;
        const formatoVeLocale = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
        
        $("#montoRecibidoBs").val(restanteEnBs.toLocaleString("es-VE", formatoVeLocale));
    }
  });

  $("#montoRecibidoBs").on("keyup", function() {
    const metodo = $("#modalConfirmacionPago").data("metodo");

    if (metodo === "Efectivo-USD") {
        const recibidoBs = parseFloat($(this).val().replace(/\./g, "").replace(",", ".")) || 0;
        const recibidoBsEnUsd = recibidoBs / TASA_BCV;
        
        let restanteEnUsd = granTotalUsdModal - recibidoBsEnUsd;
        if (restanteEnUsd < 0) {
            restanteEnUsd = 0;
        }
        
        $("#montoRecibidoUsd").val(restanteEnUsd.toFixed(2));
    }
  });

  function calcularPagosYVuelto() {
      const recibidoUsd = parseFloat($("#montoRecibidoUsd").val().replace(/[^0-9.]/g, "")) || 0;
      const recibidoBs = parseFloat($("#montoRecibidoBs").val().replace(/\./g, "").replace(",", ".")) || 0;
      const formatoVeLocale = { minimumFractionDigits: 2, maximumFractionDigits: 2 };

      // Mostrar conversiones en tiempo real
      if (recibidoUsd > 0) {
          $("#conversionDisplayBs").val(`≈ ${(recibidoUsd * TASA_BCV).toLocaleString("es-VE", formatoVeLocale)} Bs`).addClass("visible");
      } else { $("#conversionDisplayBs").removeClass("visible"); }
      if (recibidoBs > 0) {
          $("#conversionDisplayUsd").val(`≈ $${(recibidoBs / TASA_BCV).toFixed(2)}`).addClass("visible");
      } else { $("#conversionDisplayUsd").removeClass("visible"); }

      const totalRecibidoEnUsd = recibidoUsd + (recibidoBs / TASA_BCV);
      const diferencia = totalRecibidoEnUsd - granTotalUsdModal;

      // Comprobación con margen de error para decimales
      if (diferencia >= -0.009) {
          const vueltoEnUsd = diferencia > 0 ? diferencia : 0;
          $("#alertaMontoInsuficiente").hide();
          $(".vuelto-container").show();
          $("#vueltoDisplayUsd").text("$" + vueltoEnUsd.toFixed(2));
          $("#vueltoDisplayBs").text((vueltoEnUsd * TASA_BCV).toLocaleString("es-VE", formatoVeLocale) + " Bs");
          $("#btnConfirmarYCrearVenta").prop("disabled", false);
      } else {
          $("#alertaMontoInsuficiente").show();
          $(".vuelto-container").hide();
          $("#btnConfirmarYCrearVenta").prop("disabled", true);
      }
  }

  //======================================================================
  // == 6. ACCIONES FINALES DEL FORMULARIO DE VENTA
  //======================================================================
  $("#btnVolverMetodos").on("click", function() {
      $("#modalConfirmacionPago").modal("hide");
      $("#modalMetodoPago").modal("show");
  });

  $("#btnConfirmarYCrearVenta").on("click", function() {
      const boton = $(this);
      
      // 1. Validar que haya un cliente seleccionado
      if ($("#seleccionarCliente").val() === "") {
          $("#modalConfirmacionPago").modal("hide"); // Oculta temporalmente para mostrar la alerta
          Swal.fire({
              title: "Cliente no seleccionado",
              text: "Por favor, busca y selecciona un cliente para continuar.",
              icon: "warning",
              confirmButtonText: "Entendido",
          }).then(() => {
              $("#modalConfirmacionPago").modal('show'); // Vuelve a mostrar el modal
          });
          return;
      }

      // 2. Deshabilitar botón para prevenir doble envío
      boton.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> CREANDO VENTA...');

      // 3. Usar el método de pago específico seleccionado
      const metodoPagoSeleccionado = $("#modalConfirmacionPago").data("metodo");
      $("#listaMetodoPago").val(metodoPagoSeleccionado);

      // 4. Enviar el formulario
      $("#formularioVentas").submit();
  });

});