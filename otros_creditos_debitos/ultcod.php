<?php
 require '../clases/conexion.php';
 $con = new conexion();
 $con->conectar();
 $sql = pg_query("select coalesce(max(otro_deb_cred_ban_cod),0)+1 as nro from otros_cred_deb_bancarios_cab");
 $rs = pg_fetch_assoc($sql);
 echo $rs["nro"];
?>