<?php
require "../funciones/fpdf/fpdf.php";
require "../clases/conexion.php";

$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();
$sql1 = ("SELECT * FROM v_reposiciones_fondos_fijos_cab WHERE rep_fon_fij_cod = $id ");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);


$sql2 = ("SELECT * FROM v_reposiciones_fondos_fijos_det WHERE rep_fon_fij_cod = $id ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}

$con->destruir();
class PDF extends FPDF
{
// Tabla simple
    function facturas_varias($cab, $det)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12);
        $this->Cell(/*1*/90, /*2*/10, /*3*/$cab['emp_nom'], /*4*/'LTR', /*5*/0, /*6*/'C');

        $this->SetFont('Times', '', 10);
        $this->Cell(/*1*/90, /*2*/10, /*3*/utf8_decode('') , /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Ln();
        
        // DIRECCION Y FECHA INICION DE VIGENCIA
        $this->Cell(/*1*/90, /*2*/6, /*3*/utf8_decode(" Dirección:  ".$cab['emp_dir']." "), /*4*/'L', /*5*/0, /*6*/'L');  
        $this->SetFont('Times', 'B', 12);
        $this->Cell(/*1*/90, /*2*/4, /*3*/utf8_decode(' REPOSICION FONDO FIJO ') , /*4*/'LR', /*5*/0, /*6*/'C');
        $this->SetFont('Times', '', 10);
        $this->Ln();

        // TELEFONO Y FECHA FIN DE VIGENCIA
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode(" Teléfono:  ".$cab['emp_tel']." "), /*4*/'L', /*5*/0, /*6*/'L'); 
        $this->Cell(/*1*/90, /*2*/6, /*3*/utf8_decode('Reposcion N°: ' .$cab['rep_fon_fij_cod']) , /*4*/'LR', /*5*/0, /*6*/'C');
        $this->Ln();

        //CORREO Y RUC 
        $this->Cell(/*1*/90, /*2*/6, /*3*/utf8_decode(" Correo:  ".$cab['emp_email']." "), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/6, /*3*/" "  , /*4*/'R', /*5*/0, /*6*/'C');
        $this->Ln();
        
        // NOTA DE REMISION
        $this->Cell(/*1*/90, /*2*/6, /*3*/' RUC:  '.$cab['emp_ruc'].'', /*4*/'LR', /*5*/0, /*6*/'L');
        $this->SetFont('Times', 'B', 12);//TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/6, /*3*/utf8_decode(''), /*4*/'LR', /*5*/0, /*6*/'C');
        $this->SetFont('Times', '', 10);//TIPO DE LETRA PARA SUBTITULO
        $this->Ln();

        // NUMERO DE REMISION
        $this->Cell(/*1*/90, /*2*/6, /*3*/'', /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/6, /*3*/'', /*4*/'LBR', /*5*/0, /*6*/'C');
        $this->Ln();

        //FECHA DE EMISION Y CONDICION DE VENTA
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode("  Fecha reposicion: ".$cab['reposicion_fecha']), /*4*/'LB', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode("  Estado: ".$cab['rep_estado']), /*4*/'BR', /*5*/0, /*6*/'L');
        $this->Ln();

        $header = array('Cód.','Descripción','Importe', 'Exenta', '5%', '10%');
        // Cabecera
        $this->Cell(10,10,utf8_decode($header[0]),1);
        $this->Cell(95,10,utf8_decode($header[1]),1);
        $this->Cell(15,10,utf8_decode($header[2]),1);
        $this->Cell(20,10,$header[3],1);
        $this->Cell(20,10,$header[4],1);
        $this->Cell(20,10,$header[5],1);
        $this->Ln();
        // Datos
        $totalGeneral = 0;

        $exenta = 0;
        $iva5 = 0;
        $iva10 = 0 ;
        
        foreach($det as $row)
        {
            $subtotal = $row['rendi_monto_fact'];
            $totalGeneral = $totalGeneral + $subtotal;
            $this->Cell(/*1*/10, /*2*/6, /*3*/utf8_decode($row['rubro_cod']), /*4*/1, /*5*/0, /*6*/'C');
                $this->SetFont('Times', '', 8);
            $this->Cell(/*1*/95, /*2*/6, /*3*/utf8_decode($row['rubro_desc']), /*4*/1, /*5*/0, /*6*/'L');
              //  $this->SetFont('Times', '', 10);
            $this->Cell(/*1*/15, /*2*/6, /*3*/number_format($row["rendi_monto_fact"],0,',','.'), /*4*/1, /*5*/0, /*6*/'R');
            
            if($row['tipo_imp_cod'] == '1'){
                $iva10 = $iva10 + $subtotal;
                $this->Cell(/*1*/20, /*2*/6, /*3*/utf8_decode(" "), /*4*/'LBR', /*5*/0, /*6*/'R'); 
                $this->Cell(/*1*/20, /*2*/6, /*3*/utf8_decode("  "), /*4*/'LBR', /*5*/0, /*6*/'R'); 
                $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($subtotal,0,',','.'), /*4*/'LBR', /*5*/1, /*6*/'R'); 
            }elseif ($row['tipo_imp_cod'] == '2') {
                $iva5 = $iva5 + $subtotal;
                $this->Cell(/*1*/20, /*2*/6, /*3*/utf8_decode(" "), /*4*/'LBR', /*5*/0, /*6*/'R'); 
                $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($subtotal,0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
                $this->Cell(/*1*/20, /*2*/6, /*3*/utf8_decode("  "), /*4*/'LBR', /*5*/1, /*6*/'R'); 
            }elseif ($row['tipo_imp_cod'] == '3') {
                $exenta = $exenta + $subtotal;
                $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($subtotal,0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
                $this->Cell(/*1*/20, /*2*/6, /*3*/utf8_decode("  "), /*4*/'LBR', /*5*/0, /*6*/'R'); 
                $this->Cell(/*1*/20, /*2*/6, /*3*/utf8_decode("  "), /*4*/'LBR', /*5*/1, /*6*/'R'); 
            }
        }

        //SUBTOTALES
        $this->SetFont('Times', '', 10);
        $this->Cell(/*1*/120, /*2*/6, /*3*/utf8_decode("  Subtotales:"), /*4*/'LBR', /*5*/0, /*6*/'L'); 
        $this->SetFont('Times', '', 8);
        $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($exenta,0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($iva5,0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($iva10,0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
        $this->Ln();

        //LIQUIDACION DE IVA
        $this->SetFont('Times', '', 10);
        $this->Cell(/*1*/120, /*2*/6, /*3*/utf8_decode("  Liquidación de IVA:"), /*4*/'LBR', /*5*/0, /*6*/'L'); 
        $this->SetFont('Times', '', 8);

        $this->Cell(/*1*/20, /*2*/6, /*3*/number_format($exenta,0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/20, /*2*/6, /*3*/number_format(($iva5/21),0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/20, /*2*/6, /*3*/number_format(($iva10/11),0,',','.'), /*4*/'LBR', /*5*/0, /*6*/'R'); 
        $this->Ln();

        //TOTAL DE IVA
        $totalIVA = ($iva10/11) + ($iva5/21);
        $this->SetFont('Times', '', 10);
        $this->Cell(/*1*/135, /*2*/6, /*3*/utf8_decode("  Total de IVA:"), /*4*/'LB', /*5*/0, /*6*/'L'); 
        $this->Cell(/*1*/15, /*2*/6, /*3*/utf8_decode("  "), /*4*/'B', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/15, /*2*/6, /*3*/utf8_decode("  "), /*4*/'B', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/15, /*2*/6, /*3*/number_format($totalIVA,0,',','.'), /*4*/'BR', /*5*/0, /*6*/'R'); 
        $this->Ln();

        //TOTAL GENERAL
        $this->SetFont('Times', '', 10);
        $this->Cell(/*1*/135, /*2*/6, /*3*/utf8_decode("  Total Reposicion:"), /*4*/'LB', /*5*/0, /*6*/'L'); 
        $this->Cell(/*1*/15, /*2*/6, /*3*/utf8_decode("  "), /*4*/'B', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/15, /*2*/6, /*3*/utf8_decode("  "), /*4*/'B', /*5*/0, /*6*/'R'); 
        $this->Cell(/*1*/15, /*2*/6, /*3*/number_format($totalGeneral,0,',','.'), /*4*/'BR', /*5*/0, /*6*/'R'); 
        $this->Ln();

    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->facturas_varias($cab, $det);
$pdf->Output();
?>