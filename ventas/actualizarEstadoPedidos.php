<?php
require '../clases/conexion.php';
$con = new conexion();
$con->conectar();
$pedidocod = $_POST['pedidocod'];

pg_query("UPDATE pedidos_vcab SET ped_estado = 'PROCESADO' WHERE ped_vcod = $pedidocod ");

?>