<?php
require "../funciones/fpdf/fpdf.php";
require "../clases/conexion.php";

$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();
$sql1 = ("select * from v_ajustes_cab where ajus_cod = ".$id." order by 1");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);

$sql2 = ("select * from v_ajustes_det where ajus_cod = ".$id." ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}

$sqlDep = ("SELECT * FROM v_ajustes_det WHERE ajus_cod = $id LIMIT 1");
$resDep = pg_query($sqlDep);
$dep = pg_fetch_assoc($resDep);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

$con->destruir();
class PDF extends FPDF
{
// Tabla simple
    function ajustes($cab, $det, $dep)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12);//TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/10, /*3*/mb_convert_encoding($cab['emp_nom'], 'ISO-8859-1', 'UTF-8'), /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Cell(/*1*/90, /*2*/10, /*3*/'AJUSTES DE STOCK', /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Ln();
        
        // DIRECCION Y RUC DE LA EMPRESA
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/8, /*3*/mb_convert_encoding('Dirección: '.$cab['emp_dir'], 'ISO-8859-1', 'UTF-8'), /*4*/'LR', /*5*/0, /*6*/'L');   
        $this->Cell(/*1*/90, /*2*/5, /*3*/mb_convert_encoding('RUC: '.$cab['emp_ruc'],'ISO-8859-1', 'UTF-8'), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        // TELEFONO Y NUMERO DE ORDEN
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/8, /*3*/mb_convert_encoding('Teléfono: '.$cab['emp_tel'], 'ISO-8859-1', 'UTF-8'), /*4*/'LR', /*5*/0, /*6*/'L');            
        $this->Cell(/*1*/90, /*2*/5, /*3*/mb_convert_encoding('Ajuste N.°: '.$cab['ajus_nro'], 'ISO-8859-1', 'UTF-8'), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/90, /*2*/8, /*3*/mb_convert_encoding('Correo: '.$cab['emp_email'], 'ISO-8859-1', 'UTF-8'), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/'', /*4*/'RB', /*5*/0, /*6*/'L');
        $this->Ln();
        
        //FECHA DE EMISION Y ESTADO
        $this->Cell(/*1*/180, /*2*/8, /*3*/mb_convert_encoding('Fecha de Ajuste: '.$cab['fecha_ajuste'], 'ISO-8859-1', 'UTF-8'), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Ln();

        //DEPOSITO AJUSTADO
        $this->Cell(/*1*/180, /*2*/8, /*3*/mb_convert_encoding('Depósito ajustado: '.$dep['dep_desc'], 'ISO-8859-1', 'UTF-8'), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Ln();

        //OBSERVACIONES
        $this->Cell(/*1*/180, /*2*/8, /*3*/mb_convert_encoding('Observación: ', 'ISO-8859-1', 'UTF-8'), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Ln();

        // DETALLES DE LA ORDEN DE COMPRA 
        $header = array('Descripción', 'Cant. Ant.', 'Cant. Act.', 'Ajuste');
        // Cabecera
        $this->Cell(120, 10, mb_convert_encoding($header[0], 'ISO-8859-1', 'UTF-8'), 1);
        $this->Cell(20, 10, $header[1], 1);
        $this->Cell(20, 10, $header[2], 1);
        $this->Cell(20, 10, $header[3], 1);
        $this->Ln();
        // Datos
        $total = 0;
        foreach($det as $row)
        {
            // $this->Cell(/*1*/20, /*2*/6, /*3*/$row['item_cod'], /*4*/1, /*5*/0, /*6*/'C');
                $this->SetFont('Times', '', 8);//TIPO DE LETRA PARA SUBTITULO
            $this->Cell(/*1*/120, /*2*/6, /*3*/mb_convert_encoding($row['item_desc'].' '.$row['mar_desc'], 'ISO-8859-1', 'UTF-8'), /*4*/1, /*5*/0, /*6*/'L');
                $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
            $this->Cell(/*1*/20, /*2*/6, /*3*/$row['stk_cant_ant'], /*4*/1, /*5*/0, /*6*/'R');
            $this->Cell(/*1*/20, /*2*/6, /*3*/$row['stk_cant_encontrada'], /*4*/1, /*5*/0, /*6*/'R');
            $this->Cell(/*1*/20, /*2*/6, /*3*/$row['ajus_cantidad'], /*4*/1, /*5*/0, /*6*/'R');
            $this->Ln();
        }
        
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->ajustes($cab, $det, $dep);
$pdf->Output();
?>
