<?php 
require "../clases/conexion.php";
$cod = $_POST['cod'];
$con = new conexion();
$con->conectar();

$sql = pg_query("SELECT * FROM asignacion_fondo_fijo where asignacion_responsable_cod = '$cod' ");
$res = pg_fetch_assoc($sql);

echo json_encode($res);