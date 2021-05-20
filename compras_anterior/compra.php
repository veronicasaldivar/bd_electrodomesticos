<?php
require "../clases/sesion.php";
verifico();
require "../clases/conexion.php";
$con = new conexion();
$con->conectar();
$nro = pg_query("select coalesce(max(comp_cod),0)+1 as nro from compras_cab;");
$nros = pg_fetch_assoc($nro);
date_default_timezone_set('America/Asuncion');
$fecha = date("d/m/Y H:h:m");
// $sqltim = pg_query('select timb_nro from timbrados where tim_vighasta >= current_date');
#$resultim = pg_fetch_assoc($sqltim);
#if (!$resultim) {
  #  header('location:../timbrados/timbrado.php');
#}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>COMPRA</title>
        <link href="../gentelella-master/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../gentelella-master/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">    
        <link href="../gentelella-master/build/css/custom.min.css" rel="stylesheet">    
        <link href="../css/flatty.css" rel="stylesheet"> 
        <link rel="stylesheet" type="text/css" href="../css/chosenselect.css" media="screen">
        <link rel="stylesheet" href="../css/bootstrap-select.css">
        <!--<link rel="stylesheet" href="place.css">-->

        <!--<link rel="stylesheet" type="text/css" href="../../css/chosenselect.css" media="screen">-->
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
                                <h1 class="page-header" style="color: #0e0e0e;" > <font  face=" Century Gothic"> COMPRAS </h1>
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
                                        <!--<div class="col-sm-16 col-xs-16">-->  
                                         <div class="col-md-12 ">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Nro.</label>
                                                    <input class="form-control" type="text"  id="nro" value="<?php echo $nros["nro"]; ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Fecha</label>
                                                    <input class="form-control" type="text" id="fecha" value="<?php echo $fecha; ?>" disabled/>
                                                </div>
                                            </div>

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
                                                    <input  class="form-control" type="text" value="<?php echo $_SESSION["fun_nom"] ?>" disabled/>

                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Fecha</label>
                                                    <input class="form-control" type="date" id="ffactura" value="<?php echo $fecha; ?>" />
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Timbrado</label>
                                                    <!-- <input type="text" class="form-control"  id="timbrado" value=" echo $resultim ['timb_nro']; " /> -->
                                                    <select name="" id="timbrado2" class=" form-control selectpicker" data-live-search="true" data-size=>
                                                    
                                                    
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Seleccionar Proveedor</label>
                                                    <select class="form-control chosen-select"  data-size="5" data-live-search="true" id="proveedor">
                                                         <option value="0">Seleccion un Proveedor</option>
                                                        <?php
                                                        $var = pg_query("select * from v_proveedores order by prov_cod;");
                                                        while ($i = pg_fetch_assoc($var)) {
                                                            echo "<option value='" . $i["prov_cod"] . "'>" . $i["prov_cod"] . " - " . $i["prov_nombre"]. "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                              <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="tipofact">Seleccionar Tipo de Factura</label>
                                                    <select class="form-control chosen-select" id="tipofact" onchange="tiposelect();" >

                                                        <option value="CONTADO">CONTADO</option>
                                                        <option value="CREDITO">CREDITO</option>

                                                    </select>
                                                </div>
                                            </div>    
                                           
                                            <div class="col-md-3 ">
                                                <div class="form-group">
                                                    <label for="cboiddeposito" class="control-label">Seleccionar Deposito</label>
                                                    <!-- onchange="getMercaderias();"  tenia como atribtuo el select--> 
                                                    <select class="form-control chosen-select"  id="cboiddeposito">  
                                                        <option value="">Elija una opcion</option>
                                                        <?php
                                                        $var = pg_query("select * from v_depositos where  suc_cod = ".$_SESSION['suc_cod']." order by 2");
                                                        while ($i = pg_fetch_assoc($var)) {
                                                            echo "<option value='" . $i["dep_cod"] . "'>" . $i["dep_desc"] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="item">Seleccionar Item</label>
                                                                                                <!--onchange="merselect(); tenia como atributo el select  -->
                                                    <select class="form-control chosen-select" data-size="10" data-live-search="true" " id="item" >
                                                        <option>Elija una Opcion</option>
                                                        <?php
                                                        $var = pg_query("select * from v_items where tipo_item_cod = 1 order by item_cod");
                                                        while ($i = pg_fetch_assoc($var)) {
                                                            echo "<option value='" . $i["item_cod"] . "'>" . $i["item_cod"] . " - " . $i["item_desc"] . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <div id="prog"> </div>
                                                </div>
                                            </div>
                                            </div>
                                               <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Stock</label>
                                                    <input class="form-control" type="text" id="stock" placeholder="0" readonly/>
                                                </div>
                                            </div>
                                               <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Tipo Impuesto</label>
                                                    <input class="form-control" type="text" id="tipoimpuesto" placeholder="0" readonly/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Precio</label>
                                                    <input class="form-control" type="number"  id="precio" placeholder="0 Gs." readonly="" />
                                                </div>
                                            </div>
                                            <!--<div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label>Cant. Stock</label>
                                                                                                <input class="form-control" type="number" id="stock" placeholder="0" readonly/>
                                                                                            </div>-->

                                           


                                            <!--                                            <div class="col-md-3">
                                                                                            <div class="form-group">
                                                                                                <label>Tipo Items</label>
                                                                                                <input class="form-control" type="text" id="tipoitem" placeholder="tipo item" readonly/>
                                                                                            </div>
                                                                                        </div>-->





                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Cantidad</label>
                                                    <input class="form-control" type="number" min="1" id="cantidad"  placeholder="Cantidad"/>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="plazo">Plazo</label>
                                                    <input type="number" class="form-control" value="0" id="plazo" onblur="if (this.value === '') {
                                                                this.value = 0;
                                                            }" disabled=""/>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label for="cuotas">Cuotas</label>
                                                    <input min="1" type="number" class="form-control" value="1" required id="cuotas" onblur="if (this.value === '') {
                                                                this.value = 1;
                                                            }" disabled=""/>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" id="detalle" value="">
                                        <input type="hidden" id="usuario" value="<?php echo $_SESSION["id"]; ?>">
                                        <input type="hidden" id="funcionario" value="<?php echo $_SESSION["fun_cod"]; ?>">
                                        <input type="hidden" id="empresa" value="<?php echo $_SESSION["emp_cod"]; ?>">
                                        <input type="hidden" id="sucursal" value="<?php echo $_SESSION["suc_cod"]; ?>">
                                        <input type="hidden" id="codigo" value="0"> <!-- Se calcula en el sp -->

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                              <input type="button" class=" btn btn-round btn-dark btn-block agregar "  id="agregar" value="AGREGAR" /> 
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <br>
                                                <input type="button" onclick="location.reload();" class="btn btn-round btn-dark btn-block cancelar" id="cancelar" value="CANCELAR"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive table-bordered">
                                                <table class="table table-responsive" id="grilladetalle">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-align: center;">Código</th>
                                                            <th>Descripción</th>                                                
                                                            <th style="text-align: center;">Cantidad</th>
                                                            <th style="text-align: right;">Precio</th>
                                                            <th style="text-align: right;">Exenta</th>
                                                            <th style="text-align: right;">Grav. 5%</th>
                                                            <th style="text-align: right;">Grav. 10%</th>
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
                                            <input type="submit" class="btn btn-round btn-dark btn-block span20 grabar" id="grabar" value="GUARDAR"/>
                                        </div>

                                  
                                </div>
                            </div>
                        </div> 

                        <div class="col-md-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Lista de Compras</h2>
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
                                                    <th width="8%">Nro</th>
                                                    <th width="8%">Fecha Emisión</th>
                                                    <th width="8%">Proveedor</th>
                                                    <th width="8%">Plazo</th>
                                                    <th width="8%">Estado</th>
                                                    <th width="8%" >Acciones</th>


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
</div>

<script src="../js/jquery.js"></script>

<script src="../js/compra.js"></script> 
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
