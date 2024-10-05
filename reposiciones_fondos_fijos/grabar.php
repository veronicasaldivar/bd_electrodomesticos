<?php
require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$usu = $_POST["usuario"];
$suc = $_POST["sucursal"];
$obs = $_POST["obs"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();

$sql = pg_query("select sp_reposicion_fondo_fijos($codigo, $usu, $suc, '$obs', '$detalle', $ope)");
// ORDEN: codigo, usu, suc, obs, det[rendcod, rubro, monto, tipimp], oper

if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
