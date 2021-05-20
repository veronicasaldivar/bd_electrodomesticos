<?php
require "../funciones/fpdf/fpdf.php";
//require "../clases/sesion.php";
require "../clases/conexion.php";
            //$codigo = $_GET["codigo"];
$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();

$sql1 = ("select * from v_promociones_cab where promo_cod = ".$id." order by 1");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);
$sql2 = ("select * from v_promociones_detalles where promo_cod = ".$id." ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}
$con->destruir();
class PDF extends FPDF
{
// Tabla simple
    function promo($cab,$det){
          $this->Image('../informes/img.png',60,11,80,25,'png');
        $this->Cell(165,30,"");
        $this->Ln();
        $this->Cell(165,10,"Promociones");
          $this->Ln();
        $this->Cell(20,6,utf8_decode('Codigo: '));
        $this->Cell(16,6,$cab["promo_cod"]);
          $this->Ln();
        $this->Cell(35,6,utf8_decode('Promo Fecha inicio: '));
        $this->Cell(25,6,$cab["promo_feinicio"]);
        $this->Ln();
        $this->Cell(35,6,utf8_decode('Promo Fecha Fin: '));
        $this->Cell(20,6,$cab["promo_fefin"]);
          $this->Ln();
        $this->Cell(18,6,utf8_decode('Estado: '));
        $this->Cell(20,6,$cab["promo_estado"]);
          $this->Ln();
          $this->Ln();
          $this->Ln();
        //Titulo
        $header = array('Código','Tipo de Servicio','Precio Anterior','Descuento','Tipo Descuento','Precio Promo');
        // Cabecera
        $this->Cell(14,7,utf8_decode($header[0]),1);
        $this->Cell(40,7,utf8_decode($header[1]),1);
        $this->Cell(25,7,utf8_decode($header[2]),1);
        $this->Cell(25,7,utf8_decode($header[3]),1);
        $this->Cell(25,7,utf8_decode($header[4]),1);
        $this->Cell(25,7,utf8_decode($header[5]),1);
       // $this->Cell(25,7,$header[4],1);
       // $this->Cell(25,7,$header[5],1);
       //$this->Cell(40,7,$header[6],1);//FALTA AUN POR TERMINARRRRRRRRRRRRRRRRR..... XP ASANCHEZ
          $this->Ln();
       
        foreach($det as $row)
        {
        $this->Cell(14,7,$row["promo_cod"],1);
        $this->Cell(40,7,Utf8_decode($row["item_desc"]),1);
        $this->Cell(25,7,$row["item_precio"],1);
       $this->Cell(25,7,$row["promo_desc"],1);
       $this->Cell(25,7,$row["tipo_desc"],1);
       $this->Cell(25,7,$row["promo_precio"],1);
        //$this->Cell(25,7,$row["agen_hhasta"],1);
       // $this->Cell(25,7,$row["dias_desc"],1);
       // $this->Cell(40,7,$row["agen_cupos"],1);
        $this->Ln();
    }
}
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->promo($cab,$det);
$pdf->Output();
?>