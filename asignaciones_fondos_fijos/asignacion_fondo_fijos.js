$(function () {
  var Path = 'imp_asignacion_fondo_fijo.php';
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
        { "data": "res" },
        { "data": "caja" },
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
    var oper = parseInt($("#operacion").val());
    if (oper === 1) {
      var entidades = $("#entidades").val();
      var cuentas_corrientes = $("#cuentas_corrientes").val();
      var cajas = $("#cajas").val();
      var monto = $("#monto").val();
      var obs = $("#obs").val();
      var forma_asignacion = $("#forma_asignacion").val();
      var cheque_num = $("#cheque_num").val();
      var fun_res = $("#fun_responsable").val();
      var suc = $("#sucursal").val();
      var usu = $("#usuario").val();

      if (entidades > 0 && cuentas_corrientes > 0 && cajas > 0 && monto > 0 && forma_asignacion !== "" && cheque_num >= 0 && suc > 0 && usu > 0) {
        $.ajax({
          type: "POST",
          url: "grabar.php",
          data: { codigo: 0, entidades: entidades, cuentas_corrientes: cuentas_corrientes, cajas: cajas, monto: monto, obs: obs, suc: suc, usu: usu, fun_res: fun_res, forma_asignacion: forma_asignacion, cheque_num: cheque_num, ope: 1 }
          //-- orden: codigo, entcod, cuencorrcod, cajacod, monto, obs, usu, suc, funresponsable, tipoasignacion, chequenum, oper
        }).done(function (msg) {
          mostrarMensaje(msg);
          vaciar();
          refrescarDatos();
          ultcod();
        });
      } else {
        humane.log("<span class='fa fa-info'></span> complete los campos por favor", { timeout: 4000, clickToClose: true, addnCls: 'humane-flatty-warning' });
      }
    }
  });
  //FIN AGREGAR

  // EDITAR
  $(document).on("click", ".editar", function () {
    $("#operacion").val(2)
    var pos = $(".editar").index(this);
    var cod;
    $("#reclamos tbody tr:eq(" + pos + ")").find("td:eq(1)").each(function () {
      cod = $(this).html();
    });
    if (cod > 0) {
      $.ajax({
        type: "POST",
        url: "asignaciones.php",
        data: { cod: cod }
      }).done(function (data) {
        var datos = JSON.parse(data)
        $("#nro").val(datos.asignacion_responsable_cod);
        $("#cajas").val(datos.caja_cod).trigger('change');
        $("#cajas").prop('disabled', true);
        $("#monto").val(datos.monto_asignado);
        $("#fun_responsable").val(datos.fun_res_cod).trigger('change');
        $("#fun_responsable").prop('disabled', true);
        $("#obs").val(datos.observacion);
      })
    }
  });

  $(document).on("click", "#btnsave", function () {
    var oper = parseInt($("#operacion").val());
    if (oper === 2) {
      var asignro = $("#nro").val();
      var entidades = $("#entidades").val();
      var cuentas_corrientes = $("#cuentas_corrientes").val();
      var cajas = $("#cajas").val();
      var monto = $("#monto").val();
      var obs = $("#obs").val();
      var forma_asignacion = $("#forma_asignacion").val();
      var cheque_num = $("#cheque_num").val();
      var fun_res = $("#fun_responsable").val();
      var suc = $("#sucursal").val();
      var usu = $("#usuario").val();

      $.ajax({
        type: 'POST',
        url: 'grabar.php',
        data: { codigo: asignro, entidades: entidades, cuentas_corrientes: cuentas_corrientes, cajas: cajas, monto: monto, obs: obs, suc: suc, usu: usu, fun_res: fun_res, forma_asignacion: forma_asignacion, cheque_num: cheque_num, ope: 2 }
      }).done(function (msg) {
        $("#cerrar2").click();
        mostrarMensaje(msg)
        refrescarDatos();
        vaciar();
        ultcod();
        $("#operacion").val(1);
      });
    }
  });
  // FIN EDITAR

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
      data: { codigo: codigo, entidades: 0, cuentas_corrientes: 0, cajas: 0, monto: 0, obs: '', suc: 0, usu: 0, fun_res: 0, forma_asignacion: '', cheque_num: 0, ope: 3 }
    }).done(function (msg) {
      $("#cerrar2").click();
      mostrarMensaje(msg)
      refrescarDatos();
    });
  });
  // FIN ELIMINAR

  // FUNCIONES
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

  // $("#forma_asignacion").change(function () {
  //   var formaasig = $("#forma_asignacion").val();
  //   if (formaasig === 'CHEQUE') {
  //     $("#cheque_num").prop('disabled', false);
  //   } else {
  //     $("#cheque_num").val('0');
  //     $("#cheque_num").prop('disabled', true);
  //   }
  // });

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

  $("#forma_asignacion").change(function () {
    var formaasig = $("#forma_asignacion").val();
    if (formaasig === 'CHEQUE') {
      var entcod = $("#entidades").val();
      var cuenta = $("#cuentas_corrientes").val();
      if (entcod > 0 && cuenta > 0) {
        $.ajax({
          type: "GET",
          url: "siguiente_cheque.php",
          data: { entcod, cuenta },
        }).done(function (data) {
          if (data !== 'error') {
            $("#cheque_num").val(data);
          } else {
            humane.log(
              "<span class='fa fa-info'></span>VERIFIQUE QUE EXISTA UNA CHEQUERA Y QUE ESTE ACTIVO",
              {
                timeout: 4000,
                clickToClose: true,
                addnCls: "humane-flatty-warning",
              }
            );
            $("#cheque_num").val('');
          }
        });
      }
    } else {
      $("#cheque_num").val('');
    }
  });


  function ultcod() {
    $.ajax({
      type: 'GET',
      url: 'ultcod.php',
    }).done(function (msg) {
      $("#nro").val(msg);
    })
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



