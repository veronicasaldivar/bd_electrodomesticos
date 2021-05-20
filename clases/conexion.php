<?php
class conexion {
    //put your code here
    public $host="localhost";
    public $db="corregido";
    public $puerto="5432";
    public $user="postgres";
    public $password="123";
    public $cone;
    public $url;
    public $consulta;
    public $ultimo;
    //creación del constructor
    function __construct(){
        
    }
    //creación de la función para cargar los valores de la conexión.
    public function cargarValores(){
        $this->cone="host='localhost' dbname='corregido3' port='5432' user='postgres' password='123' ";
    }
    //función que se utilizara al momento de hacer la instancia de la clase
    function conectar(){
        $this->cargarValores();
        $compruebo=$this->url=pg_connect($this->cone);
        if($compruebo){
            return true;
        }else{
            die("Error al conectar con la base de datos: ".pg_last_error());
        }
    }
    //funcion para query
    public function query($query) {
        // Connect to the database
        $con = $this -> conectar();
        // Query the database
        $sql = pg_query($query);
        return $sql;
    }
    //funcion para select
    public function select($query){
        $rows = array();
        $result = $this -> query($query);
        if($result === false) {
            return false;
        }
        while ($row = pg_fetch_array($result)) {
            $rows[] = $row;
        }
        return $rows;
    }
    //funcion select array
    public function select_array($query){
        $result = $this -> query($query);
        $num = pg_num_rows($result);
        $rows = 0;
        while ($row = pg_fetch_array($result)) {
            if ($num == 0){
                $rows = 0;
            }else{
                $rows = $row;
            }
        }
        return $rows;
    }
    //Funcion cantidad de registros
    public function contar($query){
        $contar = $this -> query($query);
        $cantidad = pg_num_rows($contar);
        return $cantidad;
    }
    //funcion ejecutar SP
    public function sp($query){
        $this -> query($query);
        $noticia = pg_last_notice($this->url);
        return str_replace("NOTICE: ","",$noticia);
    }
    //función para destruir la conexión.
    function destruir(){
            pg_close($this->url);
    }
}
?>