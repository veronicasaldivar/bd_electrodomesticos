<?php
require "../funciones/fpdf/fpdf.php";
require "../clases/conexion.php";

$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();

$sql1 = ("select * from v_otros_cred_deb_bancarios_cab where otro_deb_cred_ban_cod = ".$id." order by 1");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);
$sql2 = ("select * from v_otros_cred_deb_bancarios_det where otro_deb_cred_ban_cod = ".$id." ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}
$con->destruir();
class PDF extends FPDF
{
// Tabla simple
    function pedidocompras($cab,$det)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12);//TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/10, /*3*/$cab['emp_nom'], /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Cell(/*1*/90, /*2*/10, /*3*/'OTROS CREDITOS DEBITOS BANCARIOS', /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Ln();
        
        // DIRECCION Y RUC DE LA EMPRESA
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/35, /*2*/8, /*3*/utf8_decode('Dirección:'), /*4*/'L', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/55, /*2*/8, /*3*/utf8_decode($cab['emp_dir']), /*4*/'R', /*5*/0, /*6*/'L');
            
        $this->Cell(/*1*/35, /*2*/5, /*3*/utf8_decode('RUC: '), /*4*/'L', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/55, /*2*/5, /*3*/utf8_decode($cab['emp_ruc']), /*4*/'R', /*5*/0, /*6*/'L');
        $this->Ln();

        // TELEFONO Y NUMERO DE ORDEN
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/35, /*2*/8, /*3*/utf8_decode('Teléfono:'), /*4*/'L', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/55, /*2*/8, /*3*/utf8_decode($cab['emp_tel']), /*4*/'R', /*5*/0, /*6*/'L');
            
        $this->Cell(/*1*/35, /*2*/5, /*3*/utf8_decode('Movimiento N.°: '), /*4*/'L', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/55, /*2*/5, /*3*/utf8_decode($cab['otro_deb_cred_ban_cod']), /*4*/'R', /*5*/0, /*6*/'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/35, /*2*/8, /*3*/utf8_decode('Correo: '), /*4*/'LB', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/55, /*2*/8, /*3*/utf8_decode($cab['emp_email']), /*4*/'RB', /*5*/0, /*6*/'L');

        $this->Cell(/*1*/90, /*2*/8, /*3*/'', /*4*/'RB', /*5*/0, /*6*/'L');
        $this->Ln();

        // FECHA Y ESTADO DEL PEDIDO
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode(' Fecha: '.$cab['otro_debito_credito_fecha'].''), /*4*/'L', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode(' Estado: '.$cab['otro_deb_cred_estado'].''), /*4*/'R', /*5*/0, /*6*/'L');
        $this->Ln();

        $this->Cell(/*1*/180, /*2*/8, /*3*/utf8_decode(' Observacion: '.$cab['descripcion'].''), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        //Titulo
        $header = array('Código','Descripción','Monto','Subtotal');
        // Cabecera
        $this->Cell(15,7,utf8_decode($header[0]),1);
        $this->Cell(120,7,utf8_decode($header[1]),1);
        $this->Cell(25,7,utf8_decode($header[2]),1);
        $this->Cell(20,7,utf8_decode($header[3]),1);
        $this->Ln();
        // Datos
        $total = 0;
        foreach($det as $row)
        {
            $subtotal = $row["otro_deb_cred_monto"];
            $total = $total + $subtotal;
            $this->Cell(15,6,$row["con_deb_cred_ban_cod"],'LR');
            $this->setFont('Times', '', 8);
            $this->Cell(120,6,utf8_decode($row["con_deb_cred_ban_desc"]),'R');
            $this->setFont('Times', '', 10);
            $this->Cell(25,6,number_format($row["otro_deb_cred_monto"],0,',','.'),'R');
            $this->Cell(20,6,number_format($subtotal,0,',','.'),'R');
            $this->Ln();
        }
        $this->Cell(160,6,'Totales',1);
        $this->Cell(20,6,number_format($total,0,',','.'),1);
        $this->Ln();
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->pedidocompras($cab,$det);
$pdf->Output();
?>
