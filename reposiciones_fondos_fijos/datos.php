<?php
require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_reposiciones_fondos_fijos_cab ORDER BY rep_fon_fij_cod DESC");
$tabla = pg_fetch_all($sql);

$button_anular = '<button type=\'button\' class=\'btn btn-primary btn-circle btn-lg  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';

$button = $button_anular;


if (!empty($tabla)) {
    $datos['data'] = [];
    foreach ($tabla as $key => $cab) {
        $datos['data'][$key]['codigo'] = $cab['rep_fon_fij_cod'];
        $datos['data'][$key]['fecha'] = $cab['reposicion_fecha'];
        $datos['data'][$key]['estado'] = $cab['rep_estado'];
        $datos['data'][$key]['sucursal'] = $cab['suc_nom'];
        $datos['data'][$key]['usuario'] = $cab['usu_name'];
        $datos['data'][$key]['acciones'] = $button;

        $sqldetalle = pg_query('SELECT * FROM  v_reposiciones_fondos_fijos_det WHERE rep_fon_fij_cod=' . $cab['rep_fon_fij_cod']);
        $detalles = pg_fetch_all($sqldetalle);

        foreach ($detalles as $key2 => $detalle) {
            $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['rep_fon_fij_cod'];
            $datos['data'][$key]['detalle'][$key2]['rubrocod'] = $detalle['rubro_cod'];
            $datos['data'][$key]['detalle'][$key2]['rubrodesc'] = $detalle['rubro_desc'];
            $datos['data'][$key]['detalle'][$key2]['tipo_impuesto'] = $detalle['tipo_imp_desc'];
            $datos['data'][$key]['detalle'][$key2]['monto'] = $detalle['rendi_monto_fact'];
            $datos['data'][$key]['detalle'][$key2]['grav10'] = $detalle['grav10'];
            $datos['data'][$key]['detalle'][$key2]['grav5'] = $detalle['grav5'];
            $datos['data'][$key]['detalle'][$key2]['exenta'] = $detalle['exentas'];
            $datos['data'][$key]['detalle'][$key2]['iva10'] = $detalle['iva10'];
            $datos['data'][$key]['detalle'][$key2]['iva5'] = $detalle['iva5'];
        }
    }
} else {
    $datos['data'] = [];
    $datos['data']['codigo'] = '-';
    $datos['data']['fecha'] = '-';
    $datos['data']['estado'] = '-';
    $datos['data']['sucursal'] = '-';
    $datos['data']['usuario'] = '-';
    $datos['data']['acciones'] = '-';
}

echo json_encode($datos);
return json_encode($datos);