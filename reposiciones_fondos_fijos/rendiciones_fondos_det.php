<?php
require "../clases/conexion.php";
$cod = $_POST["codigo"];
$con = new conexion();
$con->conectar();
$sql = pg_query("select *  from v_rendiciones_fondos_fijos_det where rendicion_fondo_fijo_cod = $cod ");
$rendiciones = pg_fetch_all($sql);

$exenta = 0;
$gravada5 = 0;
$gravada10 = 0;

//$ped = pg_fetch_assoc($sql);

$data['filas'] = '';
foreach ($rendiciones as $key => $rendicion) {
    $data['filas'] .= '<tr>';
    $data['filas'] .= '<td style="text-align: center;">' . $rendicion['rendicion_fondo_fijo_cod'] . '</td>';
    $data['filas'] .= '<td style="text-align: center;">' . $rendicion['rubro_cod'] . '</td>';
    $data['filas'] .= '<td style="text-align: left;">' . $rendicion['rubro_desc'] . '</td>';
    $data['filas'] .= '<td style="text-align: center;">' . $rendicion['fact_monto'] . '</td>';
    $data['filas'] .= '<td style="text-align: center;">' . $rendicion['tipo_imp_cod'] . '-' . $rendicion['tipo_imp_desc'] . '</td>';
    $data['filas'] .= '<td style="text-align: right;">' . $rendicion['grav10'] . '</td>';
    $data['filas'] .= '<td style="text-align: right;">' . $rendicion['grav5'] . '</td>';
    $data['filas'] .= '<td style="text-align: right;">' . $rendicion['exentas'] . '</td>';
    $data['filas'] .= '<td style="text-align: right;">' . '' . '</td>';
    $data['filas'] .= '</tr>';
}

echo json_encode($data);
return json_encode($data);
