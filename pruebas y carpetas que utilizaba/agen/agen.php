<?php

require "../clases/funciones.php";
require "../clases/sesion.php";
require "../clases/conexion.php";
verifico();

$con = new conexion();
$con ->conectar();
date_default_timezone_set('America/Asuncion');
//$nros = pg_fetch_assoc($nro);
$fecha = date("d/m/Y H:i:s");
?>
<!DOCTYPE html>
<html lang="es">
 
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agendas</title>
     <link href="../gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../gentelella-master/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">    
        <link href="../gentelella-master/build/css/custom.min.css" rel="stylesheet">    
        <link href="../css/flatty.css" rel="stylesheet"> 
        <link rel="stylesheet" type="text/css" href="../css/chosenselect.css" media="screen">
        <link rel="stylesheet" href="../css/bootstrap-select.css">
        <!--<link rel="stylesheet" href="place.css">-->

    <!--<link rel="stylesheet" href="../css/bootstrap-select.css">-->
    <!--<link rel="stylesheet" href="place.css">-->

<style >
            body{
              background: url(../imagenes/fon1.jpg) no-repeat center center fixed;
              background-size: cover;
              -webkit-background-size: cover;
              -moz-background-size:cover;
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
                                <h1 class="page-header" style="color: #0e0e0e;" > <font  face=" Century Gothic"> Agenda Profesional </h1>
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
                          

                        <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Fecha</label>
                                            <input class="form-control" type="text" id="fecha" value="<?php echo $fecha; ?>" disabled/>
                                        </div>
                                    </div>
                                    
                               <!-- <div class="col-md-1">
                                        <div class="form-group">
                                            <label>Nro.</label>
                                            <input class="form-control" type="text"  id="nro" value="<?php echo $nros["nro"]; ?>" disabled/>
                                        </div>
                                    </div> -->
                                   

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Empresa</label>
                                            
                                                <input class="form-control" type="text"  value="<?php echo $_SESSION["emp_nom"]; ?>" disabled/>
                                           

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Sucursal</label>
                                            
                                                    <input class="form-control" type="text" value="<?php echo $_SESSION["suc_nom"]; ?>" disabled/>
                                               
                                        </div>
                                    </div>

                                 <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Funcionario</label>
                                            
                                                <input  class="form-control" type="text" value="<?php echo $_SESSION["fun_nom"]  ?>" disabled/>
                                            
                                        </div>
                                    </div>
                                           <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Fecha de Registro</label>
                                                    <input class="form-control" type="date" id="fecha" />
                                                </div>
                                            </div>
                            
                      
                                   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="funcionario">Funcionarios</label>
                                            
                                                <select class="form-control selectpicker" data-live-search="true" data-size="5" id="func" onchange="getEspecialidad();" >
                                             
                                                  <?php
                                                  $var = pg_query("select * from v_funcionarios order by fun_cod;");
                                                  while ($i = pg_fetch_assoc($var)) {
                                                    echo "<option value='" . $i["fun_cod"] . "'>" . $i["fun_cod"] . " - " . $i["fun_nom"] ." </option>";
                                                        }
                                                  ?>
                                              </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="profesion">Profesional</label>
                                            
                                                <select class="form-control selectpicker" data-live-search="true" data-size="5" id="profesion" onchange="getEspecialidad();" >
                                             
                                                  <?php
                                                  $var = pg_query("select * from profesiones order by prof_cod;");
                                                  while ($i = pg_fetch_assoc($var)) {
                                                    echo "<option value='" . $i["prof_cod"] . "'>" . $i["prof_cod"] . " - " . $i["prof_desc"] ." </option>";
                                                        }
                                                  ?>
                                              </select>
                                        </div>
                                    </div>
                                    
   
                                    <!-- <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Seleccionar Especialidad</label>
                                                 <select class="form-control  chosen-select" data-size="10" data-live-search="true" onchange="espselect();" id="especialidad">
                                                <option>Elija una Opcion</option>
                                                 # 
                                                #  $var = pg_query("select * from v_especialidades order by esp_cod;");
                                                 # while ($i = pg_fetch_assoc($var)) {
                                                 #   echo "<option value='" . $i["esp_cod"] . "'>" . $i["esp_cod"] . " - " . $i["esp_desc"] . "</option>";
                                                        }
                                                  
                                              </select>
                                            <div id="prog"> </div>
                                        </div>
                                    </div> -->
                                    

                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dias de Atención</label>
                                                

                                              <select class="form-control selectpicker" data-size="5"" data-live-search="true" id="dias">
                                                <option>Elija una Opcion</option>
                                                  <?php
                                                  $var = pg_query("select * from dias order by dias_cod;");
                                                  while ($i = pg_fetch_assoc($var)) {
                                                    echo "<option value='" . $i["dias_cod"] . "'>" . $i["dias_cod"] . " - " . $i["dias_desc"] . "</option>";
                                                        }
                                                  ?>
                                              </select>
                                        </div>
                                    </div>
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora desde</label>
                                            
                                                    <input class="form-control" type="time"  id="hora_desde"/>
                                               
                                        </div>
                                    </div>

                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Hora hasta</label>
                                            
                                                    <input class="form-control" type="time"  id="hora_hasta"/>
                                               
                                        </div>
                                    </div>
                                        <div class="col-md-1">
                                        <div class="form-group">
                                            <label> Nº Cupos</label>
                                            
                                                    <input class="form-control" type="number"  id="cupo"/>
                                               
                                        </div>
                                    </div>
                                    <input type="hidden" id="detalle" value="">
                                                            <input type="hidden" id="usuario" value="<?php echo $_SESSION["id"]; ?>">
                                                            <input type="hidden" id="funcionario" value="<?php echo $_SESSION["fun_cod"]; ?>">
                                                            <input type="hidden" id="empresa" value="<?php echo $_SESSION["emp_cod"]; ?>">
                                                            <input type="hidden" id="sucursal" value="<?php echo $_SESSION["suc_cod"]; ?>">
                                    
                                <div class="col-md-12">
                                        <div class="form-group">
                                            <br>
                                            <input type="button" class="btn btn-success btn-round  btn-dark btn-block agregar" id="agregar" value="AGREGAR"/>
                                        </div>
                                </div>
                                    <div class="col-md-12">
                                    <div class="table-responsive table-bordered">
                                        <table class="table table-responsive" id="grilladetalle">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Especialidad</th>
                                                <th>Hora Desde</th>
                                                <th>Hora Hasta</th>
                                                <th>Cupos</th>
                                                <th>Dias de Atención</th>
                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <!-- <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right" id="total">Total: 0.00 Gs.</th>
                                            </tr>
                                        </tfoot> -->
                                    </table>
                                </div><br>
                                </div>
                                <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <input type="button" onclick="location.reload();" class="btn btn-round btn-primary btn-block cancelar" id="cancelar" value="CANCELAR"/>
                                            </div>
                                        </div>
                                <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <input type="button"  class="btn btn-round btn-primary btn-dark btn-block " id="grabar" value="GUARDAR"/>
                                            </div>
                                        </div>
                                

                        </div>
                      </div>
                    </div>
                </div> 

                <div class="col-md-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Lista de Agendas Profesionales</h2>
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
                                <table class="table" style="color: #000;" id="agendas">
                                    <thead>
                                        <tr width="80px">
                                            <th></th>
                                            <th>Codigo</th>
                                            <th>Funcionario</th>
                                            <!-- <th>Fecha de Registro</th> -->
                                            <th>Profesion</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                            
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                </div>
              </div>
              <div class="modal fade bs-example-modal-lg" id="modal_basic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h3 class="modal-title" id="myModalLabel">Editar</h3>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                               <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Agendas</h2>  
                    <div class="clearfix"></div>
                  </div>
                      <div class="form-group">
                        <div class="col-sm-12">
                          

                                 <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Funcionario</label>
                                            
                                                <input  class="form-control" type="text" value="<?php echo $_SESSION["fun_nom"] . " " . $_SESSION["fun_ape"]; ?>" disabled/>
                                            
                                        </div>
                                    </div>
                            <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="profesion">Profesional</label>
                                            
                                                <select class="form-control selectpicker" data-size="10" data-live-search="true" id="profesion"  onchange="getEspecialidad();>
                                             
                                                  <?php
                                                  $var = pg_query("select * from profesionales order by fun_cod;");
                                                  while ($i = pg_fetch_assoc($var)) {
                                                    echo "<option value='" . $i["prof_cod"] . "'>" . $i["prof_cod"] . " - " . $i["prof_desc"] . "</option>";
                                                        }
                                                  ?>
                                              </select>
                                        </div>
                                    </div>
                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="fecha">Fecha</label>
                                                    <input class="form-control" type="text" id="fecha" value="<?php echo $fecha; ?>" disabled/>
                                                </div>
                                            </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Dias de Atención</label>
                                                

                                              <select class="form-control selectpicker" data-size="10" data-live-search="true" >
                                                <option>Elija una Opcion</option>
                                                  <?php
                                                  $var = pg_query("select * from dias order by dias_cod;");
                                                  while ($i = pg_fetch_assoc($var)) {
                                                    echo "<option value='" . $i["dias_cod"] . "'>" . $i["dias_cod"] . " - " . $i["dias_desc"] . "</option>";
                                                        }
                                                  ?>
                                              </select>
                                        </div>
                                    </div>

                                    
   
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="especialidad">Seleccionar Especialidad</label>
                                                 <select class="form-control selectpicker" data-size="10" data-live-search="true" id="especialidad">
                                                <option>Elija una Opcion</option>
                                                  <?php
                                                  $var = pg_query("select * from v_especialidades order by esp_cod;");
                                                  while ($i = pg_fetch_assoc($var)) {
                                                    echo "<option value='" . $i["esp_cod"] . "'>" . $i["esp_cod"] . " - " . $i["esp_desc"] . "</option>";
                                                        }
                                                  ?>
                                              </select>
                                               <div id="prog"> </div>
                                        </div>
                                    </div>
                                    

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label  for="profesion">Profesion</label>
                                            
                                                    <input class="form-control" type="text"  id="profesion" placeholder="profesion" disabled />
                                               
                                        </div>
                                    </div>

                                    
                                     <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Hora desde</label>
                                            
                                                    <input class="form-control" type="time"  id="hora_desde"/>
                                               
                                        </div>
                                    </div>

                                     <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Hora hasta</label>
                                            
                                                    <input class="form-control" type="time"  id="hora_hasta"/>
                                               
                                        </div>
                                    </div>
                                        <div class="col-md-1">
                                        <div class="form-group">
                                            <label> Nº Cupos</label>
                                            
                                                    <input class="form-control" type="number"  id="cupo"/>
                                               
                                        </div>
                                    </div>
                                    <input type="hidden" id="detalle" value="">
                                                            <input type="hidden" id="usuario" value="<?php echo $_SESSION["id"]; ?>">
                                                            <input type="hidden" id="funcionario" value="<?php echo $_SESSION["fun_cod"]; ?>">
                                                            <input type="hidden" id="empresa" value="<?php echo $_SESSION["emp_cod"]; ?>">
                                                            <input type="hidden" id="sucursal" value="<?php echo $_SESSION["suc_cod"]; ?>">
                                    
                                <div class="col-md-12">
                                        <div class="form-group">
                                            <br>
                                            <input type="button" class="btn btn-info btn-block agregar" id="agregar" value="AGREGAR"/>
                                        </div>
                                </div>
                                    <div class="col-md-12">
                                    <div class="table-responsive table-bordered">
                                        <table class="table table-responsive" id="grilladetalle">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Especialidad</th>
                                         
                                                <th>Hora Desde</th>
                                                <th>Hora Hasta</th>
                                                <th>Cupos</th>
                                                <th>Dias de Atención</th>
                                             
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <!-- <tfoot>
                                            <tr>
                                                <th colspan="5" class="text-right" id="total">Total: 0.00 Gs.</th>
                                            </tr>
                                        </tfoot> -->
                                    </table>
                                </div><br>
                                </div>
                                <div class="col-md-12">
                                        <div class="form-group">
                                    <button type="submit" class="btn btn-primary pull-right" id="grabar"><span class="fa fa-save"></span> GRABAR</button>
                                        </div>
                                </div>

                        </div>
                      </div>
                    </div>
                </div> 
                                        </div>
                                         <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <input type="button" onclick="location.reload();" class="btn btn-round btn-warning btn-block cancelar" id="cancelar" value="CANCELAR"/>
                                            </div>
                                        </div>
                                            <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" id="cerrar" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="guardar_edit">Guardar Cambios</button>
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
    </div>
     

    
<script src="../js/jquery.js"></script>

<script src="../js/agenda.js"></script>
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
