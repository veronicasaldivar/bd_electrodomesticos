<?php
require "../clases/sesion.php";
verifico();
require "../clases/conexion.php";
$con = new conexion();
$con->conectar();
$nro = pg_query("select coalesce(max(orden_nro),0)+1 as nro from ordcompras_cab;");
$nros = pg_fetch_assoc($nro);
//$fecha = date("d/m/Y H:h:m");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ORDEN COMPRA</title>
    <link href="../gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../gentelella-master/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../gentelella-master/build/css/custom.min.css" rel="stylesheet">
    <link href="../css/flatty.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap-select.css">
    <link rel="stylesheet" type="text/css" href="../css/chosenselect.css" media="screen">
    <link rel="stylesheet" href="../css/bootstrap-select.css">
    <style>
        body {
            background: url(../imagenes/fon1.jpg) no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?php require '../controles/menu_cabecera.php' ?>
            <div class="right_col">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h1 class="page-header" style="color: #000;">
                                Ordenes de Compras
                            </h1>
                        </div>
                        <div class="title_right">
                            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Go!</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>AGREGAR NUEVO</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">

                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>Cod.</label>
                                                <input class="form-control" type="text" id="nro" value="<?php echo $nros["nro"]; ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Fecha</label>
                                                <?php
                                                date_default_timezone_set('America/Asuncion');
                                                ?>
                                                <input class="form-control" type="text" id="fecha" value="<?php echo date('d/m/Y H:i:s'); ?>" disabled />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Empresa</label>
                                                <input class="form-control" type="text" value="<?php echo $_SESSION["emp_nom"]; ?>" disabled />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Sucursal</label>
                                                <input class="form-control" type="text" value="<?php echo $_SESSION["suc_nom"]; ?>" disabled />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Funcionario</label>
                                                <input class="form-control" type="text" value="<?php echo $_SESSION["fun_nom"] ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Seleccionar Proveedor</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-size="3" id="proveedor">
                                                    <option value="0">Elija una Opcion</option>
                                                    <?php
                                                    $var = pg_query("select * from v_proveedores order by prov_cod;");
                                                    while ($i = pg_fetch_assoc($var)) {
                                                        echo "<option value='" . $i["prov_cod"] . "'>" . $i["prov_cod"] . " - " . $i["prov_nombre"] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Presupuestos N°</label>
                                                <select id="presupuesto" class="form-control selectpicker" data-live-search="true" data-size="4">
                                                    <option value="0">Elija una opcion</option>
                                                    <?php
                                                    $con = new conexion();
                                                    $con->conectar();
                                                    $sql = pg_query("SELECT * FROM v_presupuestos_proveedores_cab WHERE pre_prov_estado = 'PENDIENTE'  order by pre_prov_cod ");
                                                    while ($i = pg_fetch_assoc($sql)) {
                                                        echo "<option value='" . $i["pre_prov_cod"] .'_/_'. $i["prov_cod"].'_/_'. $i["pre_prov_fecha"] ."'>" . $i["pre_prov_cod"] . ' - ' . $i["prov_nombre"] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Seleccionar Item</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-size="5" id="item">
                                                    <option value="0">Elija una Opcion</option>
                                                    <?php
                                                    $var = pg_query("select * from v_items where tipo_item_cod = 1 order by 1");
                                                    while ($i = pg_fetch_assoc($var)) {
                                                        echo "<option value='" . $i["item_cod"] . "'>" . $i["item_cod"] . " - " . $i["item_desc"] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Marca</label>
                                                <select id="marcas" class="selectpicker form-control" data-live-search="true" data-size="5">
                                                    <option value="0">Elija primero un item</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!--<div class="form-group">-->
                                        <!-- <div class="col-sm-2">
                                                <div class="form-group"> 
                                                    <label  class="control-label">Seleccionar Tipo de Factura</label>
                                                    <select class="form-control selectpicker" data-live-search="true" data-size="5" id="tipo_factura" >
                                                      
                                                        <?php
                                                        $con = new conexion();
                                                        $con->conectar();
                                                        $tipo = pg_query("select * from tipo_facturas order by 1");
                                                        while ($i = pg_fetch_assoc($tipo)) {
                                                            echo "<option value='" . $i["tipo_fact_cod"] . "' >" . $i["tipo_fact_desc"] . "</option>";
                                                        }
                                                        ?>>
                                                    </select>
                                                </div>
                                            </div>     -->

                                        <div class="col-md-2" id="pla" style="display:none">
                                            <div class="form-group">
                                                <label>Plazo</label>
                                                <input class="form-control" value="0" type="number" id="plazo" />
                                            </div>
                                        </div>
                                        <div class="col-md-2" id="cuo" style="display:none">
                                            <div class="form-group">
                                                <label>Cuotas</label>
                                                <input class="form-control" value="0" type="number" id="cuotas" />
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Costo</label>
                                                <input class="form-control" type="text" id="precio" placeholder="0 Gs." readonly="" />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Cant. Stock</label>
                                                <input class="form-control" type="text" id="stock" placeholder="0" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Cantidad</label>
                                                <input class="form-control" type="number" id="cantidad" placeholder="Cantidad" />
                                            </div>
                                        </div>
                                        <input type="hidden" id="detalle" value="">
                                        <input type="hidden" id="usuario" value="<?php echo $_SESSION["id"]; ?>">
                                        <input type="hidden" id="funcionario" value="<?php echo $_SESSION["fun_cod"]; ?>">
                                        <input type="hidden" id="empresa" value="<?php echo $_SESSION["emp_cod"]; ?>">
                                        <input type="hidden" id="sucursal" value="<?php echo $_SESSION["suc_cod"]; ?>">

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <input type="button" class="btn btn-round btn-dark btn-block agregar" id="agregar" value="AGREGAR" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive table-bordered">
                                                <table class="table table-responsive" id="grilladetalle">
                                                    <thead>
                                                        <tr>
                                                            <th>Código</th>
                                                            <th>Descripcion</th>
                                                            <th>Marca</th>
                                                            <th>Cantidad</th>
                                                            <th>Costo</th>
                                                            <th>SubTotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="6" class="text-right" id="total">Total: 0.00 Gs.</th>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="span20">
                                            <br>
                                            <input type="submit" class="btn btn-round btn-dark btn-block span20 grabar" id="grabar" value="GUARDAR" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Lista de Ordenes de Compras</h2>
                                    <ul class="nav navbar-right panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <div class="dataTable_wrapper">
                                        <table class="table" id="ordencompras">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Código</th>
                                                    <th>Nro</th>
                                                    <th>Fecha</th>
                                                    <th>Proveedor</th>
                                                    <th>Ruc</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal" id="confirmacion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <label class="msg"></label>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-5 col-md-offset-4">
                                                <button type="button" class="btn btn-primary" id="delete">Si</button>
                                                <button type="button" class="btn btn-danger" id="hide" data-dismiss="modal">Cancelar</button>
                                                <input type="hidden" id="cod_eliminar" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/jquery.js"></script>
    <script src="ordencompras.js"></script>
    <script src="../js/jquery.dataTables.js"></script>
    <script src="../js/fnReloadAjax.js"></script>
    <script src="../js/dataTables.bootstrap.js"></script>
    <script src="../js/humane.js"></script>
    <script src="../js/bootstrap-select.js"></script>
    <script src="../gentelella-master/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../gentelella-master/build/js/custom.min.js"></script>
    <script src="../js/chosenselect.js"></script>
</body>

</html>