<?php
    require '../clases/conexion.php';
    $proveedor = $_GET['proveedor'];

    $con = new conexion();
    $con->conectar();
    $sql = pg_query(" SELECT * FROM facturas_varias_cab WHERE fact_var_estado = 'PROCESADO' AND prov_cod = '$proveedor' ORDER BY fact_var_cod DESC");

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