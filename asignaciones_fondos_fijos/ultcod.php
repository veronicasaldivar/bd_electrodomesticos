<?php
require '../clases/conexion.php';
$con = new conexion();
$con->conectar();

$sql = pg_query("SELECT coalesce(max(asignacion_responsable_cod), 0) + 1 as ultcod FROM asignacion_fondo_fijo");
$rs = pg_fetch_assoc($sql);

echo $rs["ultcod"];
