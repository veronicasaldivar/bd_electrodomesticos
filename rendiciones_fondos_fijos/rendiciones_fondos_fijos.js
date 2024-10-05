$(document).ready(function () {
  var Path = "imp_rendiciones_fondos.php";
  var dt = $("#tabla").dataTable({
    columns: [
      {
        class: "details-control",
        orderable: false,
        data: null,
        defaultContent: "<a><span class='fa fa-plus'></span></a>",
      },
      { data: "codigo" },
      { data: "ffactura" },
      { data: "cliente" },
      { data: "tipofactcod" },
      { data: "estado" },
      { data: "usuario" },
      { data: "acciones" },
    ],
  });
  dt.fnReloadAjax("datos.php");

  function refrescarDatos() {
    dt.fnReloadAjax();
  }

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
  dt.on("draw", function () {
    $.each(detailRows, function (i, id) {
      $("#" + id + " td.details-control").trigger("click");
    });
  });

  //TABLA del DETALLE
  function format(d) {
    var detalle =
      '<table  class="table table-striped table-bordered nowrap table-hover">\n\
    <tr width=90px class="success"><th>Codigo</th><th>Rubro</th><th>Monto</th><th>Tipo Impuesto</th><th>Grav 10</th><th>Grav 5</th><th>Exentas</th><th>IVA 10</th><th>IVA 5</th><th>Subtotal</th></tr>';
    var total = 0;
    var subtotal;
    for (var x = 0; x < d.detalle.length; x++) {
      subtotal = d.detalle[x].monto;
      total += parseInt(subtotal);
      detalle +=
        "<tr>" +
        "<td>" +
        d.detalle[x].codigo +
        "</td>" +
        "<td>" +
        d.detalle[x].rubro +
        "</td>" +
        "<td>" +
        d.detalle[x].monto +
        "</td>" +
        "<td>" +
        d.detalle[x].tipo_impuesto +
        "</td>" +
        "<td>" +
        d.detalle[x].grav10 +
        "</td>" +
        "<td>" +
        d.detalle[x].grav5 +
        "</td>" +
        "<td>" +
        d.detalle[x].exenta +
        "</td>" +
        "<td>" +
        d.detalle[x].iva10 +
        "</td>" +
        "<td>" +
        d.detalle[x].iva5 +
        "</td>" +
        "<td>" + subtotal + '</td>' +
        "</tr>";
    }
    detalle +=
      "</tbody>" +
      "<tfoot>" +
      "<tr>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "</tr>" +
      "<tr>" +
      "<td>Total</td>" +
      "<td></td>" +
      //'<td></td>' +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      "<td></td>" +
      //'<td></td>' +
      "<td>" +
      total +
      " Gs.</td>" +
      "</tr>" +
      "</tfoot>" +
      "</table></center>";
    //AQUI SE CREA LA OPCION PARA IMPRIMIR DENTRO DEL DETALLE...
    return (
      detalle +
      '<tfoot><tr><th colspan="5" class="text-center" ></th></tr></tfoot></table>\n\
                <div class="row">' +
      '<div class="col-md-2">' +
      '<div class="col-md-12 pull-center">' +
      '<a href="../informes/' +
      Path +
      "?id=" +
      d.codigo +
      '" target="_blank" class="btn btn-sm btn-info btn-block" id="print" ><span class="fa fa-print"></span><b> Imprimir</b></a>' +
      "</div>" +
      "</div>" +
      "</div>"
    );
  }

  $(document).on("click", ".agregar", function agregar_fila() {
    $("#detalle-grilla").css({ display: "block" });
    var rubrocod = $("#rubro").val();
    var rubrodesc = $("#rubro option:selected").html();
    rubrodesc = rubrodesc.split(' - ')[1]
    var monto = parseInt($("#monto").val());
    var tipoimp = $("#tipoimpuesto option:selected").html();
    var grav10 = 0;
    var grav5 = 0;
    var exenta = 0;

    var montoTotal = 0;

    var saldo_asignacion = parseInt($("#saldo_asignacion").val());
    montoTotal = parseInt($("#montoTotal").val());

    debugger
    if ((montoTotal + monto) > saldo_asignacion) {
      humane.log(
        "<span class='fa fa-info'></span> Monto a rendir no puede ser superior al saldo de la asignacion",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
      return
    } else {
      var montoAcuTotal = parseInt($("#montoTotal").val());
      montoAcuTotal = montoAcuTotal + monto
      $("#montoTotal").val(montoAcuTotal)
    }

    if (rubrocod > 0 && monto > 0) {
      //VALIDAMOS QUE LOS CAMPOS DE  ITEM Y CANTIDAD ESTEAN CARGADOS
      if (tipoimp == "3 - EXENTAS") {
        exenta = monto;
        grav5 = 0;
        grav10 = 0;
      } else if (tipoimp === "2 - GRAVADA 5%") {
        exenta = 0;
        grav5 = monto;
        grav10 = 0;
      } else if (tipoimp === "1 - GRAVADA 10%") {
        exenta = 0;
        grav5 = 0;
        grav10 = monto;
      }

      var repetido = false;
      var co = 0;
      let filac;
      let bandera = true;
      var contador = 0;

      $("#grilladetalle tbody tr").each(function (fila1) {
        if (bandera) {
          filac = fila1;
          $(this)
            .children("td")
            .each(function (col1) {
              if (col1 === 0) {
                co = $(this).text();
                if (co === rubrocod) {
                  repetido = true;
                  $("#grilladetalle tbody tr:eq(" + filac + ")")
                    .children("td")
                    .each(function (i) {
                      if (i === 1) {
                        $(this).text(rubrodesc);
                      }
                      if (i === 2) {
                        var montoantes = $(this).text();
                        var montoAcuTotal = parseInt($("#montoTotal").val());
                        montoAcuTotal = montoAcuTotal - montoantes
                        $("#montoTotal").val(montoAcuTotal)
                        $(this).text(monto);

                      }
                      if (i === 3) {
                        $(this).text(tipoimp);
                      }
                      if (i === 4) {
                        $(this).text(grav10);
                      }
                      if (i === 5) {
                        $(this).text(grav5);
                      }
                      if (i === 6) {
                        $(this).text(exenta);
                      }
                      bandera = false;
                    });
                }
              }
            });
        }
      });

      if (!repetido) {
        $("#grilladetalle > tbody:last").append(
          '<tr><td style="text-align: center;">' +
          rubrocod +
          "</td><td>" +
          rubrodesc +
          '</td><td style="text-align: center;">' +
          monto +
          '</td><td style="text-align: center;">' +
          tipoimp +
          '</td><td style="text-align: right;">' +
          grav10 +
          '</td><td style="text-align: right;">' +
          grav5 +
          '</td><td style="text-align: right;">' +
          exenta +
          '</td><td style="text-align: right;">' +
          '</td><td class="eliminar"><input type="button" value="Х" id="bf"   class="bf"  style="background:  pink; color: black;"/></td></tr>'
        );
        contador++;
      }
      calcularTotales();
      cargargrilla();
      $("#cantidad").val("");
    } else {
      humane.log(
        "<span class='fa fa-info'></span> Por favor complete todos los campos",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  });

  $(document).on("click", ".eliminar", function () {
    var parent = $(this).parent();
    $(parent).remove();
    cargargrilla();
    calcularTotales();

    var montoTotal = 0;
    $("#grilladetalle tbody tr").each(function (index) {
      $(this)
        .children("td")
        .each(function (col) {
          if (col === 2) {
            montoTotal += parseInt($(this).text());
          }
        });
    })
    $("#montoTotal").val(montoTotal)
  });

  //CARGARGRILLA
  function cargargrilla() {
    var salida = "{";
    $("#grilladetalle tbody tr").each(function (index) {
      var campo1, campo2, campo3, campo4, campo5, campo6;
      salida = salida + "{";
      $(this)
        .children("td")
        .each(function (index2) {
          switch (index2) {
            case 0: //rubrocod
              campo1 = $(this).text();
              salida = salida + campo1 + ",";
              break;
            case 2: //monto
              campo2 = $(this).text();
              salida = salida + campo2 + ",";
              break;
            case 3: //tipo impuesto 
              campo3 = $(this).text();
              campo3 = campo3.split("-");
              campo3 = campo3[0].trim();
              salida = salida + campo3 + ",";
              break;
            case 4: //grav10
              campo4 = $(this).text();
              salida = salida + campo4 + ",";
              break;
            case 5: //grav 5
              campo5 = $(this).text();
              salida = salida + campo5 + ',';
              break;
            case 6: //exenta
              campo6 = $(this).text();
              salida = salida + campo6;
              break;
          }
        });
      if (index < $("#grilladetalle tbody tr").length - 1) {
        salida = salida + "},";
      } else {
        salida = salida + "}";
      }
    });
    salida = salida + "}"; //la ultima llave del array
    $("#detalle").val(salida);
  }
  // FIN CARGARGRILLA

  // INSERTAR
  $(document).on("click", "#grabar", function () {
    var asignacioncod = $("#asignaciones").val();
    var proveedor = $("#proveedor").val();
    var tfactura = $("#tipofact").val();
    var cuotas = $("#cuotas").val();
    var plazo = $("#plazo").val();
    var timbrado = $("#timbrado").val();
    var timbrado_vig = $("#timbrado_vig").val();
    var nrofact = $("#nrofact").val();
    var fechafact = $("#fechafact").val();
    var obs = $("#obs").val();
    var usu = $("#usuario").val();
    var suc = $("#sucursal").val();
    var detalle = $("#detalle").val();
    debugger
    if (obs === "") obs = '-'
    if (
      asignacioncod > 0 &&
      proveedor > 0 &&
      tfactura > 0 &&
      plazo !== "" &&
      cuotas !== "" &&
      timbrado > 0 &&
      timbrado_vig !== "" &&
      nrofact !== "" &&
      fechafact !== "" &&
      detalle !== ""
    ) {
      $.ajax({
        type: "POST",
        url: "grabar.php",
        data: {
          asignacioncod: asignacioncod,
          codigo: 0,
          proveedor: proveedor,
          tfactura: tfactura,
          timbrado: timbrado,
          timbrado_vig: timbrado_vig,
          nrofact: nrofact,
          fechafact: fechafact,
          plazo: plazo,
          cuotas: cuotas,
          usuario: usu,
          sucursal: suc,
          obs: obs,
          detalle: detalle,
          ope: 1,
          // asigrescod, codigo, prov, tipofact, tim, timvighas, factnro, factfecha, pla, cuo, usu, suc, obs, det[rubro, monto, tipimp], oper
        },
      }).done(function (msg) {
        mostrarMensaje(msg);
        refrescarDatos();
        ultcod();
        vaciar();
        calcularTotales();
      });
    } else {
      humane.log(
        "<span class='fa fa-info'></span> Por favor complete todos los campos",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  });

  // FUNCION ANULAR
  $(document).on("click", ".delete", function () {
    var pos = $(".delete").index(this);
    $("#tabla tbody tr:eq(" + pos + ")")
      .find("td:eq(1)")
      .each(function () {
        var codigo = $(this).html();
        $("#cod_eliminar").val(codigo);
        $(".msg").html(
          '<h4 class="modal-title" id="myModalLabel">DESEA ANULAR EL REGISTRO NROº. ' +
          codigo +
          " ?</h4>"
        );
      });
  });
  $(document).on("click", "#delete", function () {
    var codigo = $("#cod_eliminar").val();
    alert(codigo)
    $.ajax({
      type: "POST",
      url: "grabar.php",
      data: {
        asignacioncod: 0,
        codigo: codigo,
        proveedor: 0,
        tfactura: 0,
        timbrado: 0,
        timbrado_vig: '1/1/1111',
        nrofact: '',
        fechafact: '1/1/1111',
        plazo: 0,
        cuotas: 0,
        usuario: 0,
        sucursal: 0,
        obs: '',
        detalle: '{{1,1,1}}',
        ope: 2,
        // asigrescod, codigo, prov, tipofact, tim, timvighas, factnro, factfecha, pla, cuo, usu, suc, obs, det[rubro, monto, tipimp], oper
      },
    }).done(function (msg) {
      $("#hide").click();
      mostrarMensaje(msg);
      refrescarDatos();
    });
  });
  // FIN ANULAR

  // FUNCIONES
  function calcularTotales() {
    var exe = 0;
    var g5 = 0;
    var g10 = 0;
    $("#grilladetalle tbody tr").each(function (fila) {
      $(this)
        .children("td")
        .each(function (col) {
          if (col === 4) {
            g10 = g10 + parseInt($(this).text().replace(/\./g, ""));
          }
          if (col === 5) {
            g5 = g5 + parseInt($(this).text().replace(/\./g, ""));
          }
          if (col === 6) {
            exe = exe + parseInt($(this).text().replace(/\./g, ""));
          }
        });
    });
    var totales = "<tr>";
    totales += '<th colspan="4">SUB TOTALES</th>';
    totales += '<th style="text-align: right;">' + g10 + "</th>";
    totales += '<th style="text-align: right;">' + g5 + "</th>";
    totales += '<th style="text-align: right;">' + exe + "</th>";
    totales += "<th></th>";
    totales += "</tr>";
    var iva5 = Math.round(g5 / 21);
    var iva10 = Math.round(g10 / 11);
    totales += "<tr>";
    totales += '<th colspan="4">LIQUIDACION DE IVA</th>';
    totales += '<th style="text-align: right;">' + iva10 + "</th>";
    totales += '<th style="text-align: right;">' + iva5 + "</th>";
    totales += '<th style="text-align: right;"></th>';
    totales += "</tr>";
    var totaliva = Math.round(g5 / 21 + g10 / 11);
    totales += '<th colspan="6">TOTAL DE IVA</th>';
    totales += '<th style="text-align: right;">' + totaliva + "</th>";
    totales += "<th></th>";
    totales += "</tr>";

    var totalgral = exe + g5 + g10;
    totales += '<tr class="danger">';
    totales += '<th colspan="6"><h4>TOTAL GENERAL</h4></th>';
    totales +=
      '<th style="text-align: right;"><h4>' +
      totalgral.toLocaleString() +
      " Gs." +
      "</h4></th>";
    totales += "<th><h4></h4></th>";
    totales += "</tr>";

    $("#grilladetalle tfoot").html(totales);
    // $("#detalle2").val(`{${exe},${g5},${g10},${iva5},${iva10}}`);
  }

  function tiposelect() {
    if ($("#tipofact").val() == "1") {
      $("#plazo").attr("disabled", "true");
      $("#plazo").val("0");
      $("#cuotas").attr("disabled", "true");
      $("#cuotas").val("0");
    } else {
      $("#plazo").removeAttr("disabled");
      $("#cuotas").removeAttr("disabled");
    }
  }

  function ultcod() {
    $.ajax({
      type: "GET",
      url: "ultcod.php",
    }).done(function (cod) {
      $("#nro").val(cod);
    });
  }

  $("#tipofact").change(function () {
    if ($("#tipofact").val() == 2) {
      $("#plazo").removeAttr("disabled", true);
      $("#cuotas").removeAttr("disabled", true);
    } else {
      $("#plazo").attr("disabled", true);
      $("#plazo").val(0);
      $("#cuotas").attr("disabled", true);
      $("#cuotas").val(0);
    }
  });

  $("#asignaciones").change(function () {
    var asigcod = $("#asignaciones").val();
    if (asigcod > 0) {
      $.ajax({
        type: "POST",
        url: "monto_disponible.php",
        data: { asignacioncod: asigcod },
      }).done(function (data) {
        var datos = JSON.parse(data)
        $("#saldo_asignacion").val(datos.monto_disponible)
      })

      $.ajax({
        type: "GET",
        url: "asignaciones.php",
        data: { asigcod: asigcod }
      }).done(function (data) {
        var datos = JSON.parse(data)
        $("#monto_asignacion").val(datos.monto_asignado)
      })
    }
  });

  function vaciar() {
    $("#asignaciones").val("0").trigger("change");
    $("#monto_asignacion").val("");
    $("#saldo_asignacion").val("");
    $("#proveedor").val("0").trigger("change");
    $("#timbrado").val("");
    $("#timbrado_vig").val("");
    $("#nrofact").val("");
    $("#fechafact").val("");
    $("#rubro").val("0").trigger("change");
    $("#tipodocumento").val("0").trigger("change");
    $("#tipoimpuesto").val("0").trigger("change");
    $("#monto").val("");
    $("#tipofact").val("1").trigger("change");
    $("#plazo").val(0);
    $("#cuotas").val(0);
    $("#obs").val("");
    $("#detalle").val("");
    $("#total").html("Total: 0.0 Gs");
    $("#grilladetalle tbody tr").remove();
    $("#montoTotal").val(0)
  }

  function mostrarMensaje(msg) {
    var r = msg.split("_/_");
    var texto = r[0];
    var tipo = r[1];

    if (tipo.trim() == "notice") {
      texto = texto.split("NOTICE:");
      texto = texto[1];

      humane.log("<span class='fa fa-check'></span>" + texto, {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-success",
      });
    }
    if (tipo.trim() == "error") {
      texto = texto.split("ERROR:");
      texto = texto[2];
      msgError = texto.split('CONTEXT:')[0]; //--este

      humane.log("<span class='fa fa-info'></span>" + msgError, {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-error",
      });
    }
  }

  $(function () {
    $("#agregar").keypress(function (e) {
      if (e.which === 13) {
        // agregar_fila();
      }
    });

    $(".chosen-select").chosen({ width: "100%" });
    tiposelect();
    calcularTotales();
  });
});
