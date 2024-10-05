$(document).ready(function () {
  var Path = "imp_facturas_varias.php";
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
      { data: "proveedor" },
      { data: "ruc" },
      { data: "tipofactcod" },
      { data: "nro_factura" },
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
    //var x,detalle,subtotal;
    var detalle =
      '<table  class="table table-striped table-bordered nowrap table-hover">\n\
    <tr width=90px class="success"><th>Factura Varias N.°</th><th>Cuotas</th><th>Vencimiento</th><th>Monto</th><th>Saldo</th><th>Estado</th><th>Fecha pago</th></tr>';
    var total = 0;
    var subtotal;
    for (var x = 0; x < d.detalle.length; x++) {
      subtotal = d.detalle[x].cantidad * d.detalle[x].precio;
      total += parseInt(subtotal);
      detalle +=
        "<tr>" +
        "<td>" +
        d.detalle[x].fact_var_cod +
        "</td>" +
        "<td>" +
        d.detalle[x].cuotas +
        "</td>" +
        "<td>" +
        d.detalle[x].venc +
        "</td>" +
        "<td>" +
        d.detalle[x].monto +
        "</td>" +
        "<td>" +
        d.detalle[x].saldo +
        "</td>" +
        "<td>" +
        d.detalle[x].estado +
        "</td>" +
        "<td>" +
        d.detalle[x].cuotas_fecha_pago +
        "</td>" +
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
      "</tfoot>" +
      "</table></center>";
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

  //ANULACION
  $(document).on("click", ".delete", function () {
    var pos = $(".delete").index(this);
    $("#tabla tbody tr:eq(" + pos + ")")
      .find("td:eq(1)")
      .each(function () {
        var codigo = $(this).html();
        $("#cod_eliminar").val(codigo);
        $(".msg").html(
          '<h4 class="modal-title" id="myModalLabel">DESEA ELIMINAR EL REGISTRO NROº. ' +
          codigo +
          " ?</h4>"
        );
      });
  });

  $(document).on("click", "#delete", function () {
    var codigo = $("#cod_eliminar").val();
    $.ajax({
      type: "POST",
      url: "grabar.php",
      data: {
        codigo: codigo,
        nro: 0,
        empresa: 0,
        sucursal: 0,
        usuario: 0,
        funcionario: 0,
        cliente: 0,
        tipofact: "",
        plazo: "",
        cuotas: "",
        timbrado: 0,
        apercier: 1,
        cboiddeposito: 0,
        detalle: "{{1,1,1}}",
        detalle2: "{}",
        ope: 2,
      },
    }).done(function (msg) {
      $("#hide").click();
      mostrarMensaje(msg);
      refrescarDatos();
    });
  });
  // FIN ANULACION

  /////////////////////////////////////////////// COBRAR CHEQUES SOLO /////////////////////////////////////////////////////////////////
  $(document).on("click", "#cobroChequesSolo", function () {
    var provcod = $("#proveedor").val();
    var nrofact = $("#venta > option:selected").html();
    nrofact = nrofact.split('Fact N.°: ')[1];
    var fcobro = $("#tipoCobro").val();
    var usu = $("#usuario").val();
    var suc = $("#sucursal").val();
    var factvarcod = $("#venta").val();
    var detalleCheques = $("#detalleCheques").val();
    var montoCheque = $("#MontoPagarChequesSolo").val();
    var saldoDeuda = $("#montoDeudaChequesSolo").val();
    debugger
    if (provcod > 0 && nrofact !== " " && fcobro > 0 && factvarcod > 0 && detalleCheques !== "{}") {
      if (montoCheque <= saldoDeuda) {
        $.ajax({
          type: "POST",
          url: "grabar.php",
          data: {
            codigo: 0,
            provcod: provcod,
            nrofact: nrofact,
            fcobro: fcobro,
            usuario: usu,
            sucursal: suc,
            factvarcod: factvarcod,
            detalleCheques: detalleCheques,
            montoDisponible: montoCheque,
            ope: 1,
          },
          // Orden: codigo, provcod, nrofactura, fcobcod, usucod, sucod, factvarcod, detCheques, montoDisponible, oper
        }).done(function (msg) {
          mostrarMensaje(msg);
          limpiarCampos();
          refrescarDatos();
          ultcod();
        });
      } else {
        humane.log(
          "<span class='fa fa-info'></span> EL MONTO A PAGAR NO PUEDE SE MAYOR A LA DEUDA TOTAL",
          {
            timeout: 4000,
            clickToClose: true,
            addnCls: "humane-flatty-warning",
          }
        );
      }
    } else {
      humane.log(
        "<span class='fa fa-info'></span> Por favor complete todos los campos",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  });

  $(document).on("click", ".agregarCobroChequesSolo", function () {
    $("#detalle-grilla").css({ display: "block" });
    let pagoCod = $("#cobroNro").val();
    var todoBan = $("#bancoChequeSolo option:selected").html();
    var todoBanCod = $("#bancoChequeSolo").val();

    var todoNumCuen = $("#numeroCuentaChequeSolo").val();
    var todoNumCuenTxt = $("#numeroCuentaChequeSolo > option:selected").html();
    todoNumCuenTxt = todoNumCuenTxt.split("Cuenta N.° ")[1];
    var cuenTxt = todoNumCuen + ' - ' + todoNumCuenTxt;

    var todoNumCheq = $("#numeroChequeSolo").val();
    var todoCheqMon = parseInt($("#montoChequeSolo").val());

    // alert(`${todoBan}  - ${todoNumCuen} - ${todoNumCheq} - ${todoCheqMon}`)
    if (
      todoBanCod > 0 &&
      todoNumCuen > 0 &&
      todoNumCheq > 0 &&
      todoCheqMon > 0
    ) {
      var todoTotal = $("#MontoPagarChequesSolo").val();
      var todoMontoPagar = parseInt($("#montoDeudaChequesSolo").val());
      if (todoTotal == "") {
        todoTotal = 0;
      }
      var totalAcumulado = parseInt(todoTotal) + todoCheqMon;

      if (totalAcumulado <= todoMontoPagar) {
        var repetido = false;
        var banco = 0;
        let cuenta = 0;
        let filac;
        let bandera = true;
        var contador = 0;
        $("#grilladetallechequesSolo tbody tr").each(function (fila1) {
          if (bandera) {
            filac = fila1;
            $(this)
              .children("td")
              .each(function (col1) {
                if (col1 === 1) {
                  banco = $(this).text();
                  banco = banco.split(' - ')[0];
                  if (banco === todoBanCod) {
                    // repetido = true;
                    $("#grilladetallechequesSolo tbody tr:eq(" + filac + ")")
                      .children("td")
                      .each(function (col2) {
                        if (col2 === 2) {
                          cuenta = $(this).text();
                          cuenta = cuenta.split(' - ')[0];
                          if (banco === todoBanCod && cuenta === todoNumCuen) {
                            repetido = true;
                            humane.log(
                              "<span class='fa fa-info'></span> DE MOMENTO SOLO SE PODRIA GENERAR UN CHEQUE A LA VEZ",
                              {
                                timeout: 4000,
                                clickToClose: true,
                                addnCls: "humane-flatty-warning",
                              }
                            );
                          }
                        }
                      });
                  }
                }
              });
          }
        });

        if (!repetido) {
          $("#grilladetallechequesSolo > tbody:last").append(
            '<tr><td style="text-align: center;">' +
            pagoCod +
            '</td><td style="text-align: center;">' +
            todoBan +
            '</td><td style="text-align: center;">' +
            cuenTxt +
            '</td><td style="text-align: center;">' +
            todoNumCheq +
            '</td><td style="text-align: center;">' +
            todoCheqMon +
            '</td><td class="eliminarDetalleChequesSolo"><input type="button" value="Х" id="bf"   class="bf"  style="background:  pink; color: black;"/></td></tr>'
          );
          contador++;
        }
        cargarGrillaChequesSolo();
        calcularTotalesChequesSolo();
        calcularTotalChequesSolo();
      } else {
        humane.log(
          "<span class='fa fa-info'></span> EL MONTO A PAGAR NO PUEDE SER MAYOR AL MONTO DE LA DEUDA",
          {
            timeout: 4000,
            clickToClose: true,
            addnCls: "humane-flatty-warning",
          }
        );
      }
    } else {
      humane.log(
        "<span class='fa fa-info'></span> Por favor complete todos los campos de cheques",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  });

  $(document).on("click", ".eliminarDetalleChequesSolo", function () {
    var parent = $(this).parent();
    $(parent).remove();
    $("#totalChequesSolo").text(`Subtotal Cheques: 0 Gs.`);
    cargarGrillaChequesSolo();
    calcularTotalesChequesSolo();
    calcularTotalChequesSolo();
  });

  function cargarGrillaChequesSolo() {
    $("#detalleCheques").val("");
    var salida = "{";
    $("#grilladetallechequesSolo tbody tr").each(function (index) {
      var campo1, campo2, campo3, campo4, campo;
      salida = salida + "{";
      $(this)
        .children("td")
        .each(function (index2) {
          switch (index2) {
            case 0: //codigo
              campo1 = $(this).text();
              salida = salida + campo1 + ",";
              break;
            case 1: //banco
              campo2 = $(this).text();
              campo2 = campo2.split(' - ')[0];
              salida = salida + campo2 + ",";
              break;
            case 2: //numero cuenta
              campo3 = $(this).text();
              campo3 = campo3.split(' - ')[0];
              salida = salida + campo3 + ",";
              break;
            case 3: //numero cheque
              campo4 = $(this).text();
              salida = salida + campo4 + ",";
              break;
            case 4: //monto cheque
              campo5 = $(this).text();
              salida = salida + campo5;
              break;
          }
        });
      if (index < $("#grilladetallechequesSolo tbody tr").length - 1) {
        salida = salida + "},";
      } else {
        salida = salida + "}";
      }
    });
    salida = salida + "}"; //la ultima llave del array
    $("#detalleCheques").val(salida);
  }

  function calcularTotalesChequesSolo() {
    var totalCheques = 0;
    $("#grilladetallechequesSolo tbody tr").each(function (fila) {
      $(this)
        .children("td")
        .each(function (col) {
          if (col === 4) {
            totalCheques =
              totalCheques + parseInt($(this).text().replace(/\./g, ""));
          }
        });
    });
    $("#totalChequesSolo").text(`Subtotal Cheques: ${totalCheques} Gs.`);
  }

  function calcularTotalChequesSolo() {
    var totalPagar = 0;
    var totalCheques = $("#totalChequesSolo").text().split(" ");
    totalCheques = parseInt(totalCheques[2].trim());
    var totalDeuda = $("#montoDeudaChequesSolo").val();

    totalPagar = totalCheques;
    // debugger
    if (totalDeuda >= totalPagar) {
      $("#MontoPagarChequesSolo").val(totalPagar);
    } else {
      humane.log(
        "<span class='fa fa-info'></span> EL MONTO A PAGAR NO PUEDE SER MAYOR AL MONTO DE LA DEUDA 2",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  }
  /////////////////////////////////////////////// COBRAR CHEQUES SOLO FIN /////////////////////////////////////////////////////////////////

  // CALCULAR TOTAL  =  efectivo + total cheque + total tarjetas
  $("#todoMontoEfectivo").on("change", function () {
    calcularTotal();
  });

  function calcularTotal() {
    var totalPagar = 0;
    var totalCheques = $("#totalCheques").text().split(" ");
    totalCheques = parseInt(totalCheques[2].trim());
    var totalTarjetas = $("#totalTarjetas").text().split(" ");
    totalTarjetas = parseInt(totalTarjetas[2].trim());
    var totalEfectivo = $("#todoMontoEfectivo").val();
    var totalDeuda = $("#todoMontoPagar").val();
    if (totalEfectivo == "") {
      totalEfectivo = 0;
    }
    totalPagar = parseInt(totalEfectivo) + totalTarjetas + totalCheques;
    // debugger
    if (totalDeuda >= totalPagar) {
      $("#todoTotal").val(totalPagar);
    } else {
      humane.log(
        "<span class='fa fa-info'></span> EL MONTO A PAGAR NO PUEDE SER MAYOR AL MONTO DE LA DEUDA",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  }
  // CALCULAR TOTAL FIN

  // COBRO EN CHEQUE GRILLA DETALLE FIN
  $("#proveedor").on("change", function () {
    let proveedor = $("#proveedor").val();
    let ventas = document.getElementById("venta");
    let fragment = document.createDocumentFragment();
    if (proveedor > 0) {
      $.ajax({
        type: "GET",
        url: "getFacturasVarias.php",
        data: { proveedor: proveedor },
      }).done(function (data) {
        if (data != "error") {
          let datos = JSON.parse(data);
          console.log(datos);
          for (const ven of datos) {
            const selectItem = document.createElement("OPTION");
            selectItem.setAttribute("value", ven["fact_var_cod"]);
            selectItem.textContent = `Fact N.°: ${ven.nro_factura} `;
            fragment.append(selectItem);
          }
          $("#venta").children("option").remove();

          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija una factura a pagar";

          ventas.insertBefore(opcion, ventas.children[0]);
          ventas.append(fragment);

          ventas.append(fragment);
          $("#venta").selectpicker("refresh");
        } else {
          //SI AUN NO POSEE UNA VENTA CON ESTADO PENDIENTE A COBRAR
          humane.log(
            "<span class='fa fa-info'></span>ESTE PROVEEDOR NO TIENE FACTURA A SER PAGADA",
            {
              timeout: 6000,
              clickToClose: true,
              addnCls: "humane-flatty-error",
            }
          );

          $("#venta").children("option").remove();
          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija primero un proveedor";

          ventas.insertBefore(opcion, ventas.children[0]);
          $("#venta").selectpicker("refresh");
        }
      });
    }
  });

  // traer deuda total y cuotas pagadas
  $("#venta").on("change", function () {
    let ventanro = $("#venta").val();
    if (ventanro > 0) {
      getMontoDeuda(ventanro);
      getCuotas(ventanro);
      $("#confirmar").removeAttr("disabled", true);
    }
  });

  function getMontoDeuda(ventanro) {
    if (ventanro > 0) {
      $.ajax({
        type: "GET",
        url: "getMontoDeuda.php",
        data: { codigo: ventanro },
      }).done(function (data) {
        console.log(data);
        let datos = JSON.parse(data);
        $("#deuda").val("");
        $("#deuda").val(
          `${datos.data[0].monto_deuda} / ${datos.data[0].monto_saldo}`
        );
        $("#todoMontoPagar").val(datos.data[0].monto_saldo);
        $("#DeudaPagarTarjetasSolo").val(datos.data[0].monto_saldo);
        $("#montoDeudaChequesSolo").val(datos.data[0].monto_saldo);
        $("#efectivoSaldoDeuda").val(datos.data[0].monto_saldo); // para pago solo efectivo
      });
    }
  }

  function getCuotas(ventanro) {
    if (ventanro > 0) {
      $.ajax({
        type: "GET",
        url: "getCuotas.php",
        data: { codigo: ventanro },
      }).done(function (data) {
        let datos = JSON.parse(data);
        console.log(datos);
        $("#cuotaspagadas").val("");
        $("#cuotaspagadas").val(
          `${datos.data[0].cuotas_pagadas} de ${datos.data[0].cuotas_total}`
        );
        document
          .getElementById("cuotas")
          .setAttribute(
            "max",
            `${datos.data[0].cuotas_total - datos.data[0].cuotas_pagadas}`
          );
      });
    }
  }
  // fin traer deuda total y cuotas pagadas

  // traer monto de la cuotas a pagar
  $("#cuotas").on("blur", function () {
    let cuotas = $("#cuotas").val();
    let ventanro = $("#venta").val();
    if (cuotas > 0 && ventanro > 0) {
      $.ajax({
        type: "GET",
        url: "getMontoCuota.php",
        data: { cuotas: cuotas, codigo: ventanro },
      }).done(function (data) {
        console.log(data);
        $("#monto").val("");
        $("#monto").val(data);
        $("#monto").attr("disabled", true);
      });
    }
  });
  // fin traer monto de la cuotas a pagar

  //desplegar modal segun el tipo de cobro
  $("#tipoCobro").on("change", function () {
    let tipoCobro = parseInt($("#tipoCobro").val());
    let btnConfirmar = document.getElementById("confirmar");
    if (tipoCobro === 1) {
      btnConfirmar.dataset.target = "#efectivo";
      //    $("#confirmar").data("target", "#efectivo");
    } else if (tipoCobro === 2) {
      btnConfirmar.dataset.target = "#tarjetas";
    } else if (tipoCobro === 3) {
      btnConfirmar.dataset.target = "#cheques";
    } else if (tipoCobro === 4) {
      btnConfirmar.dataset.target = "#todos";
    }
  });
  //desplegar modal segun el tipo de cobro fin

  $("#bancoChequeSolo").change(function () {
    var entcod = $("#bancoChequeSolo").val();
    if (entcod > 0) {
      $.ajax({
        type: "POST",
        url: "cuentas_corrientes.php",
        data: { entcod: entcod },
      }).done(function (data) {
        $("#numeroCuentaChequeSolo > option").remove();
        $("#numeroCuentaChequeSolo").append(data);
        $("#numeroCuentaChequeSolo").selectpicker("refresh");
      });
    } else {
      $("#numeroCuentaChequeSolo > option").remove();
      $("#numeroCuentaChequeSolo").append(`<option value='0'>Elija una opcion</option>`);
      $("#numeroCuentaChequeSolo").selectpicker("refresh");
    }
  });

  $("#numeroCuentaChequeSolo").change(function () {
    var entcod = $("#bancoChequeSolo").val();
    var cuenta = $("#numeroCuentaChequeSolo").val();
    if (entcod > 0 && cuenta > 0) {
      $.ajax({
        type: "GET",
        url: "siguiente_cheque.php",
        data: { entcod, cuenta },
      }).done(function (data) {
        if (data !== 'error') {
          $("#numeroChequeSolo").val(data);
        }else{
          humane.log(
            "<span class='fa fa-info'></span>VERIFIQUE QUE EXISTA UNA CHEQUERA Y QUE ESTE ACTIVO",
            {
              timeout: 4000,
              clickToClose: true,
              addnCls: "humane-flatty-warning",
            }
          );
          $("#numeroChequeSolo").val('');
        }
      });
    } else {
      $("#numeroChequeSolo").val('');
    }
  });

  // FUNCION COBRAR
  $(document).on("click", ".cobrar", function () {
    var pos = $(".cobrar").index(this);
    let cod;
    $("#tabla tbody tr:eq(" + pos + ")")
      .find("td:eq(1)")
      .each(function () {
        cod = $(this).html();
        $("#ventanro").val(cod);
        //  alert(cod)
        $.ajax({
          type: "GET",
          url: "cobrar.php",
          data: { cod: cod },
        }).done(function (data) {
          let datos = JSON.parse(data);
          //    console.log(datos)
          if (datos.data[0].error) {
            humane.log(
              "<span class='fa fa-info'></span> ESTA VENTA YA HA SIDO COBRADA EN SU TOTALIDAD",
              {
                timeout: 4000,
                clickToClose: true,
                addnCls: "humane-flatty-warning",
              }
            );
          } else {
            $("#cliente").val(datos.data[0].cliente).trigger("change");
            $("#cliente").attr("disabled", true);
            setTimeout(() => {
              $("#venta").val(datos.data[0].ventanro).trigger("change");
              $("#venta").attr("disabled", true);
            }, 1000);
          }
        });
      });
  });
  // FIN COBRAR

  function limpiarCampos() {
    $("#proveedor").val("0").trigger("change");
    $("#venta").val("0").trigger("change");
    $("#deuda").val("");
    $("#cuotaspagadas").val("");
    $("#cuotas").val("");
    $("#monto").val("");
    $("#todoTotal").val("");

    $("#montoEfectivo").val("");
    // cheques solo
    $("#MontoPagarChequesSolo").val("");
    $("#numeroCuentaChequeSolo").val("");
    $("#serieChequeSolo").val("");
    $("#numeroChequeSolo").val("");
    $("#montoChequeSolo").val("");
    $("#emisionChequeSolo").val("");
    $("#emisionChequeSolo").val("");
    $("#libradorChequeSolo").val("");
    $("#bancoChequeSolo").val("0").trigger("change");
    $("#tipoChequeSolo").val("0").trigger("change");
    $("#grilladetallechequesSolo tbody tr").remove();
    $("#totalChequesSolo").html("SUBTOTAL CHEQUE: 0 Gs.");
    // fin cheques solo
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
      humane.log("<span class='fa fa-info'></span>" + texto, {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-error",
      });
    }
  }

  function ultcod() {
    $.ajax({
      type: "GET",
      url: "ultcod.php",
    }).done(function (cod) {
      $("#cobroNro").val(cod);
    });
  }

  $(function () {
    $("#agregar").keypress(function (e) {
      if (e.which === 13) {
        // agregar_fila();
      }
    });

    $(".chosen-select").chosen({ width: "100%" });
  });
});
