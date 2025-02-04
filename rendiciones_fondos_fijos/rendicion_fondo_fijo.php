<?php
require "../clases/sesion.php";
verifico();
require "../clases/conexion.php";
$con = new conexion();
$con->conectar();
$nro = pg_query("select coalesce(max(rendicion_fondo_fijo_cod),0) + 1 as nro from rendicion_fondo_fijo_cab;");
$nros = pg_fetch_assoc($nro);
date_default_timezone_set('America/Asuncion');
$fecha = date("d/m/Y H:i:s");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RENDICIONES FONDOS FIJOS</title>
    <link href="../gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../gentelella-master/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../gentelella-master/build/css/custom.min.css" rel="stylesheet">
    <link href="../css/flatty.css" rel="stylesheet">
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
                            <h1 class="page-header" style="color: #0e0e0e;">
                                RENDICIONES FONDOS FIJOS
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
                        <div class="col-md-12 ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>AGREGAR NUEVO</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12 ">
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <label>Nro.</label>
                                                <input class="form-control" type="text" id="nro" value="<?php echo $nros["nro"]; ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Fecha</label>
                                                <input class="form-control" type="text" id="fecha" value="<?php echo $fecha; ?>" disabled />
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
                                                <label>Asignacion Fondo Fijos</label>
                                                <select class="form-control selectpicker" data-size="7" data-live-search="true" id="asignaciones">
                                                    <option value="0">Seleccione la Asignacion</option>
                                                    <?php
                                                    $var = pg_query("select * from v_asignacion_fondo_fijos WHERE asignacion_estado <> 'ANULADO' order by asignacion_responsable_cod;");
                                                    while ($i = pg_fetch_assoc($var)) {
                                                        echo "<option value='" . $i["asignacion_responsable_cod"] . "'>" . $i["asignacion_responsable_cod"] . " - " . $i["caja_desc"] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Monto Asignacion</label>
                                                <input class="form-control" type="number" id="monto_asignacion" placeholder="Monto Asignacion" disabled/>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Saldo Asignacion</label>
                                                <input class="form-control" type="number" id="saldo_asignacion" placeholder="Saldo Asignacion" value="0" disabled />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Seleccionar Proveedor</label>
                                                <select class="form-control selectpicker" data-size="7" data-live-search="true" id="proveedor">
                                                    <option value="0">Seleccione el Proveedor</option>
                                                    <?php
                                                    $var = pg_query("select * from v_proveedores order by prov_cod;");
                                                    while ($i = pg_fetch_assoc($var)) {
                                                        echo "<option value='" . $i["prov_cod"] . "'>" . $i["prov_cod"] . " - " . $i["prov_nombre"] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Timbrado</label>
                                                <input type="number" class="form-control" placeholder="Numero de timbrado" id="timbrado">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Timbrado Vig Hasta</label>
                                                <input type="date" class="form-control" id="timbrado_vig">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Nro. Factura</label>
                                                <input type="text" class="form-control" placeholder="000-000-0000000" id="nrofact">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha Factura</label>
                                                <input type="date" class="form-control" placeholder="Numero de Factura" id="fechafact">
                                            </div>
                                        </div>

                                        <div class="col-md-3 ">
                                            <div class="form-group">
                                                <label class="control-label">Seleccionar Rubro</label>
                                                <select class="form-control selectpicker" data-live-search="true" data-size="5" id="rubro">
                                                    <option value="0">Elija una opcion</option>
                                                    <?php
                                                    $var = pg_query("select * from rubros order by rubro_cod");
                                                    while ($i = pg_fetch_assoc($var)) {
                                                        echo "<option value='" . $i["rubro_cod"] . "'>" . $i['rubro_cod'] . ' - ' . $i["rubro_desc"] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tipo Impuesto</label>
                                                <select  class="form-control selectpicker" data-live-search="true" data-size="4" id="tipoimpuesto">
                                                    <option value="0">Elija una opcion</option>
                                                <?php
                                                    $sql = pg_query("SELECT * FROM tipo_impuestos ORDER BY tipo_imp_cod");
                                                    while($i = pg_fetch_assoc($sql)){
                                                        echo "<option value='".$i['tipo_imp_cod']."'>" . $i['tipo_imp_cod'] .' - '. $i['tipo_imp_desc'] . "</option>";
                                                    }
                                                ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Monto</label>
                                                <input class="form-control" type="number" id="monto" placeholder="0 Gs."/>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="tipofact">Seleccionar Tipo de Factura</label>
                                                <select class="form-control selectpicker" id="tipofact" data-live-search="true" data-size="2">
                                                    <?php
                                                    $con = new conexion();
                                                    $con->conectar();
                                                    $sql = pg_query("SELECT * FROM tipo_facturas ORDER BY tipo_fact_cod");

                                                    while ($i = pg_fetch_assoc($sql)) {
                                                        echo "<option value='" . $i['tipo_fact_cod'] . "'>" . $i['tipo_fact_cod'] . ' - ' . $i['tipo_fact_desc'] . "</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="plazo">Plazo</label>
                                                <input type="number" class="form-control" value="0" id="plazo">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cuotas">Cuotas</label>
                                                <input type="number" class="form-control" value="0" required id="cuotas" disabled />
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="cuotas">Observacion</label>
                                                <input type="text" class="form-control" id="obs" placeholder="Observacion"/>
                                            </div>
                                        </div>

                                        <input type="hidden" id="detalle" value="">
                                        <input type="hidden" id="detalle2" value="">
                                        <input type="hidden" id="montoTotal" value="0">
                                        <input type="hidden" id="usuario" value="<?php echo $_SESSION["id"]; ?>">
                                        <input type="hidden" id="funcionario" value="<?php echo $_SESSION["fun_cod"]; ?>">
                                        <input type="hidden" id="empresa" value="<?php echo $_SESSION["emp_cod"]; ?>">
                                        <input type="hidden" id="sucursal" value="<?php echo $_SESSION["suc_cod"]; ?>">
                                        <input type="hidden" id="codigo" value="0"> <!-- Se calcula en el sp -->

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <input type="button" class=" btn btn-round btn-dark btn-block agregar " id="agregar" value="AGREGAR" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="table-responsive table-bordered">
                                            <table class="table table-responsive" id="grilladetalle">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">Código</th>
                                                        <th>Descripción</th>
                                                        <th style="text-align: center;">Monto</th>
                                                        <th style="text-align: center;">Tipo Impuesto</th>
                                                        <th style="text-align: right;">Grav. 10</th>
                                                        <th style="text-align: right;">Grav. 5%</th>
                                                        <th style="text-align: right;">Exenta</th>
                                                        <th style="text-align: right;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="5" class="text-right" id="total">SUBTOTAL: 0 Gs.</th>
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

                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Lista de Rendiciones Fondos Fijos</h2>
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
                                        <table class="table" id="tabla">
                                            <thead>
                                                <tr>
                                                    <th width="4%"></th>
                                                    <th width="5%">Código</th>
                                                    <th width="8%">Fecha Registro</th>
                                                    <th width="8%">Proveedor</th>
                                                    <th width="8%">Tipo Factura</th>
                                                    <th width="8%">Estado</th>
                                                    <th width="8%">Usuario</th>
                                                    <th width="8%">Acciones</th>
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
                                                <button type="button" class="btn btn-primary" id="delete" data-dismiss="modal">Si</button>
                                                <button type="button" class="btn btn-primary" id="hide" data-dismiss="modal">Cancelar</button>
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
    <script src="./rendiciones_fondos_fijos.js"></script>
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