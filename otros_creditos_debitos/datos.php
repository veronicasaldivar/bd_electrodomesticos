<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_otros_cred_deb_bancarios_cab ORDER BY otro_deb_cred_ban_cod DESC");
$otros_creditos_debitos = pg_fetch_all($sql);

if (!empty($otros_creditos_debitos)) {
    $button_borrar = '<button type=\'button\' class=\'btn btn-primary  delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Borrar\'><i class=\'fa fa-minus\'></i></button>';
    $button = $button_borrar;

    $datos['data'] = [];
    foreach ($otros_creditos_debitos as $key => $otroscreditosdebitos) {
        $datos['data'][$key]['cod'] = $otroscreditosdebitos['otro_deb_cred_ban_cod'];
        $datos['data'][$key]['fecha'] = $otroscreditosdebitos['otro_debito_credito_fecha'];
        $datos['data'][$key]['entidad'] = $otroscreditosdebitos['ent_nom'];
        $datos['data'][$key]['cuenta'] = $otroscreditosdebitos['cuenta_corriente_nro'];
        $datos['data'][$key]['usu'] = $otroscreditosdebitos['usu_name'];
        $datos['data'][$key]['estado'] = $otroscreditosdebitos['otro_deb_cred_estado'];
        $datos['data'][$key]['acciones'] = $button;

        $sqldetalle = pg_query('select * from v_otros_cred_deb_bancarios_det where otro_deb_cred_ban_cod=' . $otroscreditosdebitos['otro_deb_cred_ban_cod']);
        $detalles = pg_fetch_all($sqldetalle);

        foreach ($detalles as $key2 => $detalle) {
            $datos['data'][$key]['detalle'][$key2]['cod'] = $detalle['otro_deb_cred_ban_cod'];
            $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['con_deb_cred_ban_cod'];
            $datos['data'][$key]['detalle'][$key2]['descripcion'] = $detalle['con_deb_cred_ban_desc'];
            $datos['data'][$key]['detalle'][$key2]['precio'] = $detalle['otro_deb_cred_monto'];
        }
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
