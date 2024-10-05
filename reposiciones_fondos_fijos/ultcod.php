<?php
require "../clases/conexion.php";
$con = new conexion();
$con ->conectar();
$sql = pg_query("SELECT COALESCE(MAX(rep_fon_fij_cod),0) + 1 as ultcod FROM reposicion_fondos_cab ");
$rs = pg_fetch_assoc($sql);
echo $rs["ultcod"];
?>

