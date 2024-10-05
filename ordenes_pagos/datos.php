
<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_facturas_varias_cab WHERE fact_var_estado !='ANULADO' ORDER BY fact_var_cod DESC");
$tabla = pg_fetch_all($sql);
/*
$button_facturar  = '<a href=\'../informes/imp_facturasventas?id=14\' target=\'_blank\' class=\'btn btn-success btn-circle facturar pull-right\' id=\'print\'  >Facturar</a>';
$button = $button_facturar .''. $button_cobrar;
*/
$button_cobrar = '<button type=\'button\' class=\'btn btn-primary btn-circle cobrar pull-right\' title=\'Pagar\'>Pagar</button>';
if (!empty($tabla)) {
   $datos['data'] = [];
   $cobrado;
   $button_facturar = '';

   foreach ($tabla as $key => $cab) {
      $venfactvarias =  $datos['data'][$key]['codigo'] = $cab['fact_var_cod'];
      $datos['data'][$key]['ffactura'] = $cab['factura_varias_fecha'];
      $datos['data'][$key]['proveedor'] = $cab['prov_nombre'];
      $datos['data'][$key]['ruc'] = $cab['prov_ruc'];
      $datos['data'][$key]['tipofactcod'] = $cab['tipo_fact_desc'];
      $datos['data'][$key]['cuotas'] = $cab['cuotas'];
      $datos['data'][$key]['plazo'] = $cab['plazo'];
      $datos['data'][$key]['nro_factura'] = $cab['nro_factura'];
      $datos['data'][$key]['estado'] = $cab['fact_var_estado'];
      $datos['data'][$key]['usuario'] = $cab['usu_name'];
      $datos['data'][$key]['acciones'] =  $button_cobrar . '' . $button_facturar;

      // $sqldetalle = pg_query('select * from v_compras_det ');//select * from v_compras_det where comp_cod=2
      $sqldetalle = pg_query("SELECT to_char(cuotas_fecha_pago, 'dd/mm/yyyy HH24:MI:SS') as fecha_pago, * from cuentas_pagar_fact_varias where fact_var_cod = $venfactvarias order by ctas_pagar_cuota_nro ");
      $detalles = pg_fetch_all($sqldetalle);

      foreach ($detalles as $key2 => $detalle) {
         $datos['data'][$key]['detalle'][$key2]['fact_var_cod'] = $detalle['fact_var_cod'];
         $datos['data'][$key]['detalle'][$key2]['cuotas'] = $detalle['ctas_pagar_cuota_nro'];
         $datos['data'][$key]['detalle'][$key2]['venc'] = $detalle['ctas_pagar_fact_venc'];
         $datos['data'][$key]['detalle'][$key2]['monto'] = $detalle['cuotas_monto'];
         $datos['data'][$key]['detalle'][$key2]['saldo'] = $detalle['cuotas_saldo'];
         $datos['data'][$key]['detalle'][$key2]['estado'] = $detalle['cuotas_estado'];
         if (isset($detalle['cuotas_fecha_pago'])) {
            $datos['data'][$key]['detalle'][$key2]['cuotas_fecha_pago'] = $detalle['fecha_pago'];
         } else {
            $datos['data'][$key]['detalle'][$key2]['cuotas_fecha_pago'] = '-';
         }
      }
   }
} else {
   $datos['data'] = [];
   $datos['data']['codigo'] = '-';
   $datos['data']['nro'] = '-';
   $datos['data']['ffactura'] = '-';
   $datos['data']['proveedor'] = '-';
   $datos['data']['plazo'] = '-';
   $datos['data']['cuotas'] = '-';
   $datos['data']['estado'] = '-';
   $datos['data']['usuario'] = '-';
   $datos['data']['acciones'] = '-';
}

echo json_encode($datos);
return json_encode($datos);

?>



















