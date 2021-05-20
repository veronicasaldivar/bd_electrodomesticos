<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("SELECT * from v_compras_cab order by comp_fecha::timestamp desc");
$compras = pg_fetch_all($sql);
                                                                                          

$button_borrar = '<button type=\'button\' class=\'btn btn-primary btn-circle   delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';



$button = $button_borrar;



$datos['data']=[];
foreach($compras as $key => $compras){
        $proveedor = $compras['prov_cod'];
        $timbrado = $compras['prov_timb_nro'];
        $factura = $compras['nro_factura'];

        $datos['data'][$key]['cod'] = $compras['prov_cod'];
        $datos['data'][$key]['nro'] = $compras['prov_timb_nro'];
        $datos['data'][$key]['nro_factura'] = $compras['nro_factura'];
        $datos['data'][$key]['fecha'] = $compras['comp_fecha_factura'];
        $datos['data'][$key]['proveedor'] = $compras['prov_nombre'];
        $datos['data'][$key]['ruc'] = $compras['prov_ruc'];
      //  $datos['data'][$key]['ruc'] = $compras['emp_ruc'];
        // $datos['data'][$key]['pro_dir'] = $compras['prov_dir'];
        // $datos['data'][$key]['pro_email'] = $compras['prov_email'];
        // $datos['data'][$key]['fun_cod'] = $compras['fun_cod'];
        //$datos['data'][$key]['usu_cod'] = $compras['usu_cod'];
        $datos['data'][$key]['tipo_factura'] = $compras['tipo_fact_desc'];
        $datos['data'][$key]['plazo'] = $compras['comp_plazo'];
        $datos['data'][$key]['cuotas'] = $compras['comp_cuotas'];
        $datos['data'][$key]['estado'] = $compras['comp_estado'];
        $datos['data'][$key]['acciones'] =  $button;

        // $sqldetalle = pg_query(" SELECT * from v_compras_det where prov_cod = '"+$compras['prov_cod']+"' " + " AND prov_timb_nro = ' "+$compras['prov_timb_nro']+"' +  " and nro_factura = '"+$compras['nro_factura']+"'  );

        $sqldetalle = pg_query("SELECT * from v_compras_det where prov_cod = '$proveedor' AND prov_timb_nro = '$timbrado' AND nro_factura = '$factura' " );

        // $sqldetalle = pg_query('SELECT * from v_compras_det where prov_cod = \'.$compras[\'prov_cod\'].\' AND prov_timb_nro = \'.$compras[\'prov_timb_nro\'].\' and nro_factura = \'.$compras[\'nro_factura\'].' );
        $detalles = pg_fetch_all($sqldetalle);
        
        foreach ($detalles as $key2 => $detalle) {
        $datos['data'][$key]['detalle'][$key2]['cod'] = $detalle['nro_factura'];
        $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['item_cod'];
        $datos['data'][$key]['detalle'][$key2]['descripcion'] = $detalle['item_desc'];
        //$datos['data'][$key]['detalle'] = $compras['mar_cod'];
        $datos['data'][$key]['detalle'][$key2]['cantidad'] = $detalle['comp_cantidad'];
        $datos['data'][$key]['detalle'][$key2]['precio'] = $detalle['comp_precio'];
                    
        }

                
}

 echo  json_encode($datos);
 return json_encode($datos);

?>