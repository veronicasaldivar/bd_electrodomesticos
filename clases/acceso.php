<?php
require "./conexion.php";
require 'funciones.php';
require 'sesion.php';

$usu = isset($_POST["usu"]) ? $_POST["usu"] : "";
$pass = isset($_POST["pass"]) ? $_POST["pass"] : "";

if (empty($usu) || empty($pass)) {
  msg_sesion("Todos los campos son necesarios!!");
  llevame_a("../index.php");
}

$con = new conexion();
$con->conectar();

// Primero verificamos si el usuario está bloqueado
$sql_bloqueo = "SELECT usu_intentos, usu_estado FROM usuarios WHERE usu_name = '$usu'";
$res_bloqueo = $con->select_array($sql_bloqueo);

if ($res_bloqueo) {
  // Si el usuario está bloqueado, mostramos un mensaje y no permitimos el acceso
  if ($res_bloqueo['usu_estado'] == 'BLOQUEADO') {
    msg_sesion("El usuario está bloqueado por demasiados intentos fallidos.");
    llevame_a("../index.php");
    exit(); // Termina el script
  }

  // Verificamos el usuario y la contraseña
  $sql = "SELECT * FROM v_usuarios WHERE usu_name = '$usu' AND usu_pass = MD5('$pass')";
  $num = $con->contar($sql);

  if ($num == 1) {
    // Login exitoso, reiniciar intentos y establecer usuario como "ONLINE"
    $res = $con->select_array($sql);
    
    $_SESSION["usu"] = $usu;
    $_SESSION["pass"] = $pass;
    $_SESSION["id"] = $res["usu_cod"];
    $_SESSION["fun_cod"] = $res["fun_cod"];
    $_SESSION["fun_nom"] = $res["fun_nom"];
    $_SESSION["emp_cod"] = $res["emp_cod"];
    $_SESSION["emp_nom"] = $res["emp_nom"];
    $_SESSION["emp_ruc"] = $res["emp_ruc"];
    $_SESSION["suc_cod"] = $res["suc_cod"];
    $_SESSION["suc_nom"] = $res["suc_nom"];
    $_SESSION["gru_id"] = $res["gru_id"];

    // Actualizar estado del usuario a "ONLINE" y resetear los intentos
    $con->query("UPDATE usuarios SET usu_estado = 'ONLINE', usu_intentos = 0 WHERE usu_name = '$usu';");
    msg_sesion("Ha iniciado sesión correctamente.");
    llevame_a("http://localhost/electrodomesticos/controles/inicio.php");
  } else {
    // Login fallido, aumentar contador de intentos
    $intentos = $res_bloqueo['usu_intentos'] + 1;

    // Si supera los 3 intentos, bloquear al usuario
    if ($intentos >= 3) {
      $con->query("UPDATE usuarios SET usu_estado = 'BLOQUEADO', usu_intentos = $intentos WHERE usu_name = '$usu';");
      msg_sesion("Has superado el número máximo de intentos. El usuario ha sido bloqueado.");
    } else {
      $con->query("UPDATE usuarios SET usu_intentos = $intentos WHERE usu_name = '$usu';");
      msg_sesion("Contraseña incorrecta. Intentos fallidos: $intentos.");
    }
    
    llevame_a("../index.php");
  }
} else {
  msg_sesion("El nombre de usuario no existe.");
  llevame_a("../index.php");
}
?>

