<?php

require '../clases/conexion.php';
$cobrocod = $_POST["cobrocod"];
$nrotarj = $_POST["nrotarj"];
$codauto = $_POST["codauto"];
// $estado = $_POST["estado"];
/*
$usu = $_POST["usu"];
$suc = $_POST["suc"];
$ope = $_POST["ope"];
*/
$con = new conexion();
$con->conectar();
$sqlEstado = pg_query("SELECT conciliar from cobros_tarjetas WHERE cobro_cod = $cobrocod AND cob_tarj_nro = $nrotarj AND cod_auto = $codauto");
$EstadoRes = pg_fetch_assoc($sqlEstado);

if ($EstadoRes['conciliar'] === 'CONCILIADO') {
    $sql = pg_query("UPDATE cobros_tarjetas SET conciliar = null WHERE cobro_cod = $cobrocod AND cob_tarj_nro = $nrotarj AND cod_auto = $codauto");
} else {
    $sql = pg_query("UPDATE cobros_tarjetas SET conciliar = 'CONCILIADO' WHERE cobro_cod = $cobrocod AND cob_tarj_nro = $nrotarj AND cod_auto = $codauto");
}

if ($sql) {
    //echo  pg_last_notice($con->url) . "_/_notice";
    if ($EstadoRes['conciliar']  === 'CONCILIADO') {
        echo  "NOTICE: COBRO TARJETA DESCONCILIADO" . "_/_notice";
    } else {
        echo  "NOTICE: COBRO TARJETA CONCILIADO" . "_/_notice";
    }
} else {
    echo pg_last_error() . "_/_error";
}
