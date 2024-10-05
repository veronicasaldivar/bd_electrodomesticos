<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
// $sql = pg_query("SELECT * FROM v_pedidos_ventas_cab WHERE ped_estado='PENDIENTE' ORDER BY ped_vcod DESC");
$sql = pg_query("SELECT * FROM v_pedidos_ventas_cab ORDER BY ped_vcod DESC");
$pedventas = pg_fetch_all($sql);

if (!empty($pedventas)) {
    $button_borrar = '<button type=\'button\' class=\'btn btn-primary  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Borrar\'><i class=\'fa fa-minus\'></i></button>';
    $button = $button_borrar;

    $datos['data'] = [];
    foreach ($pedventas as $key => $pedidoventas) {
        $datos['data'][$key]['cod'] = $pedidoventas['ped_vcod'];
        $datos['data'][$key]['fun_nom'] = $pedidoventas['fun_nom'];
        $datos['data'][$key]['nro'] = $pedidoventas['ped_nro'];
        $datos['data'][$key]['fecha'] = $pedidoventas['ped_fecha'];
        $datos['data'][$key]['cli'] = $pedidoventas['cli_nom'];
        $datos['data'][$key]['ruc'] = $pedidoventas['cli_ruc'];
        $datos['data'][$key]['usu'] = $pedidoventas['usu_name'];
        $datos['data'][$key]['estado'] = $pedidoventas['ped_estado'];
        $datos['data'][$key]['acciones'] = $button;

        $sqldetalle = pg_query('select * from v_pedidos_ventas_det where ped_vcod=' . $pedidoventas['ped_vcod']);
        $detalles = pg_fetch_all($sqldetalle);

        foreach ($detalles as $key2 => $detalle) {

            $datos['data'][$key]['detalle'][$key2]['cod'] = $detalle['ped_vcod'];
            $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['item_cod'];
            $datos['data'][$key]['detalle'][$key2]['descripcion'] = $detalle['item_desc'];
            $datos['data'][$key]['detalle'][$key2]['marca'] = $detalle['mar_desc'];
            $datos['data'][$key]['detalle'][$key2]['cantidad'] = $detalle['ped_cantidad'];
            $datos['data'][$key]['detalle'][$key2]['precio'] = $detalle['ped_precio'];
        }
    }
} else {
    $datos['data']['cod'] = '-';
    $datos['data']['fun_nom'] = '-';
    $datos['data']['nro'] = '-';
    $datos['data']['fecha'] = '-';
    $datos['data']['cli'] = '-';
    $datos['data']['ruc'] = '-';
    $datos['data']['usu'] = '-';
    $datos['data']['estado'] = '-';
    $datos['data']['acciones'] = '-';
}

echo json_encode($datos);
return json_encode($datos);
