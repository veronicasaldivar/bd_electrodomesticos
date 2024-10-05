<?php

require "../clases/conexion.php";
$cod = $_POST["cod"];
$con = new conexion();
$con ->conectar();
$sql = pg_query("SELECT mar_desc, mar_cod FROM v_marcas_items WHERE item_cod =  '$cod' ORDER BY mar_cod ");

$verificar = pg_fetch_all($sql);

if(!empty($verificar)){

    while($var = pg_fetch_assoc($sql)){
        $datos[] = $var;
    }
    print_r(json_encode($datos));
}else{
    $error = "error";
    echo $error;
}
?>
