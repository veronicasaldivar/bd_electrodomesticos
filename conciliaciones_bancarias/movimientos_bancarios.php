<?php
require "../clases/conexion.php";

$entcod = $_POST['entcod']; 
$cuenta = $_POST['cuenta']; 
$tipomov = $_POST['tipomov']; 
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

if($tipomov === 'TODOS'){
	$tipo = "1 = 1";
}else if($tipomov === 'CREDITO'){
	$tipo = "mov_tipo = 'CREDITO' ";
}else{
	$tipo = "mov_tipo =  'DEBITO'";
}

$con = new conexion();
$con->conectar();
$sql = pg_query("SELECT * FROM v_movimientos_bancarios WHERE ent_cod = $entcod AND cuenta_corriente_cod = $cuenta AND $tipo AND mov_fecha::timestamp BETWEEN '$fechadesde' and '$fechahasta' AND $est ORDER BY mov_fecha::timestamp ");
$movimientos = pg_fetch_all($sql); 

if(!empty($movimientos)){
	$data['filas'] = '';
	$subtotal = 0;
	foreach ($movimientos as $key => $mov) {
		$subtotal += $mov['mov_monto'];
		$total= $subtotal;
		
		$data['total'] = number_format($total,0,',','.');
		$data['filas'] .= '<tr>';
		$data['filas'] .= '<td>'.$mov['ent_cod'].' - '.$mov['ent_nom'].'</td>';
		$data['filas'] .= '<td>'.$mov['cuenta_corriente_cod'].' - '.$mov['cuenta_corriente_nro'].'</td>';
		$data['filas'] .= '<td>'.$mov['movimiento_nro'].'</td>';
		$data['filas'] .= '<td>'.$mov['mov_tipo'].'</td>';
		$data['filas'] .= '<td>'.$mov['mov_fecha'].'</td>';
		$data['filas'] .= '<td>'.$mov['mov_monto'].'</td>';
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