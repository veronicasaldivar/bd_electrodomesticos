<?php

require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$sucursal = $_POST["sucursal"];
$usuario = $_POST["usuario"];
$tipoajuste = $_POST["tipoajuste"];
$deposito= $_POST["deposito"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
// $empresa = $_POST["empresa"];
// $funcionario = $_POST["funcionario"];
$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_ajustes($codigo,$sucursal,$usuario,'$tipoajuste',$deposito,'$detalle',$ope)");
$noticia = pg_last_notice($con->url);
echo str_replace("NOTICE: ","",$noticia);
?>

