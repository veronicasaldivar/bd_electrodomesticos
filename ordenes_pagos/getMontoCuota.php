<?php
    require '../clases/conexion.php';
    $codigo = $_GET['codigo'];
    $cuotas = $_GET['cuotas'];

    $con = new conexion();
    $con->conectar();
    $sql = pg_query(" SELECT (cuotas_monto * '$cuotas' ) as monto FROM cuentas_pagar_fact_varias WHERE fact_var_cod = '$codigo' limit 1 ");
    
    $verificar = pg_fetch_assoc($sql);
    if(!empty($verificar)){
        echo $verificar['monto'];
    }else{
        $error = "error";
        echo $error;
    }

?>