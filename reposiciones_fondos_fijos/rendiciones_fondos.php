<?php 
require "../clases/conexion.php";
$cod = $_POST['asignacioncod'];
$con = new conexion();
$con->conectar();

$sql = pg_query("SELECT * FROM v_rendiciones_fondos_fijos_cab WHERE rend_estado = 'PROCESADO' AND asignacion_responsable_cod = $cod");
$cuentas_corrientes = pg_fetch_all($sql);

if (!empty($cuentas_corrientes)) {
    echo "<option value='0'>Elija una rendicion</option>";
    foreach ($cuentas_corrientes as $cuentas) {
        echo "<option value='{$cuentas['rendicion_fondo_fijo_cod']}'>Fact. N° {$cuentas['fact_nro']} - {$cuentas['prov_nombre']}</option>";
    }
} else {
    echo "<option value='0'>No posee rendiciones</option>";
}