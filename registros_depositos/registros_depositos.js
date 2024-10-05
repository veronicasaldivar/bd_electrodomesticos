$(function () {
  var Path = 'imp_depositos_bancarios.php';
  var tabla = $('#reclamos').dataTable({
    "columns":
      [
        {
          "class": "details-control",
          "orderable": false,
          "data": null,
          "defaultContent": "<a><span class='fa fa-plus'></span></a>"
        },
        { "data": "codigo" },
        { "data": "fecha" },
        { "data": "ent" },
        { "data": "cuenta" },
        { "data": "monto" },
        { "data": "suc" },
        { "data": "usuario" },
        { "data": "estado" },
        { "data": "acciones" }
      ]
  });

  tabla.fnReloadAjax('datos.php');
  function refrescarDatos() {
    tabla.fnReloadAjax();
  }
  tabla.fnReloadAjax('datos.php');

  var detailRows = [];

  $('#reclamos tbody').on('click', 'tr td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = $('#reclamos').DataTable().row(tr);
    var idx = $.inArray(tr.attr('id'), detailRows);

    if (row.child.isShown()) {
      tr.removeClass('details');
      row.child.hide();
      $(this).html("<a><span class='fa fa-plus'></span></a>");
      // Remove from the 'open' array
      detailRows.splice(idx, 1);
    }
    else {

      tr.addClass('details');
      row.child(format(row.data())).show();
      if (idx === -1) {
        detailRows.push(tr.attr('id'));
      }
      $(this).html("<a><span class='fa fa-minus'></span></a>");
      // Add to the 'open' array
    }
  });

  // On each draw, loop over the `detailRows` array and show any child rows
  tabla.on('draw', function () {
    $.each(detailRows, function (i, cod) {
      $('#' + cod + ' td.details-control').trigger('click');
    });
  });

  function format(d) {
    // `d` is the original data object for the row
    var deta = '<table  class="table table-striped table-bordered nowrap table-hover">\n\
    <tr width=80px class="info"><th></th><th></th><th></th><th></th><th> </th><th></th></tr>';

    return deta + '<tfoot><tr><th colspan="5" class="text-center" ></th></tr></tfoot></table>\n\
      <div class="row">'+
      '<div class="col-md-2">' +
      '<div class="col-md-12 pull-center">' +
      '<a href="../informes/' + Path + '?id=' + d.codigo + '" target="_blank" class="btn btn-sm btn-primary btn-block" id="print" ><span class="fa fa-print"></span><b> Imprimir</b></a>' +
      '</div>' +
      '</div>' +
      '</div>';
  }
  //FIN TABLA

  //AGREGAR
  $(document).on("click", "#btnsave", function () {
    var aperturas_cierres = $("#aperturas_cierres").val();
    var recaudaciones_depositar = $("#recaudaciones_depositar").val();
    var entidades = $("#entidades").val();
    var cuentas_corrientes = $("#cuentas_corrientes").val();
    var monto_depositar = $("#monto_depositar").val();
    var suc = $("#sucursal").val();
    var usu = $("#usuario").val();
   
    if (aperturas_cierres > 0  && recaudaciones_depositar > 0 && entidades > 0 && cuentas_corrientes > 0 && monto_depositar > 0 && suc > 0 && usu > 0) {
      $.ajax({
        type: "POST",
        url: "grabar.php",
        data: { entidades: entidades, cuentas_corrientes: cuentas_corrientes, movimiento_nro: 0, recaudaciones_depositar: recaudaciones_depositar, aperturas_cierres: aperturas_cierres, monto_depositar: monto_depositar, suc: suc, usu: usu, ope: 1 }
        //-- ORDEN: entcod, cuencorrcod, movnro, reccod, apercod, monto, usu, suc,  oper
      }).done(function (msg) {
        mostrarMensaje(msg);
        vaciar();
        refrescarDatos();
      });
    } else {
      humane.log("<span class='fa fa-info'></span> complete los campos por favor", { timeout: 4000, clickToClose: true, addnCls: 'humane-flatty-warning' });

    }
  });
  //FIN AGREGAR

  //ELIMINAR
  $(document).on("click", ".eliminar", function () {
    var pos = $(".eliminar").index(this);
    var cod;
    $("#reclamos tbody tr:eq(" + pos + ")").find("td:eq(1)").each(function () {
      cod = $(this).html();
    });
    $("#delete").val(cod);
    $(".msg").html('<h4 class="modal-title" id="myModalLabel">Desea eliminar el Registro Nro. ' + cod + ' ?</h4>');
  });
  $(document).on("click", "#delete", function () {
    var codigo = $("#delete").val();
    $.ajax({
      type: 'POST',
      url: 'grabar.php',
      data: { entidades: 0, cuentas_corrientes: 0, movimiento_nro: codigo, recaudaciones_depositar: 0, aperturas_cierres: 0, monto_depositar: 0, suc: 0, usu: 0, ope: 2}
      //-- ORDEN: entcod, cuencorrcod, movnro, reccod, apercod, monto, usu, suc,  oper
    }).done(function (msg) {
      $("#cerrar2").click();
      mostrarMensaje(msg)
      refrescarDatos();
    });
  });
  // FIN ELIMINAR

  // FUNCIONES
  $("#aperturas_cierres").change(function () {
    var apercod = $("#aperturas_cierres").val();
    if (apercod !== "0") {
      $.ajax({
        type: "POST",
        url: "aperturas_cierres.php",
        data: { apercod: apercod },
      }).done(function (data) {
        if(data !== 'error'){
          var datos = JSON.parse(data);
          $("#recaudaciones_depositar").val(datos.recau_dep_cod);
          $("#monto_efectivo").val(datos.monto_efectivo);
          $("#monto_cheque").val(datos.monto_cheque);
          $("#monto_depositar").val(datos.monto_efectivo_cheque);
        }
      });
    } else {
      $("#recaudaciones_depositar").val('');
      $("#monto_efectivo").val('');
      $("#monto_cheque").val('');
      $("#monto_depositar").val('');
    }
  });

  $("#entidades").change(function () {
    var entcod = $("#entidades").val();
    if (entcod > 0) {
      $.ajax({
        type: "POST",
        url: "cuentas_corrientes.php",
        data: { entcod: entcod },
      }).done(function (data) {
        $("#cuentas_corrientes > option").remove();
        $("#cuentas_corrientes").append(data);
        $("#cuentas_corrientes").selectpicker("refresh");
      });
    } else {
      $("#cuentas_corrientes > option").remove();
      $("#cuentas_corrientes").append(`<option value='0'>Elija una opcion</option>`);
      $("#cuentas_corrientes").selectpicker("refresh");
    }
  });

  $("#forma_asignacion").change(function () {
    var formaasig = $("#forma_asignacion").val();
    if (formaasig === 'CHEQUE') {
      $("#cheque_num").prop('disabled', false);
    } else {
      $("#cheque_num").val('0');
      $("#cheque_num").prop('disabled', true);
    }
  });

  function vaciar() {
    $("#aperturas_cierres").val(0).trigger('change');
    $("#recaudaciones_depositar").val('');
    $("#monto_efectivo").val('');
    $("#monto_cheque").val('');
    $("#monto_depositar").val('');
    $("#entidades").val(0).trigger('change');
    $("#cuenta_corrientes").val(0).trigger('change');
  }

  function mostrarMensaje(msg) {
    var r = msg.split("_/_");
    var texto = r[0];
    var tipo = r[1];

    if (tipo.trim() == 'notice') {
      texto = texto.split("NOTICE:");
      texto = texto[1];

      humane.log("<span class='fa fa-check'></span>" + texto, { timeout: 4000, clickToClose: true, addnCls: 'humane-flatty-success' });
    }
    if (tipo.trim() == 'error') {
      texto = texto.split("ERROR:");
      texto = texto[2];

      texto = texto.split('CONTEXT:');
      texto = texto[0];

      humane.log("<span class='fa fa-info'></span>" + texto, { timeout: 4000, clickToClose: true, addnCls: 'humane-flatty-error' });
    }
  }
});



