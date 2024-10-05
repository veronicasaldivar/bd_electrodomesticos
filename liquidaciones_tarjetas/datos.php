<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_cuentas_corrientes WHERE cuen_corr_est = 'ACTIVO' ORDER BY ent_cod");
$cuentas_corrientes = pg_fetch_all($sql);

if (!empty($cuentas_corrientes)) {
    $button_borrar = '<button type=\'button\' class=\'btn btn-primary  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Conciliar\'><i class=\'fa fa-check\'></i></button>';
    $button = ''; //$button_borrar;

    $datos['data'] = [];
    foreach ($cuentas_corrientes as $key => $cuenta_corriente) {
        $datos['data'][$key]['cod'] = $cuenta_corriente['ent_cod'];
        $datos['data'][$key]['fecha'] = $cuenta_corriente['cuen_corr_fecha_aper'];
        $datos['data'][$key]['entidad'] = $cuenta_corriente['ent_nom'];
        $datos['data'][$key]['cuenta'] = $cuenta_corriente['cuenta_corriente_nro'];
        $datos['data'][$key]['monto'] = number_format($cuenta_corriente['monto_disponible'], 0, ',', '.');
        $datos['data'][$key]['estado'] = $cuenta_corriente['cuen_corr_est'];
        $datos['data'][$key]['acciones'] = $button;
    }
} else {
    $datos['data']['cod'] = '-';
    $datos['data']['fecha'] = '-';
    $datos['data']['entidad'] = '-';
    $datos['data']['cuenta'] = '-';
    $datos['data']['usu'] = '-';
    $datos['data']['estado'] = '-';
    $datos['data']['acciones'] = '-';
}

echo json_encode($datos);
return json_encode($datos);
