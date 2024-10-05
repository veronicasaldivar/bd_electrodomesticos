<?php

require '../clases/conexion.php';
$entidad = $_POST["entidad"];
$cuenta = $_POST["cuenta"];
$movnro = $_POST["movnro"];

/*
$usu = $_POST["usu"];
$suc = $_POST["suc"];
$ope = $_POST["ope"];
*/
$con = new conexion();
$con->conectar();
$sqlEstado = pg_query("SELECT conciliar from movimiento_bancario WHERE ent_cod = $entidad AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");
$EstadoRes = pg_fetch_assoc($sqlEstado);

if ($EstadoRes['conciliar'] === 'CONCILIADO') {
    $sql = pg_query("UPDATE movimiento_bancario SET conciliar = null WHERE ent_cod = $entidad AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");
} else {
    $sql = pg_query("UPDATE movimiento_bancario SET conciliar = 'CONCILIADO' WHERE ent_cod = $entidad AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");
}

if ($sql) {
    //echo  pg_last_notice($con->url) . "_/_notice";
    if ($EstadoRes['conciliar'] === 'CONCILIADO') {
        echo  "NOTICE: MOVIMIENTO BANCARIO DESCONCILIADO" . "_/_notice";
    } else {
        echo  "NOTICE: MOVIMIENTO BANCARIO CONCILIADO" . "_/_notice";
    }
} else {
    echo pg_last_error() . "_/_error";
}
