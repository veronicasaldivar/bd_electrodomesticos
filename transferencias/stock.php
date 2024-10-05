<?php
require '../clases/conexion.php';
$dep = $_GET['dep'];
$item = $_GET['item'];
$mar = $_GET['mar'];

$con = new conexion();
$con->conectar();

$sql= pg_query("SELECT stock_cantidad from stock where item_cod = '$item' and mar_cod = '$mar' and dep_cod = '$dep' ");
$rs = pg_fetch_assoc($sql);

if(!empty($rs)){
    echo $rs['stock_cantidad'];

}else{
    echo 0;
}
