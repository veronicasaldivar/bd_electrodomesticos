<?php
require '../clases/conexion.php';
$entcod = $_GET['entcod'];
$cuenta = $_GET['cuenta'];

$con = new conexion();
$con->conectar();

$sqlExiste = pg_query("SELECT * from chequeras where ent_cod = $entcod and cuenta_corriente_cod = $cuenta");
$cheqExiste = pg_fetch_assoc($sqlExiste);

if (!empty($cheqExiste)) {
    $sql = pg_query("SELECT coalesce(max(cheq_num_ult), 0) + 1 as sig_cheque from chequeras where ent_cod = $entcod and cuenta_corriente_cod = $cuenta");
    $verificar = pg_fetch_assoc($sql);
    echo $verificar['sig_cheque'];
} else {
    $error = "error";
    echo $error;
}
