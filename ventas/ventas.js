$(document).ready(function () {
  var Path = "imp_ventas.php";
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
    $.each(detailRows, function (i, cod) {
      $("#" + cod + " td.details-control").trigger("click");
    });
  });

  //TABLA del DETALLE
  function format(d) {
    var detalle =
      '<table  class="table table-striped table-bordered nowrap table-hover">\n\
<tr width=90px class="success"><th>Codigo</th><th>Descripción</th><th>Marca</th><th>Sección</th><th>Cantidad</th><th>Precio Unitario</th><th>Exenta</th><th>Grav 5</th><th>Grav 10</th><th>Subtotal</th></tr>';
    var total = 0;
    var subtotal;
    for (var x = 0; x < d.detalle.length; x++) {
      subtotal = d.detalle[x].cantidad * d.detalle[x].precio;
      total += parseInt(subtotal);
      detalle +=
        "<tr>" +
        "<td>" +
        d.detalle[x].codigo +
        "</td>" +
        "<td>" +
        d.detalle[x].item +
        "</td>" +
        "<td>" +
        d.detalle[x].marca +
        "</td>" +
        "<td>" +
        d.detalle[x].cboiddeposito +
        "</td>" +
        "<td>" +
        d.detalle[x].cantidad +
        "</td>" +
        "<td>" +
        d.detalle[x].precio +
        "</td>" +
        "<td>" +
        d.detalle[x].exenta +
        "</td>" +
        "<td>" +
        d.detalle[x].grav5 +
        "</td>" +
        "<td>" +
        d.detalle[x].grav10 +
        "</td>" +
        //  '<td>' + totalgral.toLocaleString() + '</td>' +
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
    var itemdesc = $("#item option:selected").html();
    var deposito = $("#cboiddeposito option:selected").html();
    var items = $("#item").val();
    var marcod = $("#marcas").val();
    var mardesc = $("#marcas option:selected").html();
    var producto = items.split("~");
    var cant = parseInt($("#cantidad").val());
    var prec = parseInt($("#precio").val());
    var tipoimp = $("#tipoimpuesto").val();

    var exenta = 0;
    var grav5 = 0;
    var grav10 = 0;
    $("#cboiddeposito").attr("enabled", "true").trigger("chosen:updated");

    if (items > 0 && cant > 0) {
      //VALIDAMOS QUE LOS CAMPOS DE  ITEM Y CANTIDAD ESTEAN CARGADOS
      if (tipoimp == "3 - EXENTAS") {
        exenta = cant * prec;
        grav5 = 0;
        grav10 = 0;
      } else if (tipoimp === "2 - GRAVADA 5%") {
        exenta = 0;
        grav5 = cant * prec;
        grav10 = 0;
      } else if (tipoimp === "1 - GRAVADA 10%") {
        exenta = 0;
        grav5 = 0;
        grav10 = cant * prec;
      }

      var repetido = false;
      var co = 0;
      let co2 = 0;
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
                if (co === items) {
                  // repetido = true;
                  $("#grilladetalle tbody tr:eq(" + filac + ")")
                    .children("td")
                    .each(function (col2) {
                      if (col2 === 2) {
                        co2 = $(this).text();
                        co2 = $(this).text();
                        co2 = co2.split("-");
                        co2 = co2[0].trim();

                        if (co === items && co2 === marcod) {
                          repetido = true;

                          $("#grilladetalle tbody tr:eq(" + filac + ")")
                            .children("td")
                            .each(function (i) {
                              if (i === 2) {
                                $(this).text(mardesc);
                              }
                              if (i === 3) {
                                $(this).text(deposito);
                              }
                              if (i === 4) {
                                $(this).text(cant);
                              }
                              if (i === 5) {
                                $(this).text(prec);
                              }
                              if (i === 6) {
                                $(this).text(exenta);
                              }
                              if (i === 7) {
                                $(this).text(grav5);
                              }
                              if (i === 8) {
                                $(this).text(grav10);
                              }
                              bandera = false;
                            });
                        }
                      }
                    });
                }
              }
            });
        }
      }); /////////////// FALTA VALIDAR REPETICIONES DE ITEMS ///////////// 11-11-2020

      if (!repetido) {
        $("#grilladetalle > tbody:last").append(
          '<tr><td style="text-align: center;">' +
            producto[0] +
            "</td><td>" +
            itemdesc +
            '</td><td style="text-align: center;">' +
            mardesc +
            '</td><td style="text-align: center;">' +
            deposito +
            '</td><td style="text-align: center;">' +
            cant +
            '</td><td style="text-align: right;">' +
            prec +
            '</td><td style="text-align: right;">' +
            exenta +
            '</td><td style="text-align: right;">' +
            grav5 +
            '</td><td style="text-align: right;">' +
            grav10 +
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
  });

  //CARGARGRILLA
  function cargargrilla() {
    var salida = "{";
    $("#grilladetalle tbody tr").each(function (index) {
      var campo1, campo2, campo3, campo4, campo5;
      salida = salida + "{";
      $(this)
        .children("td")
        .each(function (index2) {
          switch (index2) {
            case 0: //codigo
              campo1 = $(this).text();
              salida = salida + campo1 + ",";
              break;
            case 2: //marcas
              campo2 = $(this).text();
              campo2 = campo2.split("-");
              campo2 = campo2[0].trim();
              salida = salida + campo2 + ",";
              break;
            case 3: //deposito
              campo3 = $(this).text();
              campo3 = campo3.split("-");
              campo3 = campo3[0].trim();
              salida = salida + campo3 + ",";
              break;
            case 4: //cantidad
              campo4 = $(this).text();
              salida = salida + campo4 + ",";
              break;
            case 5: //precio
              campo5 = $(this).text();
              salida = salida + campo5;
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
    var suc = $("#sucursal").val();
    var usu = $("#usuario").val();
    var cliente = $("#cliente").val();
    var tfactura = $("#tipofact").val();
    var plazo = $("#plazo").val();
    var cuotas = $("#cuotas").val();
    var detalle = $("#detalle").val();
    var detalle2 = $("#detalle2").val();

    if (
      cliente > 0 &&
      plazo !== "" &&
      cuotas !== "" &&
      detalle !== "" &&
      detalle2 !== ""
    ) {
      $.ajax({
        type: "POST",
        url: "grabar.php",
        data: {
          codigo: 0,
          sucursal: suc,
          usuario: usu,
          cliente: cliente,
          tipofact: tfactura,
          plazo: plazo,
          cuotas: cuotas,
          detalle: detalle,
          detalle2: detalle2,
          ope: 1,
        },
      }).done(function (msg) {
        var res = msg.split("_/_");
        var tipo = res[1];
        if (tipo.trim() == "notice") {
          var pedidocod = $("#pedidoventa").val();
          if (pedidocod > 0) {
            $.ajax({
              type: "POST",
              url: "actualizarEstadoPedidos.php",
              data: { pedidocod },
            });
          }
        }
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
          '<h4 class="modal-title" id="myModalLabel">DESEA ELIMINAR EL REGISTRO NROº. ' +
            codigo +
            " ?</h4>"
        );
      });
  });
  //esta parte es para que al hacer clic pueda anular
  $(document).on("click", "#delete", function () {
    var codigo = $("#cod_eliminar").val();
    $.ajax({
      type: "POST",
      url: "grabar.php",
      data: {
        codigo: codigo,
        sucursal: 0,
        usuario: 0,
        cliente: 0,
        tipofact: 0,
        plazo: 0,
        cuotas: 0,
        detalle: "{}",
        detalle2: "{}",
        ope: 2,
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
          if (col === 6) {
            exe = exe + parseInt($(this).text().replace(/\./g, ""));
          }
          if (col === 7) {
            g5 = g5 + parseInt($(this).text().replace(/\./g, ""));
          }
          if (col === 8) {
            g10 = g10 + parseInt($(this).text().replace(/\./g, ""));
          }
        });
    });
    var totales = "<tr>";
    totales += '<th colspan="6">SUB TOTALES</th>';
    totales += '<th style="text-align: right;">' + exe + "</th>";
    totales += '<th style="text-align: right;">' + g5 + "</th>";
    totales += '<th style="text-align: right;">' + g10 + "</th>";
    totales += "<th></th>";
    totales += "</tr>";
    var iva5 = Math.round(g5 / 21);
    var iva10 = Math.round(g10 / 11);
    totales += "<tr>";
    totales += '<th colspan="7">LIQUIDACION DE IVA</th>';
    totales += '<th style="text-align: right;">' + iva5 + "</th>";
    totales += '<th style="text-align: right;">' + iva10 + "</th>";
    totales += '<th style="text-align: right;"></th>';
    totales += "</tr>";
    var totaliva = Math.round(g5 / 21 + g10 / 11);
    totales += '<th colspan="8">TOTAL DE IVA</th>';
    totales += '<th style="text-align: right;">' + totaliva + "</th>";
    totales += "<th></th>";
    totales += "</tr>";
    //
    //      var totalgral = (exe + g5 + g10);
    //   totales += "<tr class=\"danger\">";
    //   totales += "<th colspan=\"6\"><h4>TOTAL DESCUENTO</th>";
    //  totales += "<th style=\"text-align: right;\">" + totalgral + "</th>";
    //  totales += "<th></th>";
    //  totales += "</tr>";
    //
    var totalgral = exe + g5 + g10;
    totales += '<tr class="danger">';
    totales += '<th colspan="8"><h4>TOTAL GENERAL</h4></th>';
    totales +=
      '<th style="text-align: right;"><h4>' +
      totalgral.toLocaleString() +
      " Gs." +
      "</h4></th>";
    totales += "<th><h4></h4></th>";
    totales += "</tr>";

    $("#grilladetalle tfoot").html(totales);
    $("#detalle2").val(`{${exe},${g5},${g10},${iva5},${iva10}}`);
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

  $("#cliente").change(function () {
    getPedidosVentas();
  });

  //TRAER LAS ORDENES DE TRABAJOS DEL CLIENTE SELECCIONADA CON ESTADO PENDIENTE Y DEL DIA ACTUAL
  function getPedidosVentas() {
    let cliente = document.getElementById("cliente");
    let pedidoventa = document.getElementById("pedidoventa");

    var cod = cliente.value;
    let fragment = document.createDocumentFragment();
    if (cod > 0) {
      $.ajax({
        type: "GET",
        url: "getPedidoVentas.php",
        data: { cod: cod },
      }).done(function (msg) {
        if (msg != "error") {
          var datos = JSON.parse(msg);
          for (dato of datos) {
            let selectItem = document.createElement("OPTION");
            selectItem.setAttribute("value", dato.ped_vcod);
            selectItem.textContent = `${dato.ped_vcod} - ${dato.ped_estado}`;

            fragment.append(selectItem);
          }

          $("#pedidoventa").children("option").remove();

          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija su pedido venta";

          pedidoventa.insertBefore(opcion, pedidoventa.children[0]);
          pedidoventa.append(fragment);
          $("#pedidoventa").selectpicker("refresh");
        } else {
          //En caso que el cliente no pedido
          $("#pedidoventa").children("option").remove();

          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Este cliente no posee un pedido";

          pedidoventa.insertBefore(opcion, pedidoventa.children[0]);
          pedidoventa.append(fragment);
          $("#pedidoventa").selectpicker("refresh");
        }
      });
    } else {
      primeraOpcion2();
    }
  }

  //para pedido ventas del cliente
  function primeraOpcion2() {
    let cliente = document.getElementById("cliente").value;
    let ordenes = document.getElementById("pedidoventa");
    if (cliente == "0") {
      $("#pedidoventa").children("option").remove();
      $("#pedidoventa").selectpicker("refresh");

      let opcion = document.createElement("OPTION");
      opcion.setAttribute("value", 0);
      opcion.textContent = "Elija primero un cliente";

      ordenes.insertBefore(opcion, ordenes.children[0]);
      $("#pedidoventa").selectpicker("refresh");
    }
  }

  $("#pedidoventa").change(function () {
    var codigo = $("#pedidoventa").val();
    var suc = $("#sucursal").val();
    if (codigo > 0) {
      $.ajax({
        type: "POST",
        url: "pedidoventas.php",
        data: { cod: codigo, suc: suc },
      }).done(function (msg) {
        if (msg.trim() != "error") {
          var datos = JSON.parse(msg);
          $("#grilladetalle > tbody > tr").remove();
          $("#grilladetalle > tbody").append(datos.filas);
          calcularTotales();
          cargargrilla();
        } else {
          humane.log(
            "<span class='fa fa-check'></span>  NO HAY UN DEPOSITO CON LA CANTIDAD NECESARIA PARA REALIZAR LA VENTA DE ESTE PEDIDO ",
            {
              timeout: 8000,
              clickToClose: true,
              addnCls: "humane-flatty-error",
            }
          );
        }
      });
    }
  });

  function ultcod() {
    $.ajax({
      type: "GET",
      url: "ultcod.php",
    }).done(function (cod) {
      $("#nro").val(cod);
    });
  }

  $("#cboiddeposito").change(function () {
    item_deposito();
    $("#precio").val("0");
    $("#tipoimpuesto").val("");
    $("#stock").val("");
    $("#clasificacion").val("");
  });

  function item_deposito() {
    if ($("#cboiddeposito").val() > 0) {
      var cod = $("#cboiddeposito").val();
      let fragment = document.createDocumentFragment();
      $.ajax({
        type: "POST",
        url: "item.php",
        data: { cod: cod },
      }).done(function (msg) {
        let data = document.getElementById("item");
        if (msg != "error") {
          let items = JSON.parse(msg);

          for (const item of items) {
            const selectItem = document.createElement("OPTION");
            selectItem.setAttribute("value", item["item_cod"]);
            selectItem.textContent = `${item.item_desc}`;

            fragment.append(selectItem);
          }
          $("#item").children("option").remove();

          let primero = document.createElement("OPTION");
          primero.setAttribute("value", 0);
          primero.textContent = "Elija una opcion";

          data.insertBefore(primero, data.children[0]);
          data.append(fragment);

          $("#item").selectpicker("refresh");
        } else {
          $("#item").children("option").remove();

          let primero = document.createElement("OPTION");
          primero.setAttribute("value", 0);
          primero.textContent = "El deposito no posee item actualmente";

          data.insertBefore(primero, data.children[0]);
          $("#item").selectpicker("refresh");
        }
      });
    } else {
      $("#precio").val("0");
      $("#tipoimpuesto").val("");
      $("#stock").val("");
      $("#clasificacion").val("");

      $("#item").children("option").remove();
      let data2 = document.getElementById("item");

      let primer = document.createElement("OPTION");
      primer.setAttribute("value", 0);
      primer.textContent = "Elija primero un deposito";

      data2.insertBefore(primer, data2.children[0]);
      $("#item").selectpicker("refresh");
    }
  }

  $("#item").change(function () {
    marca();
  });

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

  $("#marcas").change(function () {
    stock();
    precio();
    getItem();
  });

  function marca() {
    let marcas = document.getElementById("marcas");
    let fragment = document.createDocumentFragment();

    var cod = $("#item").val();
    if (cod > 0) {
      $.ajax({
        type: "POST",
        url: "marcas.php",
        data: { cod: cod },
      }).done(function (data) {
        // alert(data)
        if (data != "error") {
          var datos = JSON.parse(data);
          for (const mar of datos) {
            const selectItem = document.createElement("OPTION");
            selectItem.setAttribute("value", mar["mar_cod"]);
            selectItem.textContent = `${mar.mar_cod} - ${mar.mar_desc}`;

            fragment.append(selectItem);
          }
          $("#marcas").children("option").remove();

          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija una marca";

          marcas.insertBefore(opcion, marcas.children[0]);
          marcas.append(fragment);

          marcas.append(fragment);
          $("#marcas").selectpicker("refresh");
        } else {
          //SI AUN NO POSEE LA RELACION ITEM- MARCAS
          humane.log(
            "<span class='fa fa-info'></span>  ESTE ITEM NECESITA TENER UNA MARCA ASIGNADA EN MARCAS - ITEMS ",
            {
              timeout: 6000,
              clickToClose: true,
              addnCls: "humane-flatty-error",
            }
          );

          $("#marcas").children("option").remove();
          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija primero un item";

          marcas.insertBefore(opcion, marcas.children[0]);
          $("#marcas").selectpicker("refresh");

          $("#precio").val("");
          $("#stock").val("");
        }
      });
    } else {
      $("#marcas").children("option").remove();
      let opcion = document.createElement("OPTION");
      opcion.setAttribute("value", 0);
      opcion.textContent = "Elija primero un item";

      marcas.insertBefore(opcion, marcas.children[0]);
      $("#marcas").selectpicker("refresh");

      $("#stock").val("");
      $("#precio").val("");
    }
  }

  function stock() {
    var item = $("#item").val();
    var mar = $("#marcas").val();
    var dep = $("#cboiddeposito").val();
    var suc = $("#sucursal").val();
    // alert(`este es suc ${suc}`)
    if (item > 0 && mar > 0) {
      $.ajax({
        type: "POST",
        url: "stock.php",
        data: { item: item, mar: mar, dep: dep, suc: suc },
      }).done(function (stock) {
        $("#stock").val(stock);
        $("#cantidad").focus();
      });
    }
  }

  function precio() {
    var item = $("#item").val();
    var mar = $("#marcas").val();
    if (mar > 0 && item > 0) {
      $.ajax({
        type: "POST",
        url: "precio.php",
        data: { item: item, mar: mar },
      }).done(function (precio) {
        // alert(precio)
        $("#precio").val(precio);
        $("#cantidad").focus();
      });
    }
  }

  function getItem() {
    var cod = $("#item").val();
    if (cod > 0) {
      $.ajax({
        type: "GET",
        url: "getItem.php",
        data: { cod: cod },
      }).done(function (data) {
        datos = JSON.parse(data);
        $("#tipoimpuesto").val(
          datos.tipo_imp_cod + " - " + datos.tipo_imp_desc
        );
      });
    } else {
      $("#tipoimpuesto").val("");
    }
  }

  function vaciar() {
    $("#cliente").val("0").trigger("change");
    $("#pedidoventa").val("0").trigger("change");
    $("#cboiddeposito").val(0).trigger("change");
    $("#item").val("0").trigger("change");
    $("#marcas").val("0").trigger("change");
    $("#stock").val("");
    $("#tipoimpuesto").val("");
    $("#precio").val("");
    $("#cantidad").val("");
    $("#tfact").val(1).trigger("change");
    $("#plazo").val("");
    $("#cuotas").val("");
    $("#detalle2").val("");
    $("#detalle").val("");
    $("#total").html("Total: 0.0 Gs");
    $("#grilladetalle tbody tr").remove();
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

  ///////////////////////////////////////////////////////////////////////////////////////////////////////
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
