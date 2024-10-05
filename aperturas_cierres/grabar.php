<?php
require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$apermonto = $_POST["apermonto"];
$caja = $_POST["caja"];
$ope = $_POST["ope"];
$usu = $_POST["usu"];

$con = new conexion(); 
$con->conectar();
$sql = pg_query("select sp_aperturas_cierres($codigo, $apermonto, $caja, $usu, $ope)");
if(!$sql){
	echo pg_last_error()."_/_error";
}else{
	echo pg_last_notice($con->url)."_/_notice";
}
