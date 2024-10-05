<?php
require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$proveedor = $_POST["proveedor"];
$tipodocumento = $_POST["tipodocumento"];
$tfactura = $_POST["tfactura"];
$plazo = $_POST["plazo"];
$cuotas = $_POST["cuotas"];
$timbrado = $_POST["timbrado"];
$timbrado_vig = $_POST["timbrado_vig"];
$nrofact = $_POST["nrofact"];
$fechafact = $_POST["fechafact"];
$obs = $_POST["obs"];
$usu = $_POST["usuario"];
$suc = $_POST["sucursal"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();

$sql = pg_query("select sp_facturas_varias($codigo, $proveedor, 1, $tfactura, $cuotas, $plazo, $timbrado, '$timbrado_vig', '$nrofact', '$fechafact', '$obs', $usu, $suc, '$detalle', $ope)");
// ORDEN: codigo, prov, tipodoc, tipfact, cuotas, plazo, tim, vighasta, nrofact, fechafact, obs, usu, suc, det[], oper

if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
