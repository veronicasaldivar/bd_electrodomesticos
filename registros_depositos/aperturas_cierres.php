<?php 
require "../clases/conexion.php";
$apercod = $_POST['apercod'];
$con = new conexion();
$con->conectar();

// $sql = pg_query("SELECT monto_efectivo, monto_cheque, (monto_efectivo + monto_cheque) as monto_efectivo_cheque FROM v_aperturas_cierres WHERE aper_cier_cod = $apercod");
$sql = pg_query("SELECT recau_dep_cod, monto_efectivo, monto_cheque, (monto_efectivo + monto_cheque) as monto_efectivo_cheque FROM recaudaciones_dep WHERE aper_cier_cod = $apercod");
$verificar = pg_fetch_all($sql);

if (!empty($verificar)) {
    while($aperturas = pg_fetch_assoc($sql)){
        print_r(json_encode($aperturas));
    }
} else {
    echo "error";
}