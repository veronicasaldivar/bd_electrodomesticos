<?php 
require "../clases/conexion.php";
$cod = $_POST['asignacioncod'];
$con = new conexion();
$con->conectar();

$sql = pg_query("SELECT sp_saldo_asignacion($cod) as monto_disponible");
$res = pg_fetch_assoc($sql);

echo json_encode($res);