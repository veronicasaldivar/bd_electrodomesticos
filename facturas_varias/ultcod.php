<?php
require "../clases/conexion.php";
$con = new conexion();
$con ->conectar();
$sql = pg_query("SELECT COALESCE(MAX(fact_var_cod),0) + 1 as ultcod FROM facturas_varias_cab ");
$rs = pg_fetch_assoc($sql);
echo $rs["ultcod"];
?>

