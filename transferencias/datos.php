
<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_transferencias_cab  ORDER BY trans_cod DESC");

$transferencia = pg_fetch_all($sql);
$rows = pg_num_rows($sql);

$button_anular = '<button type=\'button\' class=\'btn btn-primary btn-circle btn-lg  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';

$button = $button_anular;

if (!empty($transferencia)) {
    $datos['data'] = [];
    foreach ($transferencia as $key => $cab) {
        $datos['data'][$key]['nro'] = $cab['trans_cod'];
        $datos['data'][$key]['fecha'] = $cab['fecha_trans'];
        $datos['data'][$key]['empresa'] = $cab['emp_nom'];
        $datos['data'][$key]['funcionario'] = $cab['fun_nom'];
        $datos['data'][$key]['vehiculo'] = $cab['veh_desc'];
        $datos['data'][$key]['sucursal'] = $cab['suc_nom'];
        $datos['data'][$key]['origen'] = $cab['suc_origen'];
        $datos['data'][$key]['destino'] = $cab['suc_destino'];
        $datos['data'][$key]['estado'] = $cab['trans_estado'];
        $datos['data'][$key]['acciones'] =  $button;

        $sqldetalle = pg_query('SELECT * FROM v_transferencias_det WHERE trans_cod =' . $cab['trans_cod']);
        $detalles = pg_fetch_all($sqldetalle);

        foreach ($detalles as $key2 => $detalle) {
            $datos['data'][$key]['detalle'][$key2]['nro'] = $detalle['trans_cod'];
            $datos['data'][$key]['detalle'][$key2]['item'] = $detalle['item_desc'];
            $datos['data'][$key]['detalle'][$key2]['marca'] = $detalle['mar_desc'];
            $datos['data'][$key]['detalle'][$key2]['cantidad'] = $detalle['trans_cantidad'];
            $datos['data'][$key]['detalle'][$key2]['recibido'] = $detalle['trans_cant_recibida'];
            $datos['data'][$key]['detalle'][$key2]['deposito'] = $detalle['d_origen_desc'];
            $datos['data'][$key]['detalle'][$key2]['depositod'] = $detalle['d_destino_desc'];
        }
    }
} else {
    $datos['data'] = [];
    $datos['data']['nro'] = '-';
    $datos['data']['fecha'] = '-';
    $datos['data']['empresa'] = '-';
    $datos['data']['funcionario'] = '-';
    $datos['data']['vehiculo'] = '-';
    $datos['data']['sucursal'] = '-';
    $datos['data']['origen'] = '-';
    $datos['data']['destino'] = '-';
    $datos['data']['estado'] = '-';
    $datos['data']['acciones'] =  '-';
}
echo  json_encode($datos);
return json_encode($datos);

?>



















