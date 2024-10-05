<?php
require "../clases/conexion.php";
/*
$fechadesde = '01-07-2020'; 
$fechahasta = '30-09-2024'; 
$estado = 'TODOS'; 
*/


$fechadesde = $_POST['fechadesde']; 
$fechahasta = $_POST['fechahasta']; 
$estado = $_POST['estado'];

if($estado === 'TODOS'){
	$est = "1 = 1";
}else if($estado === 'CONCILIADO'){
	$est = "conciliar = 'CONCILIADO' ";
}else{
	$est = "conciliar is null";
}

$con = new conexion();
$con->conectar();
$sql = pg_query("SELECT * FROM v_cobros_tarjetas WHERE cobro_fecha::timestamp BETWEEN '$fechadesde' and '$fechahasta' AND $est ORDER BY cobro_fecha::timestamp, cod_auto");
$movimientos = pg_fetch_all($sql); 

if(!empty($movimientos)){
	$data['filas'] = '';
	$subtotal = 0;
	foreach ($movimientos as $key => $mov) {
		$subtotal += $mov['tarj_monto'];
		$total= $subtotal;
		
		$data['total'] = number_format($total,0,',','.');
		$data['filas'] .= '<tr>';
		$data['filas'] .= '<td>'.$mov['cobro_cod'].'</td>';
		$data['filas'] .= '<td>'.$mov['ent_nom'].'</td>';
		$data['filas'] .= '<td>'.$mov['mar_tarj_desc'].'</td>';
		$data['filas'] .= '<td>'.$mov['cob_tarj_nro'].'</td>';
		$data['filas'] .= '<td>'.$mov['cod_auto'].'</td>';
		$data['filas'] .= '<td>'.$mov['cobro_fecha'].'</td>';
		$data['filas'] .= '<td>'.$mov['tarj_monto'].'</td>';
		if($mov['conciliar'] !== null){
			$data['filas'] .= '<td class="toogleConciliar"><input type="button" value="Desconciliar" id="bf" class="bf" style="background: pink; border-color: pink; width: 95px; display: block;  min-width: 80px; color: white;"/></td>';
		}else{
			$data['filas'] .= '<td class="toogleConciliar"><input type="button" value="Conciliar" id="bf" class="bf" style="background: #394D5F;  width: 95px; display: block; color: white;"/></td>';
		}
		$data['filas'] .= '</tr>';
	}
	echo json_encode($data);
	return json_encode($data);
}else{
	echo "error";
	return;
}

?>