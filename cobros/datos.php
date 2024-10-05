
<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * FROM v_ventas_cab WHERE ven_estado !='ANULADO' ORDER BY ven_cod DESC");
$tabla = pg_fetch_all($sql);
/*
$button_facturar  = '<a href=\'../informes/imp_facturasventas?id=14\' target=\'_blank\' class=\'btn btn-success btn-circle facturar pull-right\' id=\'print\'  >Facturar</a>';
$button = $button_facturar .''. $button_cobrar;
*/
$button_cobrar = '<button type=\'button\' class=\'btn btn-primary btn-circle cobrar pull-right\' title=\'Cobrar\'>Cobrar</button>';
if (!empty($tabla)) {
   $datos['data'] = [];
   $cobrado;
   $button_facturar = '';

   foreach ($tabla as $key => $cab) {

      $cobrado = verificarCobro($cab['ven_cod']);
      if ($cobrado == true) {
         $button_facturar  = '<a href=\'../informes/imp_facturasventas.php?id=' . $cab['ven_cod'] . '\' target=\'_blank\' class=\'btn btn-success btn-circle facturar pull-right\' id=\'print\'>Facturar</a>';
      } else {
         $button_facturar = '';
      };

      $ventacab =  $datos['data'][$key]['codigo'] = $cab['ven_cod'];
      $datos['data'][$key]['ffactura'] = $cab['ven_fecha'];
      $datos['data'][$key]['cliente'] = $cab['cli_nom'];
      $datos['data'][$key]['ruc'] = $cab['cli_ruc'];
      $datos['data'][$key]['tipofactcod'] = $cab['tipo_fact_desc'];
      $datos['data'][$key]['cuotas'] = $cab['ven_cuotas'];
      $datos['data'][$key]['estado'] = $cab['ven_estado'];
      $datos['data'][$key]['usuario'] = $cab['usu_name'];
      $datos['data'][$key]['acciones'] =  $button_cobrar . '' . $button_facturar;

      // $sqldetalle = pg_query('select * from v_compras_det ');//select * from v_compras_det where comp_cod=2
      $sqldetalle = pg_query("SELECT  * from  cuentas_cobrar where  ven_cod = $ventacab order by ctas_cobrar_nro ");
      $detalles = pg_fetch_all($sqldetalle);

      foreach ($detalles as $key2 => $detalle) {
         $datos['data'][$key]['detalle'][$key2]['ven_cod'] = $detalle['ven_cod'];
         $datos['data'][$key]['detalle'][$key2]['cuotas'] = $detalle['ctas_cobrar_nro'];
         $datos['data'][$key]['detalle'][$key2]['venc'] = $detalle['ctas_venc'];
         $datos['data'][$key]['detalle'][$key2]['monto'] = $detalle['ctas_monto'];
         $datos['data'][$key]['detalle'][$key2]['saldo'] = $detalle['ctas_saldo'];
         $datos['data'][$key]['detalle'][$key2]['estado'] = $detalle['ctas_estado'];
         $datos['data'][$key]['detalle'][$key2]['fecha_cobro'] = $detalle['fecha_cobro'];
      }
   }
} else {
   $datos['data'] = [];
   $datos['data']['codigo'] = '-';
   $datos['data']['nro'] = '-';
   $datos['data']['ffactura'] = '-';
   $datos['data']['cliente'] = '-';
   $datos['data']['plazo'] = '-';
   $datos['data']['cuotas'] = '-';
   $datos['data']['estado'] = '-';
   $datos['data']['usuario'] = '-';
   $datos['data']['acciones'] = '-';
}

function verificarCobro($ventacod)
{
   $res = pg_query("SELECT ven_cod FROM cobros_det WHERE ven_cod = '$ventacod' ");
   $rs = pg_fetch_all($res);
   if (!empty($rs)) {
      return true;
   } else {
      return false;
   }
};

echo json_encode($datos);
return json_encode($datos);

?>



















