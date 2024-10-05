<?php
require '../clases/conexion.php';

$cn = new conexion();
$cn->conectar();
$sql = pg_query('SELECT * FROM v_boletas_depositos ORDER BY fecha_deposito desc');
$asignaciones = pg_fetch_all($sql);

// $button_editar = '<button type=\'button\' class=\'btn btn-info btn-circle pull-right editar\' data-toggle=\'modal\' data-target=\'#modal_basic\' data-placement=\'top\' title=\'Editar\'><i class=\'fa fa-edit\'></i></button>';

$datos['data'] = [];
foreach ($asignaciones as $key => $asignacion) {
	$datos['data'][$key]['codigo'] = $asignacion['movimiento_nro'];
	$datos['data'][$key]['ent'] = $asignacion['ent_nom'];
	$datos['data'][$key]['cuenta'] = $asignacion['cuenta_corriente_nro'];
	$datos['data'][$key]['fecha'] = $asignacion['fecha_deposito'];
	$datos['data'][$key]['monto'] = $asignacion['monto'];
	$datos['data'][$key]['suc'] = $asignacion['suc_nom'];
	$datos['data'][$key]['usuario'] = $asignacion['usu_name'];
	$datos['data'][$key]['estado'] = $asignacion['estado'];
	$datos['data'][$key]['acciones'] = 																		
		'<button type=\'button\' class=\'btn btn-danger btn-circle eliminar pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Desactivar\'><i class=\'fa fa-times\'></i></button>';
}
echo json_encode($datos);
return json_encode($datos);
