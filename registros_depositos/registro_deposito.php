<?php

require "../clases/funciones.php";
require "../clases/sesion.php";
require "../clases/conexion.php";
verifico();
$con = new conexion();
$con->conectar();
$nro = pg_query("select coalesce(max(asignacion_responsable_cod)) + 1 as nro from asignacion_fondo_fijo");
$nros = pg_fetch_assoc($nro);

date_default_timezone_set('America/Asuncion');
$fecha = date('d/m/Y H:i:s')

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>REGISTRO DEPOSITOS</title>
    <link href="../gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../gentelella-master/vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="../gentelella-master/build/css/custom.min.css" rel="stylesheet">
    <link href="../css/dataTables.responsive.css" rel="stylesheet">
    <link href="../css/flatty.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
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
                            <h3 class="page-header" style="color:#000000">
                                <font face="arial,verdana">REGISTRO DEPOSITOS BANCARIOS
                            </h3>
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
                                    <h2>AGREGAR NUEVO </h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Fecha</label>
                                            <input class="form-control" type="text" value="<?php echo $fecha; ?>" disabled />
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
                                            <input class="form-control" type="text" class="form-control" value="<?php echo $_SESSION["suc_nom"]; ?>" disabled>
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
                                            <label>Aperturas Cierres</label>
                                            <select class="form-control selectpicker" data-live-search="true" id="aperturas_cierres">
                                                <option value="0">Seleccione un cierre</option>
                                                <?php
                                                $cli = pg_query("SELECT * from v_aperturas_cierres where fecha_cierreformato is not null and (monto_efectivo + monto_cheque) > 0 order by aper_cier_cod desc;");
                                                while ($c = pg_fetch_assoc($cli)) {
                                                    echo "<option value='" . $c["aper_cier_cod"] . "'>" . $c["aper_cier_cod"] . ' - ' . $c["fecha_cierreformato"] . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Recaudacion a Depositar</label>
                                            <input class="form-control" type="number" id="recaudaciones_depositar" value="" placeholder="Recaudaciones a depositar cod" disabled />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Monto Efectivo</label>
                                            <input class="form-control" type="number" id="monto_efectivo" value="" placeholder="Monto Efectivo" disabled />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Monto Cheque</label>
                                            <input class="form-control" type="number" id="monto_cheque" value="" placeholder="Monto Cheque" disabled />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Monto Total a Depositar</label>
                                            <input class="form-control" type="number" id="monto_depositar" value="" placeholder="Monto a Depositar" disabled />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Seleccionar Entidad</label>
                                            <select class="form-control selectpicker" data-live-search="true" id="entidades">
                                                <option value="0">Seleccione Entidad</option>
                                                <?php
                                                $cli = pg_query("select * from entidades_emisoras order by 1;");
                                                while ($c = pg_fetch_assoc($cli)) {
                                                    echo "<option value='" . $c["ent_cod"] . "'>" . $c["ent_nom"] . " </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Cuenta Corriente</label>
                                            <select class="form-control selectpicker" data-live-search="true" id="cuentas_corrientes">
                                                <option value="0L">Seleccione Primero una entidad</option>
                                            </select>
                                        </div>
                                    </div>

                                    <input type="hidden" id="funcionario" value="<?php echo $_SESSION["fun_cod"]; ?>">
                                    <input type="hidden" id="usuario" value="<?php echo $_SESSION["id"]; ?>">
                                    <input type="hidden" id="empresa" value="<?php echo $_SESSION["emp_cod"]; ?>">
                                    <input type="hidden" id="sucursal" value="<?php echo $_SESSION["suc_cod"]; ?>">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label></label>
                                            <div class="controls">
                                                <button type="submit" class="btn btn-round btn-dark btn-block" id="btnsave">Guardar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Lista de Registros de Depositos</h2>
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
                                <div class="x_content">
                                    <!-- start project list -->
                                    <table class="table table-striped projects" id="reclamos">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Movimiento N°</th>
                                                <th>Fecha Deposito</th>
                                                <th>Entidad</th>
                                                <th>Cuenta corriente</th>
                                                <th>Monto Depositado</th>
                                                <th>Sucursal</th>
                                                <th>Usuario</th>
                                                <th>Estado</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                    </table>
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
                                                        <button type="button" class="btn btn-primary" value="" id="delete">Si</button>
                                                        <button type="button" class="btn btn-danger" id="cerrar2" data-dismiss="modal">Cancelar</button>
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
        </div>
    </div>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="./registros_depositos.js"></script>
    <script src="../js/jquery.dataTables.js"></script>
    <script src="../js/fnReloadAjax.js"></script>
    <script src="../js/dataTables.bootstrap.js"></script>
    <script src="../js/humane.js"></script>
    <script src="../js/bootstrap-select.js"></script>
    <script src="../gentelella-master/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../gentelella-master/build/js/custom.min.js"></script>
</body>

</html>