<?php

require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$entcod = $_POST["entcod"];
$cuenta = $_POST["cuenta"];
$movnro = $_POST["movnro"];
$obs = $_POST["obs"];
$tipomov = $_POST["tipomov"];
$usu = $_POST["usu"];
$suc = $_POST["suc"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();
$sql = pg_query("select sp_otros_creditos_debitos_bancarios($codigo, $entcod, $cuenta, $movnro, '$obs', '$tipomov', $usu, $suc, '$detalle', $ope)");
#-- orden: codigo, ent, cuencorrcod, movnro, obs, tipomov, usu, suc, det[concepto, obs, monto], oper
if ($sql) {
    echo  pg_last_notice($con->url) . "_/_notice";
} else {
    echo pg_last_error() . "_/_error";
}
