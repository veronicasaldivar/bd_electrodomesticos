<?php
require '../clases/conexion.php';
$entidades = $_POST["entidades"];
$cuentas_corrientes = $_POST["cuentas_corrientes"];
$movimiento_nro = $_POST["movimiento_nro"];
$recaudaciones_depositar = $_POST["recaudaciones_depositar"];
$aperturas_cierres = $_POST["aperturas_cierres"];
$monto_depositar = $_POST["monto_depositar"];
$usu = $_POST["usu"];
$suc = $_POST["suc"];
$ope = $_POST["ope"];

$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_boletas_depositos($entidades, $cuentas_corrientes,  $movimiento_nro, $recaudaciones_depositar, $aperturas_cierres, $monto_depositar, $usu, $suc, $ope)");
#-- ORDEN: entcod, cuencorrcod, movnro, reccod, apercod, monto, usu, suc,  oper
if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
