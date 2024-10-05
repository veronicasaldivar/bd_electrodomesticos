<?php
require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_facturas_varias_cab ORDER BY fact_var_cod DESC");
$tabla = pg_fetch_all($sql);

$button_anular = '<button type=\'button\' class=\'btn btn-primary btn-circle btn-lg  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';

$button = $button_anular;


if (!empty($tabla)) {
    $datos['data'] = [];
    foreach ($tabla as $key => $cab) {
        $datos['data'][$key]['codigo'] = $cab['fact_var_cod'];
        $datos['data'][$key]['ffactura'] = $cab['factura_varias_fecha'];
        $datos['data'][$key]['cliente'] = $cab['prov_nombre'];
        $datos['data'][$key]['tipofactcod'] = $cab['tipo_fact_desc'];
        $datos['data'][$key]['cuotas'] = $cab['cuotas'];
        $datos['data'][$key]['estado'] = $cab['fact_var_estado'];
        $datos['data'][$key]['usuario'] = $cab['usu_name'];
        $datos['data'][$key]['acciones'] = $button;

        $sqldetalle = pg_query('SELECT * FROM  v_facturas_varias_det WHERE fact_var_cod=' . $cab['fact_var_cod']);
        $detalles = pg_fetch_all($sqldetalle);

        foreach ($detalles as $key2 => $detalle) {
            $datos['data'][$key]['detalle'][$key2]['fact_var_cod'] = $detalle['fact_var_cod'];
            $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['rubro_cod'];
            $datos['data'][$key]['detalle'][$key2]['rubro'] = $detalle['rubro_desc'];
            $datos['data'][$key]['detalle'][$key2]['tipo_impuesto'] = $detalle['tipo_imp_desc'];
            $datos['data'][$key]['detalle'][$key2]['monto'] = $detalle['monto'];
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
    $datos['data']['nro'] = '-';
    $datos['data']['ffactura'] = '-';
    $datos['data']['cliente'] = '-';
    $datos['data']['plazo'] = '-';
    $datos['data']['cuotas'] = '-';
    $datos['data']['estado'] = '-';
    $datos['data']['usuario'] = '-';
    $datos['data']['acciones'] = '-';
}

echo json_encode($datos);
return json_encode($datos);