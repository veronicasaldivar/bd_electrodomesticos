<?php

require '../clases/conexion.php';
$cn = new conexion();
$cn->conectar();
$sql = pg_query("select * from v_notas_compras_cab  ");
$compras = pg_fetch_all($sql);
                                                                                          

$button_borrar = '<button type=\'button\' class=\'btn btn-primary btn-circle   delete pull-right\' data-toggle=\'modal\' data-target=\'#confirmacion\' data-placement=\'top\' title=\'Anular\'><i class=\'fa fa-times\'></i></button>';



$button = $button_borrar;



$datos['data']=[];
foreach($compras as $key => $compras){
        $notanro = $compras['nota_com_nro'];
        $proveedor = $compras['prov_cod'];
        $timbrado = $compras['prov_timb_nro'];
        $factura = $compras['nro_factura'];
        $tiponota = $compras['nota_com_tipo'];

        $datos['data'][$key]['notanro'] = $compras['nota_com_nro'];
        $datos['data'][$key]['cod'] = $compras['prov_cod'];
        $datos['data'][$key]['nro'] = $compras['prov_timb_nro'];
        $datos['data'][$key]['nro_factura'] = $compras['nro_factura'];
        $datos['data'][$key]['fecha'] = $compras['nota_com_fecha'];
        $datos['data'][$key]['proveedor'] = $compras['prov_nombre'];
        // $datos['data'][$key]['ruc'] = $compras['prov_ruc'];
        $datos['data'][$key]['tipo_factura'] = $compras['nota_com_tipo'];
        $datos['data'][$key]['estado'] = $compras['nota_com_estado'];
        $datos['data'][$key]['acciones'] =  $button;

        // $sqldetalle = pg_query(" SELECT * from v_compras_det where prov_cod = '"+$compras['prov_cod']+"' " + " AND prov_timb_nro = ' "+$compras['prov_timb_nro']+"' +  " and nro_factura = '"+$compras['nro_factura']+"'  );
        
                // $sqldetalle = pg_query('SELECT * from v_compras_det where prov_cod = \'.$compras[\'prov_cod\'].\' AND prov_timb_nro = \'.$compras[\'prov_timb_nro\'].\' and nro_factura = \'.$compras[\'nro_factura\'].' );

        if($tiponota == "CREDITO"){

                $sqldetalle = pg_query("SELECT * from v_notas_compras_det  where nota_com_nro = '$notanro' and  prov_cod = '$proveedor' AND prov_timb_nro = '$timbrado' AND nro_factura = '$factura' " );
                $detalles = pg_fetch_all($sqldetalle);
                
                foreach ($detalles as $key2 => $detalle) {
                        $datos['data'][$key]['detalle'][$key2]['codigo'] = $detalle['item_cod'];
                        $datos['data'][$key]['detalle'][$key2]['descripcion'] = $detalle['item_desc'];
                        $datos['data'][$key]['detalle'][$key2]['marca'] = $detalle['mar_cod'].' - '.$detalle['mar_desc'];
                        $datos['data'][$key]['detalle'][$key2]['cantidad'] = $detalle['nota_com_cant'];
                        $datos['data'][$key]['detalle'][$key2]['precio'] = $detalle['nota_com_precio'];
                            
                }

        }elseif ($tiponota == "DEBITO") {
                $datos['data'][$key]['detalle'][$key2]['tipo'] = 'debito';
                $datos['data'][$key]['detalle'][$key2]['codigo'] = '-';
                $datos['data'][$key]['detalle'][$key2]['descripcion'] = '-';
                $datos['data'][$key]['detalle'][$key2]['marca'] = '-';
                $datos['data'][$key]['detalle'][$key2]['cantidad'] = '-';
                $datos['data'][$key]['detalle'][$key2]['precio'] = '-';
        }

                
}

 echo  json_encode($datos);
 return json_encode($datos);

?>