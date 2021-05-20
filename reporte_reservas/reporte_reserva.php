<?php

require "../clases/funciones.php";
require "../clases/sesion.php";
require "../clases/conexion.php";
verifico();
$fecha = date("Y/m/d")
?>
<html>
<head>
    <title>REPORTE RESERVA</title>
    <link href="../css/application.min.css" rel="stylesheet" />
    <link rel="shortcut icon" href="../img/favicon.jpg" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Ejemplo" />
    <meta name="author" content="Hector Oviedo nosis_r8@hotmail.com" />
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <style>
        td.details-control {
            background: url('../img/open.png') no-repeat center center;
            background-size: 22px 22px;
            cursor: pointer;
        }
        tr.details td.details-control {
            background: url('../img/close.png') no-repeat center center;
            background-size: 20px 20px;
        }
    </style>
    <style >
            body{
              background: url(../imagenes/1_1.jpg) no-repeat center center fixed;
              background-size: cover;
              -webkit-background-size: cover;
              -moz-background-size:cover;
            }
          </style>
</head>
<body background="camp.jpg">


<!-- Inicio Barra de menu -->
<?php
//include "../controles/menubar.php";
?>
<!--Fin Barra de menu -->
<!-- Inicio del body -->
<!--<div class="item"><img  src="camp.jpg"  /></div>-->
<div class="wrap">
    <!--Inicio de la Cabecera -->
    <?php
   // include "../controles/header.php";
    ?>
    <!--Fin de la cabecera -->
    <!--Inicio del contenido -->
    <div class="content container-fluid">
        <!--Inicio Titulo del Contenido -->
        <div class="row-fluid">
                            <div class="btn-group">
                                    <a href="../reservas/reserva.php"  class="btn btn-info btn-sm fa fa-print" ><i class='icon-arrow-left'></i>
                                       VOLVER
                                    </a>
           </div>



            <div class="span12">
                <h2 class="page-title">Reporte <small>  <small>Reserva</small></h2>
            </div>

        </div>
        <!--Fin Titulo Contenido -->
        <!--Inicio de la pagina contenido -->
        <div class="row-fluid">
            <div class="span12">
                <section class="widget">
                    <header>
                        <h4>
                            <i class="icon-align-left"></i>
                            Generar Reporte de Reservas
                        </h4>
                    </header>
                    <div class="body">
                        <fieldset>
                            <div class="well">
                                <div class="row-fluid non-responsive">
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="btn-enabled-date" class="control-label">Desde la fecha</label>
                                            <div class="controls controls-row">
                                                <div class="input-append span12">
                                                    <input id="btn-enabled-date-desde" class="span10" type="text" name="btn-enabled-date" value="" />
                                                    <a href="#" id="btn-select-calendar-desde" class="btn btn-info   " data-date-format="yyyy/mm/dd" data-date="<?php echo $fecha;?>">
                                                        <i class="icon-calendar"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="btn-enabled-date" class="control-label">Hasta la fecha</label>
                                            <div class="controls controls-row">
                                                <div class="input-append span12">
                                                    <input id="btn-enabled-date-hasta" class="span10" type="text" name="btn-enabled-date" value="" />
                                                    <a href="#" id="btn-select-calendar-hasta" class="btn btn-info" data-date-format="yyyy/mm/dd" data-date="<?php echo $fecha;?>">
                                                        <i class="icon-calendar"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="country" class="control-label">
                                                Seleccionar Cliente
                                            </label>
                                            <div class="controls">
                                                <select id="cliente" required="required" class="select-block-level chzn-select" tabindex="-1">
                                                    <option value="0">Todos</option>
                                                    <?php
                                                    $con = new conexion();
                                                    $con ->conectar();
                                                    $cli = pg_query("select * from v_clientes order by 1;");
                                                    while ($c = pg_fetch_assoc($cli)){
                                                        echo "<option value='".$c["cli_cod"]."'>".$c["cli_nom"]. " " . $c["cli_ape"]."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label for="country" class="control-label">
                                                Seleccionar Funcionarios
                                            </label>
                                            <div class="controls">
                                                <select id="funcionario" required="required" class="select-block-level chzn-select" tabindex="-1">
                                                    <option value="0">Todos</option>
                                                    <?php
                                                    $fun = pg_query('select * from v_perso where tipo_pers_desc=\'FUNCIONARIO\' order by 1');
                                                    while ($f = pg_fetch_assoc($fun)){
                                                        echo "<option value='".$f["per_cod"]."'>".$f["per_nom"]." ". $f["per_ape"]."</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button id="buscar" class="btn btn-primary span12 buscar">  Buscar   <i class=' icon-spinner'></i></button>
                                
                            </div>
                        </fieldset>
                    </div>
                </section>
            </div>
        </div>
        <div class="row-fluid" id="tabla-reporte" style="display: none;">
            <div class="span12">
                <section class="widget">
                    <header>
                        <h4>
                            <i class="icon-file-alt"></i>
                            Lista de Reservas de Turnos
                        </h4>
                    </header>
                    <div class="body background-dark">
                        <table style="color: #ffffff;" id="pedido" class="table text-align-center">
                            <th class="span1"></th>
                                <th class="span2">Cod.</th>
                                
                                <th class="span2">Fecha</th>
                                <th class="span2">Cliente</th>
                                <th class="span2">Ruc</th>
                                <th class="span2">Estado</th>
                                <th class="span2">Total</th>
                                <th class="span1">Imprimir</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <br>
                    </div>
                </section>
            </div>
        </div>
        <!--Fin de la pagina de contenido -->
    </div>
    <!--Fin del Contenido -->
</div>
<!--Fin del body -->

<!-- jquery and friends -->
<script src="../lib/jquery/jquery-1.11.2.min.js"> </script>
<script src="../lib/jquery/jquery-migrate-1.1.0.min.js"> </script>
<!-- jquery plugins -->
<script src="../lib/jquery-maskedinput/jquery.maskedinput.js"></script>
<script src="../lib/parsley/parsley.js"> </script>
<script src="../lib/uniform/js/jquery.uniform.js"></script>
<script src="../lib/select2.js"></script>
<script src="../lib/jquery.dataTables.min.js"></script>
<script src="../js/fnReloadAjax.js"></script>
<script src="../js/tabla_pedido_reporte_1.js"></script>
<!--backbone and friends -->
<script src="../lib/backbone/underscore-min.js"></script>
<script src="../lib/backbone/backbone-min.js"></script>
<script src="../lib/backbone/backbone-pageable.js"></script>
<script src="../lib/backgrid/backgrid.js"></script>
<script src="../lib/backgrid/backgrid-paginator.js"></script>
<!-- bootstrap default plugins -->
<script src="../js/bootstrap/bootstrap-transition.js"></script>
<script src="../js/bootstrap/bootstrap-collapse.js"></script>
<script src="../js/bootstrap/bootstrap-alert.js"></script>
<script src="../js/bootstrap/bootstrap-tooltip.js"></script>
<script src="../js/bootstrap/bootstrap-popover.js"></script>
<script src="../js/bootstrap/bootstrap-button.js"></script>
<script src="../js/bootstrap/bootstrap-dropdown.js"></script>
<script src="../js/bootstrap/bootstrap-modal.js"></script>
<script src="../js/bootstrap/bootstrap-tab.js"> </script>
<!-- bootstrap custom plugins -->
<script src="../lib/bootstrap-datepicker.js"></script>
<script src="../lib/bootstrap-select/bootstrap-select.js"></script>
<script src="../lib/wysihtml5/wysihtml5-0.3.0_rc2.js"></script>
<script src="../lib/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
<script src="../lib/bootstrap-switch.js"></script>
<script src="../lib/bootstrap-colorpicker.js"></script>
<!-- page specific -->
<script src="../js/forms-elemets.js"></script>
<script>
    $('#report').addClass('active');
    $('#estado').addClass('active');
    $('#components-collapse').addClass('in');
    $(function() {
        $('.date-picker').datepicker({
            autoclose: true
        });

        var $btnCalendardesde = $('#btn-select-calendar-desde');
        $btnCalendardesde.datepicker({
            autoclose: true
        }).on('changeDate', function(ev){
            $('#btn-enabled-date-desde').val($btnCalendardesde.data('date'));
            $btnCalendardesde.datepicker('hide');
        });

        var $btnCalendarhasta = $('#btn-select-calendar-hasta');
        $btnCalendarhasta.datepicker({
            autoclose: true,
            changeYear: true,
            defaultDate: new Date()
        }).on('changeDate', function(ev){
            $('#btn-enabled-date-hasta').val($btnCalendarhasta.data('date'));
            $btnCalendarhasta.datepicker('hide');
        });

    });
</script>
</body>
</html>