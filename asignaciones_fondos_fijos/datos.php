<?php
require '../clases/conexion.php';

$cn = new conexion();
$cn->conectar();
$sql = pg_query('SELECT * FROM v_asignacion_fondo_fijos ORDER BY asignacion_responsable_cod desc');
$asignaciones = pg_fetch_all($sql);

$button_editar = '<button type=\'button\' class=\'btn btn-info btn-circle pull-right editar\' title=\'Editar\'><i class=\'fa fa-edit\'></i></button>';

$button_eliminar = '<button type=\'button\' class=\'btn btn-danger btn-circle eliminar pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Desactivar\'><i class=\'fa fa-times\'></i></button>';

$button = $button_eliminar .' '.$button_editar;

if (!empty($asignaciones)) {
	$datos['data'] = [];
	foreach ($asignaciones as $key => $asignacion) {
		$datos['data'][$key]['codigo'] = $asignacion['asignacion_responsable_cod'];
		$datos['data'][$key]['fecha'] = $asignacion['fecha_asignacion'];
		$datos['data'][$key]['res'] = $asignacion['fun_res_nom'];
		$datos['data'][$key]['caja'] = $asignacion['caja_desc'];
		$datos['data'][$key]['monto'] = $asignacion['monto_asignado'];
		$datos['data'][$key]['suc'] = $asignacion['suc_nom'];
		$datos['data'][$key]['usuario'] = $asignacion['usu_name'];
		$datos['data'][$key]['estado'] = $asignacion['asignacion_estado'];
		$datos['data'][$key]['acciones'] = $button;
	}
} else {
	$datos['data'] = [];
	$datos['data']['codigo'] = '-';
	$datos['data']['fecha'] = '-';
	$datos['data']['res'] = '-';
	$datos['data']['caja'] = '-';
	$datos['data']['monto'] = '-';
	$datos['data']['suc'] = '-';
	$datos['data']['usuario'] = '-';
	$datos['data']['estado'] = '-';
	$datos['data']['acciones'] = '-';
}
echo json_encode($datos);
return json_encode($datos);
