<?php
require '../clases/conexion.php';

$codigo = $_POST["codigo"];
$entidades = $_POST["entidades"];
$cuentas_corrientes = $_POST["cuentas_corrientes"];
$cajas = $_POST["cajas"];
$monto = $_POST["monto"];
$obs = $_POST["obs"];
$usu = $_POST["usu"];
$suc = $_POST["suc"];
$fun_res = $_POST["fun_res"];
$forma_asignacion = $_POST["forma_asignacion"];
$cheque_num = $_POST["cheque_num"];
$ope = $_POST["ope"];

$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_asignacion_fondo_fijos($codigo, $entidades, $cuentas_corrientes,  $cajas, $monto, '$obs', $usu, $suc, $fun_res, '$forma_asignacion', $cheque_num, $ope)");
#-- orden: codigo, entcod, cuencorrcod, cajacod, monto, obs, usu, suc, funresponsable, tipoasignacion, chequenum, oper
if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
