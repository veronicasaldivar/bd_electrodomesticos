<?php

require '../clases/conexion.php';
$codigo = $_POST["codigo"];
$plazo = $_POST["plazo"];
$cuotas = $_POST["cuotas"];
$suc = $_POST["suc"];
$usu = $_POST["usu"];
$proveedor = $_POST["proveedor"];
$tipo_factura = $_POST["tipo_factura"];
$detalle = $_POST["detalle"];
$ope = $_POST["ope"];
$con = new conexion();
$con->conectar();

$sql = pg_query("select sp_ordenes_compras($codigo,$plazo,$cuotas,$suc,$usu,$proveedor,$tipo_factura,'$detalle',$ope)");
#--ORDEN: ordencod,ordenro,ordenplazo,ordencuotas,succod,usucod,provcod,tipofactcod, detalle[ ultcod,itemcod,ordencantidad,ordenprecio ], operacion
if ($sql) {
    echo  pg_last_notice($con->url) . "_/_notice";
} else {
    echo pg_last_error() . "_/_error";
}
