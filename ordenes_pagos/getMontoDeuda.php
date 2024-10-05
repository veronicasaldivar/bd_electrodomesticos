<?php
    require '../clases/conexion.php';
    $codigo = $_GET['codigo'];
    $con = new conexion();
    $con->conectar();
    $sql = pg_query("SELECT  SUM(cuotas_monto) AS monto_deuda, SUM(cuotas_saldo) AS monto_saldo FROM cuentas_pagar_fact_varias WHERE fact_var_cod = '$codigo' ");

    $verificar = pg_fetch_assoc($sql);
    if(!empty($verificar)){
        $array[] =  array('monto_deuda' => $verificar['monto_deuda'], 'monto_saldo' => $verificar['monto_saldo']);
        $data = array('data' => $array);
        $json = json_encode($data);
        print_r(utf8_encode($json));
    }else{
        $error = "error";
        echo $error;
    }

?>