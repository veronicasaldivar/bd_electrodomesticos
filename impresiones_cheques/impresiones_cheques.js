$(function () {
  var Path = 'imp_impresiones_cheques.php';
  var tabla = $('#reclamos').dataTable({
    "columns":
      [
        {
          "class": "details-control",
          "orderable": false,
          "data": null,
          "defaultContent": "<a><span class='fa fa-plus'></span></a>"
        },
        { "data": "entcod" },
        { "data": "cuenta" },
        { "data": "movnro" },
        { "data": "cheque_nro" },
        { "data": "cheque_monto" },
        { "data": "fecha" },
        { "data": "estado" },
        { "data": "librador" },
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
    var entidad = d.entcod.split(' - ')[0];
    var cuenta = d.cuenta.split(' - ')[0];
    // `d` is the original data object for the row
    var deta = '<table  class="table table-striped table-bordered nowrap table-hover">\n\
    <tr width=80px class="info"><th></th><th></th><th></th><th></th><th> </th><th></th></tr>';

    return deta + '<tfoot><tr><th colspan="5" class="text-center" ></th></tr></tfoot></table>\n\
      <div class="row">'+
      '<div class="col-md-2">' +
      '<div class="col-md-12 pull-center">' +
      '<a href="../informes/' + Path + '?id=' + entidad + '_' + cuenta + '_' + d.movnro + '" target="_blank" class="btn btn-sm btn-primary btn-block" id="print" ><span class="fa fa-print"></span><b> Imprimir</b></a>' +
      '</div>' +
      '</div>' +
      '</div>';
  }
  //FIN TABLA

  // EDITAR LIBRADOR
  $(document).on("click", ".editar", function () {
    var pos = $(".editar").index(this);
    var entidad, cuenta, movnro, cheque, monto, librador;
    $("#reclamos tbody tr:eq(" + pos + ")").children("td").each(function (col) {
      if (col === 1) {
        entidad = $(this).html();
        $("#entidades").val(entidad);
      }
      if (col === 2) {
        cuenta = $(this).html();
        $("#cuentas_corrientes").val(cuenta);
      }
      if (col === 3) {
        movnro = $(this).html();
        $("#movimiento").val(movnro);
      }
      if (col === 4) {
        cheque = $(this).html();
        $("#cheque_num").val(cheque);
      }
      if (col === 5) {
        monto = $(this).html();
        $("#monto").val(monto);
      }
      if (col === 8) {
        librador = $(this).html();
        $("#librador").val(librador);
      }
    });
  });
  $(document).on("click", "#btnsave", function () {
    var entidad = $("#entidades").val();
    entidad = entidad.split(' - ')[0];
    var cuenta = $("#cuentas_corrientes").val();
    cuenta = cuenta.split(' - ')[0];
    var movnro = $("#movimiento").val();
    var librador = $("#librador").val();
    if (entidad !== "" && cuenta !== "" && movnro !== "" && librador !== "") {
      $.ajax({
        type: 'POST',
        url: 'grabar.php',
        data: { entidad: entidad, cuenta: cuenta, movnro: movnro, librador: librador }
      }).done(function (msg) {
        mostrarMensaje(msg)
        refrescarDatos();
        vaciar();
      });
    }
  });
  // FIN EDITAR LIBRADOR

  //ENTREGAR
  $(document).on("click", ".entregar", function () {
    var pos = $(".entregar").index(this);
    var entidad, cuenta, movnro;
    $("#reclamos tbody tr:eq(" + pos + ")").children("td").each(function (col) {
      if (col === 1) {
        entidad = $(this).html();
        entidad = entidad.split(' - ')[0];
      }
      if (col === 2) {
        cuenta = $(this).html();
        cuenta = cuenta.split(' - ')[0];
      }
      if (col === 3) {
        movnro = $(this).html();
      }
    });

    $("#cod_entidad").val(entidad);
    $("#cod_cuenta").val(cuenta);
    $("#cod_movnro").val(movnro);
  });
  $(document).on("click", "#entregar", function () {
    var entidad = $("#cod_entidad").val();
    var cuenta = $("#cod_cuenta").val();
    var movnro = $("#cod_movnro").val();
    var cedula = $("#cedula").val();
    var nombres = $("#nombres").val();
    var obs = $("#obs").val();
    var suc = $("#sucursal").val();
    var usu = $("#usuario").val();
    $.ajax({
      type: 'POST',
      url: 'entregar.php',
      data: { codigo: 0, entidad: entidad, cuenta: cuenta, movnro: movnro, cedula: cedula, nombres: nombres, obs: obs, suc: suc, usu: usu, ope: 1 }
    }).done(function (msg) {
      $("#cerrarEntrega").click();
      mostrarMensaje(msg)
      refrescarDatos();

      $("#cod_entidad").val('');
      $("#cod_cuenta").val('');
      $("#cod_movnro").val('');
      $("#cedula").val('');
      $("#nombres").val('');
      $("#obs").val('');
    });
  });
  // FIN ENTREGAR

  // FUNCIONES
  function vaciar() {
    $("#entidades").val(0).trigger('change');
    $("#cuentas_corrientes").val(0).trigger('change');
    $("#cajas").val(0).trigger('change');
    $("#monto").val('');
    $("#forma_asignacion").val(0).trigger('change');
    $("#cheque_num").val(0);
    $("#fun_responsable").val(0).trigger('change');
    $("#obs").val('');
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



