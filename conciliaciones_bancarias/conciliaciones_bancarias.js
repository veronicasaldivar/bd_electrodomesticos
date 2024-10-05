$(function () {
  var Path = "imp_otros_creditos_debitos.php";

  var tabla = $("#tabla").dataTable({
    columns: [
      {
        class: "details-control",
        orderable: false,
        data: null,
        defaultContent: "<a><span class='fa fa-plus'></span></a>",
      },
      { data: "cod" },
      { data: "fecha" },
      { data: "entidad" },
      { data: "cuenta" },
      { data: "monto" },
      { data: "estado" },
      { data: "acciones" },
    ],
  });

  tabla.fnReloadAjax("datos.php");

  var detailRows = [];

  $("#tabla tbody").on("click", "tr td.details-control", function () {
    var tr = $(this).closest("tr");
    var row = $("#tabla").DataTable().row(tr);
    var idx = $.inArray(tr.attr("id"), detailRows);

    if (row.child.isShown()) {
      tr.removeClass("details");
      row.child.hide();
      $(this).html("<a><span class='fa fa-plus'></span></a>");
      // Remove from the 'open' array
      detailRows.splice(idx, 1);
    } else {
      tr.addClass("details");
      row.child(format(row.data())).show();
      if (idx === -1) {
        detailRows.push(tr.attr("id"));
      }
      $(this).html("<a><span class='fa fa-minus'></span></a>");
      // Add to the 'open' array
    }
  });

  // On each draw, loop over the `detailRows` array and show any child rows
  tabla.on("draw", function () {
    $.each(detailRows, function (i, cod) {
      $("#" + cod + " td.details-control").trigger("click");
    });
  });

  /*
  function format(d) {
    // `d` is the original data object for the row
    var deta =
      '<table  class="table table-striped table-bordered nowrap table-hover">\n\
    <tr width=80px class="info"><th>Codigo</th><th>Descripcion</th><th>Monto</th><th>Subtotal</th></tr>';
    var total = 0;
    for (var x = 0; x < d.detalle.length; x++) {
      subtotal = d.detalle[x].precio;
      total += parseInt(subtotal);

      deta +=
        "<tr>" +
        "<td width=10px>" +
        d.detalle[x].codigo +
        "</td>" +
        "<td width=80px>" +
        d.detalle[x].descripcion +
        "</td>" +
        "<td width=50px>" +
        d.detalle[x].precio +
        "</td>" +
        "<td width=10px>" +
        subtotal +
        "</td>" +
        "</tr>";
    }
    deta +=
      "</tbody>" +
      "<tfoot>" +
      "<tr>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "</tr>" +
      "<tr>" +
      "<td>Total</td>" +
      "<td></td>" +
      "<td></td>" +
      "<td>" +
      total +
      " Gs.</td>" +
      "</tr>" +
      "</tfoot>" +
      "</table></center>";

    return (
      deta +
      '<tfoot><tr><th colspan="5" class="text-center" ></th></tr></tfoot></table>\n\
                <div class="row">' +
      '<div class="col-md-2">' +
      '<div class="col-md-12 pull-center">' +
      '<a href="../informes/' +
      Path +
      "?id=" +
      d.cod +
      '" target="_blank" class="btn btn-sm btn-primary btn-block" id="print" ><span class="fa fa-print"></span><b> Imprimir</b></a>' +
      "</div>" +
      "</div>"
    );
  }
  */

  // INSERTAR GRILLA 
  $(document).on("click", ".listar", function () {
    $("#detalle-grilla").css({ display: "block" });
    listar();
  });

  $(document).on("click", ".toogleConciliar", function () {
    var entidad, cuenta, movnro;
    var pos = $(".toogleConciliar").index(this);
    $("#grilladetalle tbody tr:eq(" + pos + ")")
      .children("td")
      .each(function (i) {
        if (i == 0) {
          entidad = $(this).text().split(' - ')[0].trim();
        }
        if (i == 1) {
          cuenta = $(this).text().split(' - ')[0].trim();
        }
        if (i == 2) {
          movnro = $(this).text();
        }
      });

    obj = { entidad, cuenta, movnro }

    console.log(obj)

    $.ajax({
      type: "POST",
      url: "grabar.php",
      data: { entidad, cuenta, movnro},
    }).done(function (msg) {
      mostrarMensajes(msg);
      listar();
    });
  });


  // LISTAR
  function listar() {
    var entCod = $("#entidades").val();
    var cuenCorr = $("#cuentas_corrientes").val();
    var tipoMov = $("#tipo_mov").val();
    var fechaDesde = $("#fecha_desde").val();
    var fechaHasta = $("#fecha_hasta").val();
    var estado = $("#estado").val();
    if (entCod > 0 && cuenCorr > 0 && tipoMov !== "" && fechaDesde !== "" && fechaHasta !== "" && estado !== "") {
      $.ajax({
        type: "POST",
        url: "movimientos_bancarios.php",
        data: {
          entcod: entCod,
          cuenta: cuenCorr,
          tipomov: tipoMov,
          fechadesde: fechaDesde,
          fechahasta: fechaHasta,
          estado: estado
        },
      }).done(function (data) {
        if (data !== "error") {
          var datos = JSON.parse(data)
          $("#grilladetalle > tbody > tr").remove();
          $("#grilladetalle > tbody:last").append(datos.filas);
          $("#total").html("<strong>" + datos.total + " Gs.</strong>");
        } else {
          humane.log(
            "<span class='fa fa-info'></span> NO HAY MOVIMIENTOS BANCARIOS PARA ESTE RANGO DE FECHA Y CUENTA BANCARIA",
            { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
          );
        }
      });
    } else {
      humane.log(
        "<span class='fa fa-info'></span> ATENCION!! Por favor complete todos los campos en la grilla",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  };
  // FIN LISTAR

  // FUNCION ANULAR
  $(document).on("click", ".delete", function () {
    var pos = $(".delete").index(this);
    $("#tabla tbody tr:eq(" + pos + ")")
      .find("td:eq(1)")
      .each(function () {
        var cod;
        cod = $(this).html();
        $("#delete").val(cod);
        $(".msg").html(
          '<h4 class="modal-title" id="myModalLabel">Desea eliminar el Registro Nro. ' +
          cod +
          " ?</h4>"
        );
      });
  });
  //esta parte es para que al hacer clic pueda anular
  $(document).on("click", "#delete", function () {
    var id = $("#delete").val();
    $.ajax({
      type: "POST",
      url: "grabar.php",
      data: {
        codigo: id,
        entcod: 0,
        cuenta: 0,
        movnro: 0,
        obs: '',
        tipomov: '',
        usu: 0,
        suc: 0,
        detalle: '{{1,1,1}}',
        ope: 2,
      },
    }).done(function (msg) {
      $("#hide").click();
      mostrarMensajes(msg);
      refrescarDatos();
    });
  });
  // FIN ANULAR

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


  function calcularTotales() {
    var subtotal = 0;
    var total = 0;
    $("#grilladetalle tbody tr")
      .find("td:eq(3)")
      .each(function () {
        subtotal = $(this).text();
        total = parseInt(total) + parseInt(subtotal);
      });
    $("#total").html("Total: " + total + " Gs.");
  }

  //FUNCION PARA MOSTRAR SOLO LA PARTE QUE QUEREMOS DE LA RESPUESTO DEL SERVIDOR
  function mostrarMensajes(msg) {
    var r = msg.split("_/_");
    var texto = r[0];
    var tipo = r[1];
    if (tipo.trim() == "notice") {
      texto = texto.split("NOTICE:");
      texto = texto[1];
      humane.log("<span class='fa fa-check'></span> " + texto, {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-success",
      });
    }
    if (tipo.trim() == "error") {
      texto = texto.split("ERROR:");
      texto = texto[2];
      humane.log("<span class='fa fa-info'></span> " + texto, {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-error",
      });
    }
  }

  function refrescarDatos() {
    tabla.fnReloadAjax();
  }
  // Funciones
  $(function () {
    $(".chosen-select").chosen({ width: "100%" });
  });
});
