<?php 
require "../clases/conexion.php";
$entcod = $_POST['entcod'];
$con = new conexion();
$con->conectar();

$sql = pg_query("SELECT * FROM cuentas_corrientes WHERE ent_cod = $entcod");
$cuentas_corrientes = pg_fetch_all($sql);

if (!empty($cuentas_corrientes)) {
    echo "<option value='0'>Elija una cuenta</option>";
    foreach ($cuentas_corrientes as $cuentas) {
        echo "<option value='{$cuentas['cuenta_corriente_cod']}'>Cuenta N.° {$cuentas['cuenta_corriente_nro']}</option>";
    }
} else {
    echo "<option value='0'>No posee cuentas corrientes</option>";
}