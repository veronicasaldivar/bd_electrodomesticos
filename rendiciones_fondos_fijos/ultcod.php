<?php
require "../clases/conexion.php";
$con = new conexion();
$con ->conectar();
$sql = pg_query("SELECT COALESCE(MAX(rendicion_fondo_fijo_cod),0) + 1 as ultcod FROM rendicion_fondo_fijo_cab ");
$rs = pg_fetch_assoc($sql);
echo $rs["ultcod"];
?>

