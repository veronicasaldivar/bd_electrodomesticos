<?php
require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("select * from v_notas_ventas_cab order by nota_ven_cod desc ");
$notasventas = pg_fetch_all($sql);

$button_borrar = '<button type=\'button\' class=\'btn btn-primary btn-circle   delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';
$button = $button_borrar;

if (!empty($notasventas)) {
    $datos['data'] = [];
    foreach ($notasventas as $key => $notasventas) {
        $notanro = $notasventas['nota_ven_cod'];
        $proveedor = $notasventas['nota_ven_nro_fact'];
        $timbrado = $notasventas['nota_ven_fecha'];
        $tiponota = $notasventas['nota_ven_tipo'];
        $motivonota = $notasventas['nota_ven_motivo'];

        $datos['data'][$key]['notanro'] = $notasventas['nota_ven_cod'];
        $datos['data'][$key]['nro'] = $notasventas['nota_ven_nro_fact'];
        $datos['data'][$key]['cod'] = $notasventas['cli_nom'];
        $datos['data'][$key]['fecha'] = $notasventas['nota_ven_fecha'];
        $datos['data'][$key]['tipo_factura'] = $notasventas['nota_ven_tipo'];
        $datos['data'][$key]['estado'] = $notasventas['nota_ven_estado'];
        $datos['data'][$key]['acciones'] =  $button;

        if ($tiponota === "CREDITO" && $motivonota === "DEVOLUCION") {

            $sqldetalle = pg_query("SELECT * FROM v_notas_ventas_detalles WHERE nota_ven_cod = '$notanro' ");
            $detalles = pg_fetch_all($sqldetalle);

            if(!empty($detalles)){
                foreach ($detalles as $key2 => $detalle) {
                    $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['item_cod'];
                    $datos['data'][$key]['detalle'][$key2]['descripcion'] = $detalle['item_desc'];
                    $datos['data'][$key]['detalle'][$key2]['marca'] = $detalle['mar_cod'] . ' - ' . $detalle['mar_desc'];
                    $datos['data'][$key]['detalle'][$key2]['cantidad'] = $detalle['nota_ven_cant'];
                    $datos['data'][$key]['detalle'][$key2]['precio'] = $detalle['nota_ven_precio'];
                }
            }
        } elseif ($tiponota == "DEBITO") {
            $datos['data'][$key]['detalle']['tipo'] = 'debito';
            $datos['data'][$key]['detalle']['codigo'] = '-';
            $datos['data'][$key]['detalle']['descripcion'] = '-';
            $datos['data'][$key]['detalle']['marca'] = '-';
            $datos['data'][$key]['detalle']['cantidad'] = '-';
            $datos['data'][$key]['detalle']['precio'] = '-';
        }
    }
} else {
    $datos['data'] = [];
    $datos['data']['notanro'] = '-';
    $datos['data']['nro'] = '-';
    $datos['data']['cod'] = '-';
    $datos['data']['fecha'] = '-';
    $datos['data']['tipo_factura'] = '-';
    $datos['data']['estado'] = '-';
    $datos['data']['acciones'] = '-';
}

echo  json_encode($datos);
return json_encode($datos);
