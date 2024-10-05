<?php
require "../clases/conexion.php";
$con = new conexion();
$con ->conectar();
$sql = pg_query("SELECT COALESCE(MAX(orden_pago_cod),0) + 1 as ultcod FROM orden_pago_cab ");
$rs = pg_fetch_assoc($sql);
echo $rs["ultcod"];
?>

