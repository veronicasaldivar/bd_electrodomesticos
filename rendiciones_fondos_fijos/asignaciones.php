<?php 
require "../clases/conexion.php";
$asigcod = $_GET['asigcod'];
$con = new conexion();
$con->conectar();

$sql = pg_query("SELECT * FROM v_asignacion_fondo_fijos WHERE asignacion_responsable_cod = '$asigcod' ");
$verificar = pg_fetch_all($sql);

if(!empty($verificar)){
	while($asignacion = pg_fetch_assoc($sql)){
		print_r(json_encode($asignacion));
	}; 
}else{
	$error = 'error';
	echo $error;
}
