$(function () {
  var Path = "imp_compras.php";
  var compras = $("#compras").dataTable({
    columns: [
      {
        class: "details-control",
        orderable: false,
        data: null,
        defaultContent: "<a><span class='fa fa-plus'></span></a>",
      },
      { data: "cod" },
      { data: "nro" },
      { data: "nro_factura" },
      { data: "fecha" },
      { data: "proveedor" },
      { data: "ruc" },
      { data: "tipo_factura" },
      { data: "plazo" },
      { data: "cuotas" },
      { data: "estado" },
      { data: "acciones" },
    ],
  });

  compras.fnReloadAjax("datos.php");

  var detailRows = [];

  $("#compras tbody").on("click", "tr td.details-control", function () {
    var tr = $(this).closest("tr");
    var row = $("#compras").DataTable().row(tr);
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
  compras.on("draw", function () {
    $.each(detailRows, function (i, cod) {
      $("#" + cod + " td.details-control").trigger("click");
    });
  });
  //TABLA DETALLE
  function format(d) {
    // `d` is the original data object for the row
    var deta =
      '<table  class="table table-striped table-bordered nowrap table-hover">\n\
    <tr width=90px class="info"><th>Codigo</th><th>Descripcion</th><th>Cantidad</th><th>Costo</th><th>Precio</th><th>Subtotal</th></tr>';
    var total = 0;
    var subtotal = 0;
    for (var x = 0; x < d.detalle.length; x++) {
      subtotal = d.detalle[x].cantidad * d.detalle[x].costo;
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
        d.detalle[x].cantidad +
        "</td>" +
        "<td width=50px>" +
        d.detalle[x].costo +
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
      "<td></td>" + //FILAS ==> <td>
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
      '" target="_blank" class="btn btn-sm btn-info btn-block" id="print" ><span class="fa fa-print"></span><b> Imprimir</b></a>' +
      "</div>" +
      "</div>"
    );
  }

  // INSERTAR GRILLA
  $(document).on("click", ".agregar", function () {
    $("#detalle-grilla").css({ display: "block" });
    var producto = $("#item option:selected").html();
    var marca = $("#marcas option:selected").html();

    var procod = $("#item").val();
    var marcod = $("#marcas").val();
    var cant = $("#cantidad").val();
    var cost = $("#costo").val();
    var prec = $("#precio").val();
    var depcod = $("#deposito").val();
    var deposito = $("#deposito option:selected").html();
    var depfinal = depcod + ". " + deposito;
    prec = prec.replace(" Gs.", "");
    var subtotal = cant * prec;
    if (
      procod > 0 &&
      producto !== "" &&
      cant > 0 &&
      cost > 0 &&
      prec > 0 &&
      depcod > 0 &&
      subtotal !== 0
    ) {
      var repetido = false;
      var co = 0;
      var co2 = 0;
      let fila;
      let bandera = true;
      $("#grilladetalle tbody tr").each(function (fila1) {
        if (bandera) {
          fila = fila1;
          //   alert('fila: ' + fila)
          $(this)
            .children("td")
            .each(function (col1) {
              if (col1 == 0) {
                co = $(this).text();
                // alert(`co = ${co} y el procod = ${procod}`)
                if (co == procod) {
                  //       alert('coincide el producto')
                  $("#grilladetalle tbody tr:eq(" + fila + ")")
                    .children("td")
                    .each(function (col2) {
                      if (col2 == 2) {
                        co2 = $(this).text();
                        co2 = co2.split("-");
                        co2 = co2[0].trim();
                        if (co2 == marcod && co == procod) {
                          //    alert('coincide la marca tambien')
                          repetido = true;
                          $("#grilladetalle tbody tr:eq(" + fila + ")")
                            .children("td")
                            .each(function (i) {
                              //  alert('fila a modificar ' + fila + 'columna ' + i )
                              if (i === 2) {
                                $(this).text(marca);
                              }
                              if (i === 3) {
                                $(this).text(cant);
                              }
                              if (i === 4) {
                                $(this).text(prec);
                              }
                              if (i === 5) {
                                $(this).text(subtotal);
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
            cost +
            "</td><td>" +
            prec +
            "</td><td>" +
            subtotal +
            "</td><td>" +
            depfinal +
            '</td><td class="eliminar"><input type="button" value="Х" id="bf"   class="bf"  style="background:  pink; color: black;"/></td></tr>'
        );
      }
      cargargrilla();
      calcularTotales();
      $("#cantidad").val("");
    } else if (cant < 0 || cant == "") {
      humane.log(
        "<span class='fa fa-info'></span> ATENCION!! La cantidad no puede ser negativo",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    } else {
      humane.log(
        "<span class='fa fa-info'></span> ATENCION!! Por favor complete todos los campos en la grilla",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    }
  });

  function cargargrilla() {
    // alert('cargargrilla')
    detalle = "{";
    $("#grilladetalle tbody tr").each(function (index) {
      var campo1, campo2, campo3, campo4, campo5, campo6;
      detalle = detalle + "{";
      //y recorremos todos los hijos inmediatos "td" del objeto "this" (en este caso "this" hace referencia a la fila(tr))
      $(this)
        .children("td")
        .each(function (index2) {
          switch (index2) {
            case 0: //procod
              campo1 = $(this).text();
              detalle = detalle + campo1 + ",";
              break;
            case 2: //marca
              campo2 = $(this).text().split("-");
              campo2 = campo2[0].trim();
              detalle = detalle + campo2 + ",";
              break;
            case 3: //cant
              campo3 = $(this).text();
              detalle = detalle + campo3 + ",";
              break;
            case 4: //costo
              if ($("#orden").val() > 0) {
                campo4 = $("#costoid" + campo1 + campo2).val();
                if(campo4 <= 0){
                  humane.log(
                    "<span class='fa fa-info'></span>  DEBES CARGAR UN COSTO VALIDO A CADA PRODUCTO ",
                    { timeout: 6000, clickToClose: true, addnCls: "humane-flatty-error" }
                  );
                  return
                }else{
                  detalle = detalle + campo4 + ",";
                }
              } else {
                campo4 = $(this).text();
                detalle = detalle + campo4 + ",";
              }
              break;
            case 5: //precio
              if ($("#orden").val() > 0) {
                campo5 = $("#precioid" + campo1 + campo2).val();
                detalle = detalle + campo5 + ",";
              } else {
                campo5 = $(this).text();
                detalle = detalle + campo5 + ",";
              }
              break;
            case 7:
              // dep en el caso de que sea por orden
              if ($("#orden").val() > 0) {
                campo6 = $("#ordenid" + campo1).val();
                detalle = detalle + campo6;
              } else {
                campo6 = $(this).text();
                campo6 = campo6.split(".");
                campo6 = campo6[0].trim();
                detalle = detalle + campo6;
              }
              break;
          }
        });
      if (index < $("#grilladetalle tbody tr").length - 1) {
        detalle = detalle + "},";
      } else {
        detalle = detalle + "}";
      }
    });
    detalle = detalle + "}";
    $("#detalle").val(detalle);
  }
  

  $(document).on("click", ".eliminar", function () {
    var parent = $(this).parent();
    $(parent).remove();
    cargargrilla();
    calcularTotales();
  });

  //FUNCION INSERTAR
  $(document).on("click", "#grabar", function () {
    //declaramos las variables que vamos a enviar a nuestro SP
    var suc = $("#sucursal").val();
    var usu = $("#usuario").val();
    var proveedor = $("#proveedor").val();
    var prov_tim_vig = $("#timbrado_vig").val();
    var tipo_factura = $("#tipo_factura").val();
    var plazo = $("#plazo").val();
    var cuotas = $("#cuotas").val();
    var fechafact = $("#fechafact").val();
    var timbrado = $("#timbrado").val();
    var nrofact = $("#nrofact").val();
    var depcod = $("#deposito").val();

    if ($("#orden").val() > 0) {
      cargargrilla();
    }

    debugger;

    var detalle = $("#detalle").val();
    // alert(`${proveedor} ${timbrado} ${nrofact} ${fechafact} ${tipo_factura} ${plazo} ${cuotas} ${suc} ${usu} `)

    if (
      detalle !== "" &&
      proveedor !== "" &&
      timbrado !== "" &&
      fechafact !== "" &&
      prov_tim_vig !== "" &&
      nrofact !== ""
    ) {
      if ($("#fechafact").val() > $("#timbrado_vig").val()) {
        humane.log(
          "<span class='fa fa-info'></span>  LA FECHA DE FACTURA NO PUEDE SER MAYOR A LA FECHA DE VENCIMIENTO DEL TIMBRADO ",
          { timeout: 6000, clickToClose: true, addnCls: "humane-flatty-error" }
        );
      } else {
        // ACTUALIZAR EL ESTADO DE LA ORDEN
        let ordencod = $("#orden").val();
        let compcod = $("#nro").val();

        $.ajax({
          type: "POST",
          url: "grabar.php",
          data: {
            cod: 0,
            proveedor: proveedor,
            timbrado: timbrado,
            timvighasta: prov_tim_vig,
            nrofact: nrofact,
            fechafact: fechafact,
            tipo_factura: tipo_factura,
            plazo: plazo,
            cuotas: cuotas,
            depcod: depcod,
            suc: suc,
            usu: usu,
            detalle: detalle,
            ope: 1,
          },
        }).done(function (msg) {
          var r = msg.split("_/_");
          var tipo = r[1];
          if (tipo.trim() == "notice") {
            $.ajax({
              type: "POST",
              url: "actualizar_ordcompra.php",
              data: { compcod: compcod, ordencod: ordencod },
            });
            let opciones = document.getElementById("orden").childNodes;
            opciones.forEach((opcion) => {
              if (opcion.tagName === "OPCION") {
                if (opcion.value == ordencod) {
                  //  alert(`holaaa ${ordencod}`)
                }
              }
            });
          }
          $("#orden").selectpicker("refresh");

          mostrarMensajes(msg);
          limpiarCompraPorOrden();
          vaciar();
          ultcod();
          refrescarDatos();
        });
      }
    } else if (proveedor === "") {
      humane.log(
        "<span class='fa fa-info'></span>Selecciona un proveedor. Por favor",
        { timeout: 4000, clickToClose: true, addnCls: "humane-flatty-warning" }
      );
    } else if (detalle === "") {
      humane.log("<span class='fa fa-info'></span> Debe agregar detalle", {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-warning",
      });
    } else {
      humane.log("<span class='fa fa-info'></span> Verifique los campos", {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-warning",
      });
    }
  });

  ///FUNCION ANULAR
  $(document).on("click", ".delete", function () {
    var pos = $(".delete").index(this);
    var cod = $("#compras tbody tr:eq(" + pos + ")")
      .find("td:eq(1)")
      .html();

    $("#delete").val(cod);
    $(".msg").html(
      '<h4 class="modal-title" id="myModalLabel">Desea eliminar el Registro Nro. ' +
        cod +
        "?</h4>"
    );
  });
  //esta parte es para que al hacer clic pueda anular
  $(document).on("click", "#delete", function () {
    var cod = $("#delete").val();

    var suc = $("#sucursal").val();
    $.ajax({
      type: "POST",
      url: "grabar.php",
      data: {
        cod: cod,
        proveedor: 0,
        timbrado: 0,
        timvighasta: "1/1/1111",
        nrofact: 0,
        suc: suc,
        usu: 0,
        tipo_factura: 0,
        plazo: 0,
        cuotas: 0,
        fechafact: "1/1/1111",
        depcod: 0,
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

  $("#item").change(function () {
    precio();
    stock();
    marca();
  });

  $("#orden comprasfact").blur(function () {
    if ($("#orden comprasfact").val() > $("#timbrado_vig").val()) {
      humane.log(
        "<span class='fa fa-info'></span>  LA FECHA DE FACTURA NO PUEDE SER MAYOR A LA FECHA DE VENCIMIENTO DEL TIMBRADO ",
        { timeout: 6000, clickToClose: true, addnCls: "humane-flatty-error" }
      );
    }
  });

  // Insert detalle desde la tabla orden_compras_det
  $("#orden").change(function () {
    var ordenId = $(this).val();
    if (ordenId > 0) {
      cuota = [];
      $.ajax({
        url: "orden.php",
        type: "POST",
        data: { orden: ordenId },
      }).done(function (msg) {
        datos = JSON.parse(msg);
        $("#grilladetalle > tbody > tr").remove();
        $("#grilladetalle > tbody:last").append(datos.filas);
        $("#total").html("<strong>" + datos.total + " Gs.</strong>");

        $("#item").attr("disabled", true);
        $("#marca").attr("disabled", true);
        $("#cantidad").attr("disabled", true);
        $("#deposito").attr("disabled", true);
      });
      setTimeout(function () {
        traerOrdenCab(ordenId);
      }, 300);
    } else {
      $("#item").removeAttr("disabled", true);
      $("#cantidad").removeAttr("disabled", true);
      $("#marcas").removeAttr("disabled", true);
      $("#grilladetalle > tbody > tr").remove();
      $("#total").html("Total: 0.00 Gs.");
    }
  });

  function limpiarCompraPorOrden() {
    $("#proveedor").removeAttr("disabled", true);
    $("#proveedor").val(0).trigger("change");
    $("#orden").val(0).trigger("change");
    $("#item").removeAttr("disabled", true);
    $("#cantidad").removeAttr("disabled", true);
    $("#marcas").removeAttr("disabled", true);
    $("#deposito").removeAttr("disabled", true);
    $("#total").html("Total: 0.00 Gs.");
  }

  function traerOrdenCab(ordenid) {
    var ordenid = ordenid;
    $.ajax({
      type: "POST",
      url: "ordencab.php",
      data: { orden: ordenid },
    }).done(function (data) {
      // console.log(data)
      if (data.trim() != "error") {
        var datos = JSON.parse(data);
        // console.log(datos)
        $("#proveedor").val(datos.prov_cod).trigger("change");
        $("#proveedor").attr("disabled", true);
        $("#tipo_factura").val(datos.tipo_fact_cod).trigger("change");
        $("#plazo").val(datos.orden_plazo).trigger("change");
        $("#cuotas").val(datos.orden_cuotas).trigger("change");
      }
    });
  }
  // fin  Insert detalle desde la tabla pedidos_compras_det

  $("#tipo_factura").bind("change", function (event, ui) {
    var tipo = $("#tipo_factura").val();
    if (tipo === "1") {
      //CONTADO
      $("#tipo").attr("style", "display:none");
      $("#cuo").attr("style", "display:none");
      $("#cuotas").val(0);
      $("#pla").attr("style", "display:none");
      $("#plazo").val(0);
      $("#cant").attr("style", "display:compact");
    } else {
      ///0 CREDITO
      $("#tipo").attr("style", "display:compact");
      $("#pla").attr("style", "display:compact");
      $("#cuo").attr("style", "display:compact");
    }
  });

  function vaciar() {
    $("#proveedor").val(0).trigger("change");
    $("#timbrado").val("");
    $("#timbrado_vig").val("");
    $("#nrofact").val("");
    $("#fechafact").val("");
    $("#orden").val(0).trigger("change");
    $("#item").val(0).trigger("change");
    $("#marcas").val(0).trigger("change");
    $("#costo").val("");
    $("#precio").val("");
    $("#stock").val("");
    $("#deposito").val(0).trigger("change");
    $("#cantidad").val("");
    $("#tipo_factura").val("");
    $("#plazo").val("");
    $("#cuotas").val("");
    $("detalle").val("");

    $("#grilladetalle tbody tr").remove();
    $("#total").html("Total: 0.00 Gs.");
  }

  function ultcod() {
    $.ajax({
      type: "POST",
      url: "ultcod.php",
    }).done(function (ultcod) {
      $("#nro").val(ultcod);
    });
  }

  $("#marcas").change(function () {
    stock();
    precio();
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
    var suc = $("#sucursal").val();
    if (item > 0 && mar > 0) {
      $.ajax({
        type: "POST",
        url: "stock.php",
        data: { item: item, mar: mar, suc: suc },
      }).done(function (stock) {
        $("#stock").val(stock);
        $("#deposito").focus();
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
      });
    }
  }

  function calcularTotales() {
    var subtotal = 0;
    var total = 0;
    //recorremos todas las filas y buscamos la columna (columna es igual a td en html) numero 4
    $("#grilladetalle tbody tr")
      .find("td:eq(6)")
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

      var msgFinal = texto.split('CONTEXT:');
      msgFinal = msgFinal[0];
      humane.log("<span class='fa fa-info'></span> " + msgFinal, {
        timeout: 4000,
        clickToClose: true,
        addnCls: "humane-flatty-error",
      });
    }
  }

  function refrescarDatos() {
    compras.fnReloadAjax();
  }

  $(function () {
    $(".chosen-select").chosen({ width: "100%" });
  });
});
