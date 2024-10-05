<?php
require '../clases/conexion.php';
$entidad = $_POST["entidad"];
$cuenta = $_POST["cuenta"];
$movnro = $_POST["movnro"];
$librador = $_POST["librador"];

$con = new conexion();
$con->conectar();
$sql = pg_query("UPDATE pago_cheques SET librador = '$librador' WHERE ent_cod = $entidad AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");

if (!$sql) {
    echo pg_last_error() . "_/_error";
} else {
    echo pg_last_notice($con->url) . "_/_notice";
}
