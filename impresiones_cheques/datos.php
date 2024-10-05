<?php
require '../clases/conexion.php';

$cn = new conexion();
$cn->conectar();
$sql = pg_query('SELECT * FROM v_pagos_cheques ORDER BY movimiento_nro desc');
$impresiones = pg_fetch_all($sql);

$button_editar = '<button type=\'button\' class=\'btn btn-info btn-circle pull-right editar\' title=\'Editar\'><i class=\'fa fa-edit\'></i></button>';

//$button_imprimir = '<button type=\'button\' class=\'btn btn-dark btn-circle pull-right imprimir\' title=\'Imprimir\'><i class=\'fa fa-print\'></i></button>';
$button_imprimir = '<a href=\'../informes/imp_impresion_cheque.php?id=3_1_45\' target=\'_blank\' class=\'btn btn-dark \' id=\'print\' ><i class=\'fa fa-print\'></i></a>';

$button_entregar = '<button type=\'button\' class=\'btn btn-success btn-circle pull-right entregar\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Entregar\'><i class=\'fa fa-check\'></i></button>';

$button = $button_entregar . ' ' . $button_editar;

if (!empty($impresiones)) {
	$datos['data'] = [];
	foreach ($impresiones as $key => $impresion) {
		$id = '../informes/imp_impresion_cheque.php?id='. $impresion['ent_cod'].'_'.$impresion['cuenta_corriente_cod'].'_'.$impresion['movimiento_nro'];

		$datos['data'][$key]['entcod'] = $impresion['ent_cod'] . ' - ' . $impresion['ent_nom'];
		$datos['data'][$key]['cuenta'] = $impresion['cuenta_corriente_cod'] . ' - ' . $impresion['cuenta_corriente_nro'];
		$datos['data'][$key]['movnro'] = $impresion['movimiento_nro'];
		$datos['data'][$key]['cheque_nro'] = $impresion['chque_num'];
		$datos['data'][$key]['cheque_monto'] = $impresion['monto_cheque'];
		$datos['data'][$key]['fecha'] = $impresion['fecha_pago'];
		$datos['data'][$key]['estado'] = $impresion['estado'];
		if ($impresion['librador'] !== null) {
			$datos['data'][$key]['librador'] = $impresion['librador'];
		} else {
			$datos['data'][$key]['librador'] = $impresion['librador_por_defecto'];
		}
		$datos['data'][$key]['acciones'] = $button .' '."<a href='{$id}' target='_blank' class='btn btn-dark btn-circle pull-right' id='print' ><i class='fa fa-print'></i></a>";
	}
} else {
	$datos['data'] = [];
	$datos['data']['entcod'] = '-';
	$datos['data']['cuenta'] = '-';
	$datos['data']['movnro'] = '-';
	$datos['data']['cheque_nro'] = '-';
	$datos['data']['monto'] = '-';
	$datos['data']['fecha'] = '-';
	$datos['data']['estado'] = '-';
	$datos['data']['librador'] = '-';
	$datos['data']['acciones'] = '-';
}
echo json_encode($datos);
return json_encode($datos);
