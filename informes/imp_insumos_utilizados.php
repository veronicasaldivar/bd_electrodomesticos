<?php
require "../funciones/fpdf/fpdf.php";
require "../clases/conexion.php";

$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();

$sql1 = ("select * from v_insumos_utilizados_cab where ins_uti_cod = ".$id." ");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);
$sql2 = ("select * from v_insumos_utilizados_det where ins_uti_cod = ".$id." ");
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
        $this->Cell(/*1*/90, /*2*/10, /*3*/'PEDIDOS DE COMPRAS', /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Ln();
        
        // DIRECCION Y RUC DE LA EMPRESA
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('Dirección:  '.$cab['emp_dir'].''), /*4*/'L', /*5*/0, /*6*/'L');            
        $this->Cell(/*1*/90, /*2*/5, /*3*/utf8_decode('RUC:  '.$cab['emp_ruc'].''), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        // TELEFONO Y NUMERO DE ORDEN
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('Teléfono:  '.$cab['emp_tel'].''), /*4*/'L', /*5*/0, /*6*/'L');
            
        $this->Cell(/*1*/90, /*2*/5, /*3*/utf8_decode('INSUMOS_UTILIZADOS N.°:  '.$cab['ins_uti_cod'].''), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('Correo:  '.$cab['emp_email'].''), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/'', /*4*/'RB', /*5*/0, /*6*/'L');
        $this->Ln();

        // FECHA Y ESTADO DEL PEDIDO
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode(' Fecha: '.$cab['ins_uti_fecha'].''), /*4*/'L', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode(' Estado: '.$cab['ins_uti_estado'].''), /*4*/'R', /*5*/0, /*6*/'L');
        $this->Ln();

        //Titulo
        $header = array('Código','Descripción','Cantidad Usado','Fecha Uso');
        // Cabecera
        $this->Cell(25,7,utf8_decode($header[0]),1);
        $this->Cell(90,7,utf8_decode($header[1]),1);
        $this->Cell(25,7,utf8_decode($header[2]),1);
        $this->Cell(40,7,utf8_decode($header[3]),1);
        $this->Ln();
        // Datos
        $total = 0;
        foreach($det as $row)
        {
            $this->Cell(25,6,$row["item_cod"],'LRB');
            $this->Cell(90,6,utf8_decode($row["item_desc"].' '.$row["mar_desc"]),'RB');
            $this->Cell(25,6,$row["cant_usado"],'RB');
            $this->Cell(40,6,$row["fecha_uso"],'RB');
            $this->setFont('Times', '', 10);
         
            $this->Ln();
        }
        // $this->Cell(160,6,'Totales',1);
        // $this->Cell(20,6,number_format($total,0,',','.'),1);
        // $this->Ln();
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->pedidocompras($cab,$det);
$pdf->Output();
?>
