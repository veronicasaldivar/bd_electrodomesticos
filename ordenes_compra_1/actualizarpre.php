<?php
    require '../clases/conexion.php';
    $pednro = $_POST["pednro"];
    $con = new conexion();
    $con->conectar();
    $sql = pg_query("UPDATE presupuestos_proveedores_cab set pre_prov_estado = 'PROCESADO' where pre_prov_cod = '$pednro' ");
?>