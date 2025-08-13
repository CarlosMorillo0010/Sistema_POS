$(".tablaLibro").DataTable({
  order: [[0, "desc"]],
  responsive: true,      // Soporte para dispositivos móviles
  scrollX: true,         // Scroll horizontal
  dom:  "<'row mb-2'<'col-sm-12'B>>" +
        "<'row mb-2'<'col-sm-6'l><'col-sm-6'f>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row mt-2'<'col-sm-7 align-self-center text-start'i><'col-sm-5 d-flex justify-content-end'p>>",
  buttons: [
    {
      extend: 'excelHtml5',
      text: '<i class="fa fa-file-excel"></i>',
      titleAttr: 'Exportar a Excel',
      className: 'btn btn-success'
    },
    {
      extend: 'print',
      text: '<i class="fa fa-print"></i>',
      titleAttr: 'Imprimir',
      className: 'btn btn-primary'
    },
  ],
   language: {
    sProcessing: "Procesando...",
    sLengthMenu: "Mostrar _MENU_ registros",
    sZeroRecords: "No se encontraron resultados",
    sEmptyTable: "Ningún dato disponible en esta tabla",
    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0",
    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
    sInfoPostFix: "",
    sSearch: "Buscar:",
    sUrl: "",
    sInfoThousands: ",",
    sLoadingRecords: "Cargando...",
    oPaginate: {
      sFirst: "Primero",
      sLast: "Último",
      sNext: "Siguiente",
      sPrevious: "Anterior",
    },
    oAria: {
      sSortAscending: ": Activar para ordenar la columna de manera ascendente",
      sSortDescending:
        ": Activar para ordenar la columna de manera descendente",
    },
  },
});