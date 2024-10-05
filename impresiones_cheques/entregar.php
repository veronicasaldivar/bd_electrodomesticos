<?php
require '../clases/conexion.php';

$codigo = $_POST["codigo"];
$entidad = $_POST["entidad"];
$cuenta = $_POST["cuenta"];
$movnro = $_POST["movnro"];
$cedula = $_POST["cedula"];
$nombres = $_POST["nombres"];
$obs = $_POST["obs"];
$usu = $_POST["usu"];
$suc = $_POST["suc"];
$ope = $_POST["ope"];

$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_entregas_cheques($codigo, $entidad, $cuenta, $movnro, '$cedula', '$nombres', '$obs', $usu, $suc, $ope)");
#-- orden: cod, ent, cuenta, movnro, ced, nom, obs, usu, suc, oper
if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
