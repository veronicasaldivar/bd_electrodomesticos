<?php
require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_bancos($codigo,'$nombre','$direccion','$telefono',$ope)");
$noticia = pg_last_notice($con->url);
echo str_replace("NOTICE: ","",$noticia);
?>


