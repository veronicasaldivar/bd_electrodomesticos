<?php
require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_ventas_cab ORDER BY ven_cod DESC");
$tabla = pg_fetch_all($sql);

$button_anular = '<button type=\'button\' class=\'btn btn-primary btn-circle btn-lg  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';

$button = $button_anular;

if (!empty($tabla)) {
    $datos['data'] = [];
    foreach ($tabla as $key => $cab) {
        $datos['data'][$key]['codigo'] = $cab['ven_cod'];
        $datos['data'][$key]['ffactura'] = $cab['ven_fecha'];
        $datos['data'][$key]['cliente'] = $cab['cli_nom'];
        $datos['data'][$key]['tipofactcod'] = $cab['tipo_fact_desc'];
        $datos['data'][$key]['cuotas'] = $cab['ven_cuotas'];
        $datos['data'][$key]['estado'] = $cab['ven_estado'];
        $datos['data'][$key]['usuario'] = $cab['usu_name'];
        $datos['data'][$key]['acciones'] = $button;

        $sqldetalle = pg_query('SELECT * FROM  v_ventas_detalles WHERE ven_cod=' . $cab['ven_cod']);
        $detalles = pg_fetch_all($sqldetalle);

        foreach ($detalles as $key2 => $detalle) {
            $datos['data'][$key]['detalle'][$key2]['ven_cod'] = $detalle['ven_cod'];
            $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['item_cod'];
            $datos['data'][$key]['detalle'][$key2]['item'] = $detalle['item_desc'];
            $datos['data'][$key]['detalle'][$key2]['marca'] = $detalle['mar_desc'];
            $datos['data'][$key]['detalle'][$key2]['exenta'] = $detalle['exenta'];
            $datos['data'][$key]['detalle'][$key2]['grav5'] = $detalle['grav5'];
            $datos['data'][$key]['detalle'][$key2]['grav10'] = $detalle['grav10'];
            $datos['data'][$key]['detalle'][$key2]['cboiddeposito'] = $detalle['dep_desc'];
            $datos['data'][$key]['detalle'][$key2]['cantidad'] = $detalle['ven_cantidad'];
            $datos['data'][$key]['detalle'][$key2]['precio'] = $detalle['ven_precio'];
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
