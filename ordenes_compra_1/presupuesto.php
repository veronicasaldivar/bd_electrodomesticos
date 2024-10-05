<?php
require "../clases/conexion.php";
$presupuestoId = $_POST['presupuestoId']; 
$presupuestoProv = $_POST['presupuestoProv']; 
$presupuestoFecha = $_POST['presupuestoFecha']; 

$con = new conexion();
$con->conectar();
$sql = pg_query("SELECT * FROM v_presupuestos_proveedores_det WHERE pre_prov_cod = '$presupuestoId' AND prov_cod = '$presupuestoProv' AND pre_prov_fecha = '$presupuestoFecha' ");

$presupuestos = pg_fetch_all($sql); 

if(!empty($presupuestos)){
	$data['filas'] = '';
	$subtotal = 0;
	foreach ($presupuestos as $key => $presupuesto) {
		$subtotal = $presupuesto['pre_prov_precio']*$presupuesto['pre_prov_cantidad'];
		$data['total'] = number_format($subtotal,0,',','.');
		$data['filas'] .= '<tr>';
		$data['filas'] .= '<td>'.$presupuesto['item_cod'].'</td>';
		$data['filas'] .= '<td>'.$presupuesto['item_desc'].'</td>';
		$data['filas'] .= '<td>'.$presupuesto['mar_cod'].'-'.$presupuesto['mar_desc'].'</td>';
		$data['filas'] .= '<td>'.number_format($presupuesto['pre_prov_cantidad'],0,',','').'</td>';
		
		$data['filas'] .= '<td>'.$presupuesto['pre_prov_precio'].'</td>';
		$data['filas'] .= '<td>'.number_format($subtotal,0,',','').'</td>';
		$data['filas'] .= '<td class="eliminar"><input type="button" value="Х" id="bf"   class="bf"  style="background:pink; color: black;"/></td>';
		$data['filas'] .= '</tr>';
	}
	echo json_encode($data);
	return json_encode($data);

}else{
	$error = 'error';
	echo $error;
}


?>