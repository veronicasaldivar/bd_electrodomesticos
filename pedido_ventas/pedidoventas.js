$(function () {
  var Path = "imp_pedidoventas.php";

  var tabla = $("#tabla").dataTable({
    columns: [
      {
        class: "details-control",
        orderable: false,
        data: null,
        defaultContent: "<a><span class='fa fa-plus'></span></a>",
      },
      { data: "cod" },
      { data: "nro" },
      { data: "fecha" },
      { data: "cli" },
      { data: "ruc" },
      { data: "usu" },
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

  function format(d) {
    // `d` is the original data object for the row
    var deta =
      '<table  class="table table-striped table-bordered nowrap table-hover">\n\
<tr width=80px class="info"><th>Codigo</th><th>Descripcion</th><th>Marca</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>';
    var total = 0;
    var totalgral = precio;
    for (var x = 0; x < d.detalle.length; x++) {
      subtotal = d.detalle[x].cantidad * d.detalle[x].precio;
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
        d.detalle[x].marca +
        "</td>" +
        "<td width=50px>" +
        d.detalle[x].cantidad +
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
      "<td></td>" +
      "<td></td>" +
      "</tr>" +
      "<tr>" +
      "<td>Total</td>" +
      "<td></td>" +
      "<td></td>" +
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

  // INSERTAR GRILLA DE PEDIDO Compras
  $(document).on("click", ".agregar", function () {
    $("#detalle-grilla").css({ display: "block" });
    var producto = $("#item option:selected").html();
    var marca = $("#marca option:selected").html();
    var procod = $("#item").val();
    var cant = $("#cantidad").val();
    var marcod = $("#marca").val();
    var prec = $("#precio").val();
    prec = prec.replace(" Gs.", "");
    var subtotal = cant * prec;
    if (procod > 0 && marcod > 0 && cant > 0 && prec !== "" && subtotal !== 0) {
      var repetido = false;
      var co = 0;
      var co2 = 0;
      $("#grilladetalle tbody tr").each(function (index) {
        $(this)
          .children("td")
          .each(function (index2) {
            if (index2 === 0) {
              co = $(this).text();
              if (co === procod) {
                $("#grilladetalle tbody tr").each(function (index) {
                  $(this)
                    .children("td")
                    .each(function (index2) {
                      if (index2 === 2) {
                        co2 = $(this).text();
                        co2 = $(this).text();
                        co2 = co2.split("-");
                        co2 = co2[0].trim();
                        if (co2 === marcod) {
                          repetido = true;
                          $("#grilladetalle tbody tr")
                            .eq(index)
                            .each(function () {
                              $(this)
                                .find("td")
                                .each(function (i) {
                                  if (i === 2) {
                                    $(this).text(marca);
                                  }
                                  if (i === 3) {
                                    $(this).text(cant);
                                  }
                                  if (i === 4) {
                                    $(this).text(precio);
                                  }
                                  if (i === 5) {
                                    $(this).text(subtotal);
                                  }
                                });
                            });
                        }
                      }
                    });
                });
              }
            }
          });
      });
      if (!repetido) {
        $("#grilladetalle > tbody:last").append(
          '<tr class="ultimo"><td>' +
            procod +
            "</td><td>" +
            producto +
            "</td><td>" +
            marca +
            "</td><td>" +
            cant +
            "</td><td>" +
            prec +
            "</td><td>" +
            subtotal +
            '</td><td class="eliminar"><input type="button" value="Х" id="bf"   class="bf"  style="background:  pink; color: black;"/></td></tr>'
        );
      }
    } else {
      humane.log(
        "<span class='fa fa-info'></span> ATENCION!! Por favor complete todos los campos en la grilla",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
    calcularTotales();
    cargargrilla();
  });

  $(document).on("click", ".eliminar", function () {
    var parent = $(this).parent();
    $(parent).remove();
    cargargrilla();
    calcularTotales();
  });

  function cargargrilla() {
    var salida = "{";
    $("#grilladetalle tbody tr").each(function (index) {
      var campo1, campo2, campo3, campo4;
      salida = salida + "{";
      $(this)
        .children("td")
        .each(function (index2) {
          switch (index2) {
            case 0:
              campo1 = $(this).text();
              salida = salida + campo1 + ",";
              break;
            case 2:
              campo2 = $(this).text().split("-");
              campo2 = campo2[0].trim();
              salida = salida + campo2 + ",";
              break;
            case 3:
              campo3 = $(this).text();
              salida = salida + campo3 + ",";
              break;
            case 4:
              campo4 = $(this).text();
              salida = salida + campo4;
              break;
          }
        });
      if (index < $("#grilladetalle tbody tr").length - 1) {
        salida = salida + "},";
      } else {
        salida = salida + "}";
      }
    });
    salida = salida + "}";
    $("#detalle").val(salida);
  }

  //FUNCION INSERTAR
  $(document).on("click", "#grabar", function () {
    var nro, suc, usu, detalle, cli;
    nro = $("#nro").val();
    suc = $("#sucursal").val();
    cli = $("#cliente").val();
    usu = $("#usuario").val();
    console.log(suc);
    detalle = $("#detalle").val();

    if ((nro !== "", suc !== "", usu !== "", detalle !== "", cli > 0)) {
      $.ajax({
        type: "POST",
        url: "grabar.php",
        data: {
          codigo: 0,
          nro: nro,
          suc: suc,
          usu: usu,
          cli: cli,
          detalle: detalle,
          ope: 1,
        },
      }).done(function (msg) {
        mostrarMensajes(msg);
        vaciar();
        //solicitamos el ultimo codigo actual
        ultcod();
        refrescarDatos();
      });
    } else {
      humane.log(
        "<span class='fa fa-info'></span> Por favor complete todos los campos",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  });
  // FIN INSERTAR

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
        nro: 0,
        emp: 0,
        suc: 0,
        usu: 0,
        cli: 0,
        detalle: "{{1,1,1}}",
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
  $("#item").change(function () {
    if ($("#item").val() === 0) {
      $("#marca").val();
      $("#cantidad").val();
      $("#precio").val();
    } else {
      marca();
    }
  });

  $("#marca").change(function () {
    stock();
    precio();
  });

  function marca() {
    let marcas = document.getElementById("marca");
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
          // console.log(datos)

          for (const mar of datos) {
            const selectItem = document.createElement("OPTION");
            selectItem.setAttribute("value", mar["mar_cod"]);
            selectItem.textContent = `${mar.mar_cod} - ${mar.mar_desc}`;

            fragment.append(selectItem);
          }
          $("#marca").children("option").remove();

          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija una marca";

          marcas.insertBefore(opcion, marcas.children[0]);
          marcas.append(fragment);

          marcas.append(fragment);
          $("#marca").selectpicker("refresh");
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

          $("#marca").children("option").remove();
          let opcion = document.createElement("OPTION");
          opcion.setAttribute("value", 0);
          opcion.textContent = "Elija primero un item";

          marcas.insertBefore(opcion, marcas.children[0]);
          $("#marca").selectpicker("refresh");

          $("#precio").val("");
          $("#stock").val("");
        }
      });
    } else {
      $("#marca").children("option").remove();
      let opcion = document.createElement("OPTION");
      opcion.setAttribute("value", 0);
      opcion.textContent = "Elija primero un item";

      marcas.insertBefore(opcion, marcas.children[0]);
      $("#marca").selectpicker("refresh");
    }
  }

  function stock() {
    var item = $("#item").val();
    var mar = $("#marca").val();
    var suc = $("#sucursal").val();
    if (item > 0 && mar > 0) {
      $.ajax({
        type: "POST",
        url: "stock.php",
        data: { item: item, mar: mar, suc: suc },
      }).done(function (stock) {
        $("#stock").val(stock);
        $("#cantidad").focus();
      });
    }
  }

  function vaciar() {
    $("#cliente").val(0).trigger("change");
    $("#item").val(0).trigger("change");
    $("#marca").val(0).trigger("change");
    $("#stock").val("");
    $("#precio").val("");
    $("#cantidad").val("");
    $("#grilladetalle tbody tr").remove();
    $("#total").html("Total: 0.00 Gs.");
  }

  function ultcod() {
    $.ajax({
      type: "GET",
      url: "ultcod.php",
    }).done(function (msg) {
      $("#nro").val(msg);
    });
  }

  function precio() {
    var item = $("#item").val();
    var mar = $("#marca").val();
    if (item > 0 && mar > 0) {
      $.ajax({
        type: "POST",
        url: "precio.php",
        data: { item: item, mar: mar },
      }).done(function (precio) {
        $("#precio").val(precio);
        $("#cantidad").focus();
      });
    }
  }

  function calcularTotales() {
    var subtotal = 0;
    var total = 0;
    //recorremos todas las filas y buscamos la columna (columna es igual a td en html) numero 4
    $("#grilladetalle tbody tr")
      .find("td:eq(5)")
      .each(function () {
        //asignamos el valor de esa columna a la variable subtotal
        subtotal = $(this).text();
        //y le asignamos a la variable total el valor resultante de la suma detotal actual + subtotal
        total = parseInt(total) + parseInt(subtotal);
      });
    //y por ultimo mostramos el valor de total
    //en la fila con id total
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
