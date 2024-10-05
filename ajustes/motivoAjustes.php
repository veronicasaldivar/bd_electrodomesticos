<?php
require "../clases/conexion.php";
$tipo = $_POST["tipo"];
$con = new conexion();
$con->conectar();
$sql = pg_query("SELECT * FROM motivo_ajustes WHERE mot_tip_ajuste = '$tipo'");
$motivos = pg_fetch_all($sql);

if(!empty($motivos)){
  echo "<option value='0'>Elija una opción</option>";
  foreach($motivos as $motivo){
    echo "<option value='{$motivo['mot_cod']}'>{$motivo['mot_cod']} - {$motivo['mot_desc']}</option>";
  }
}
