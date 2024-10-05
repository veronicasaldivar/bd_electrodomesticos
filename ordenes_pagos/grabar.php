<?php
require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$provcod = $_POST["provcod"];
$nrofact = $_POST["nrofact"];
$fcobro = $_POST["fcobro"];
$usu = $_POST["usuario"];
$suc = $_POST["sucursal"];
$factvarcod = $_POST["factvarcod"];
$detalleCheques = $_POST["detalleCheques"];
$montoDisponible = $_POST["montoDisponible"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();

$sql = pg_query("select sp_ordenes_pagos($codigo, $provcod, '$nrofact', $fcobro, $usu, $suc, $factvarcod, '$detalleCheques', $montoDisponible, $ope)");
#-- Orden: codigo, provcod, nrofactura, fcobcod, usucod, sucod, factvarcod, detCheques, montoDisponible, oper
#-- detCheques[codigo, entcod, cuenta, cheqnum, cheqmon]
if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
