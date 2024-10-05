<?php
require '../clases/conexion.php';
$asignacioncod = $_POST["asignacioncod"];
$codigo = $_POST["codigo"];
$proveedor = $_POST["proveedor"];
$tfactura = $_POST["tfactura"];
$timbrado = $_POST["timbrado"];
$timbrado_vig = $_POST["timbrado_vig"];
$nrofact = $_POST["nrofact"];
$fechafact = $_POST["fechafact"];
$plazo = $_POST["plazo"];
$cuotas = $_POST["cuotas"];
$usu = $_POST["usuario"];
$suc = $_POST["sucursal"];
$obs = $_POST["obs"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();

$sql = pg_query("select sp_rendicion_fondo_fijos($asignacioncod, $codigo, $proveedor, $tfactura, $timbrado, '$timbrado_vig', '$nrofact', '$fechafact', $plazo, $cuotas, $usu, $suc, '$obs', '$detalle', $ope)");
// orden: asigrescod, codigo, prov, tipofact, tim, timvighas, factnro, factfecha, pla, cuo, usu, suc, obs, det[rubro, monto, tipimp], oper

if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
