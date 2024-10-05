<?php

require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$nro = $_POST["nro"];
$suc = $_POST["suc"];
$cli = $_POST["cli"];
$usu = $_POST["usu"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_pedidos_ventas($codigo,$suc,$nro,$usu,$cli,'$detalle',$ope)");
if ($sql) {
    echo  pg_last_notice($con->url) . "_/_notice";
} else {
    echo pg_last_error() . "_/_error";
}
