<?php

require '../clases/conexion.php';
$notanro = $_POST["notanro"];
$proveedor = $_POST["proveedor"];
$timbrado = $_POST["timbrado"];
$nrofact = $_POST["nrofact"];
$fechafact = $_POST["fechafact"];
$tiponota = $_POST["tipo_nota"];
$plazo = $_POST["plazo"];
$cuotas = $_POST["cuotas"];
$suc = $_POST["suc"];
$usu = $_POST["usu"];
$notamonto = $_POST["notamonto"];
$desc = $_POST["desc"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
// $depcod = $_POST["depcod"];
// $emp = $_POST["emp"];
// $fun = $_POST["fun"];
$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_notas_compras($notanro,$proveedor,$timbrado,'$nrofact','$tiponota',$usu,$suc,$notamonto,'$desc','$detalle',$ope)");
//--ORDEN: notanro, provcod, provtimbnro, nrofactura, notatipo, usucod, succod, detalle[], operacion

if($sql){
  echo  pg_last_notice($con->url)."_/_notice";
}else{
    echo pg_last_error()."_/_error";
}
?>