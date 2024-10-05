<?php
require "../funciones/fpdf/fpdf.php";
require "../clases/conexion.php";

$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();
$sql1 = ("SELECT * FROM v_notas_remisiones_cab WHERE nota_rem_cod = " . $id . " ");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);
$sql2 = ("SELECT * FROM v_notas_remisiones_detalles WHERE nota_rem_cod = " . $id . " ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

$con->destruir();
class PDF extends FPDF
{
    function ordencompras($cab, $det)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12); //TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/ 10, /*3*/ $cab['emp_nom'], /*4*/ 'LTR', /*5*/ 0, /*6*/ 'C');
        $this->Cell(/*1*/90, /*2*/ 10, /*3*/ utf8_decode('NOTA DE REMISIÓN'), /*4*/ 'LTR', /*5*/ 0, /*6*/ 'C');
        $this->Ln();

        // DIRECCION Y RUC DE LA EMPRESA
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Dirección:  ' . $cab['emp_dir'] . ''), /*4*/ 'L', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 5, /*3*/ utf8_decode(' RUC:  ' . $cab['emp_ruc'] . ''), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // TELEFONO Y NUMERO DE ORDEN
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Teléfono: ' . $cab['emp_tel'] . ''), /*4*/ 'L', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 5, /*3*/ utf8_decode(' Remisión N.°: ' . $cab['nota_rem_cod'] . ''), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Correo:  ' . $cab['emp_email'] . ''), /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ '', /*4*/ 'RB', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        //FECHA DE EMISION Y ESTADO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Fecha de Emisión:  ' . $cab['nota_rem_fecha'] . ''), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Estado:  ' . $cab['nota_rem_estado'] . ''), /*4*/ 'BR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // DATOS DEL CHOFER
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Chofer:  ' . $cab['chofer_nom'] . ''), /*4*/ 'L', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Chofer Ruc:  ' . $cab['chofer_ruc'] . ''), /*4*/ 'R', /*5*/ 0, /*6*/ 'L');
        $this->Ln();
        $this->Cell(/*1*/60, /*2*/ 8, /*3*/ utf8_decode(' Chofer N.° Fact:  ' . $cab['chofer_factura'] . ''), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/60, /*2*/ 8, /*3*/ utf8_decode(' Timbrado:  ' . $cab['chofer_timb'] . ''), /*4*/ 'B', /*5*/ 0, /*6*/ 'L');
        if ($cab['chofer_timb_vighasta'] === '1111-01-01') {
            $this->Cell(/*1*/60, /*2*/ 8, /*3*/ utf8_decode(' Fecha Fact: - ' ), /*4*/ 'BR', /*5*/ 0, /*6*/ 'L');
        } else {
            $this->Cell(/*1*/60, /*2*/ 8, /*3*/ utf8_decode(' Fecha Fact:  ' . $cab['chofer_timb_vighasta']), /*4*/ 'BR', /*5*/ 0, /*6*/ 'L');
        }
        $this->Ln();

        // NOMBRE O RAZON SOCIAL Y RUC DEL CLIENTE
        $this->Cell(/*1*/40, /*2*/ 8, /*3*/ utf8_decode('Nombre o Razón Social:'), /*4*/ 'L', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/140, /*2*/ 8, /*3*/ utf8_decode($cab['cli_nom']), /*4*/ 'R', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        $this->Cell(/*1*/20, /*2*/ 8, /*3*/ utf8_decode('RUC:'), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/70, /*2*/ 8, /*3*/ utf8_decode($cab['cli_ruc']), /*4*/ 'B', /*5*/ 0, /*6*/ 'L');

        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ '', /*4*/ 'RB', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // DETALLES DE LA ORDEN DE COMPRA - Titulo
        $header = array('Codigo', 'Cantidad', 'Descripción', 'Precio');
        // Cabecera
        $this->Cell(15, 10, $header[0], 1);
        $this->Cell(15, 10, $header[1], 1);
        $this->Cell(125, 10, utf8_decode($header[2]), 1);
        $this->Cell(25, 10, $header[3], 1);
        // $this->Cell(25,15,$header[4],1);
        $this->Ln();
        // Datos
        $total = 0;
        foreach ($det as $row) {
            $subtotal = $row["nota_rem_cant"] * $row["nota_rem_precio"];
            // $total = $total + $subtotal;
            $this->Cell(/*1*/15, /*2*/ 6, /*3*/ utf8_decode($row['item_cod']), /*4*/ 1, /*5*/ 0, /*6*/ 'C');
            $this->Cell(/*1*/15, /*2*/ 6, /*3*/ utf8_decode($row['nota_rem_cant']), /*4*/ 1, /*5*/ 0, /*6*/ 'R');
            $this->SetFont('Times', '', 8); //TIPO DE LETRA PARA SUBTITULO
            $this->Cell(/*1*/125, /*2*/ 6, /*3*/ utf8_decode($row['item_desc']), /*4*/ 1, /*5*/ 0, /*6*/ 'LR');
            $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
            $this->Cell(/*1*/25, /*2*/ 6, /*3*/ number_format($row["nota_rem_precio"], 0, ',', '.'), /*4*/ 1, /*5*/ 0, /*6*/ 'R');

            // $this->Cell(/*1*/25, /*2*/6, /*3*/number_format($subtotal,0,',','.'), /*4*/1, /*5*/1, /*6*/'R');

        }
        // $this->Cell(155,6,'Totales',1);
        // $this->Cell(/*1*/25, /*2*/6, /*3*/number_format($total,0,',','.'), /*4*/1, /*5*/1, /*6*/'R');
        // $this->Ln();
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial', '', 10);
$pdf->AddPage();
$pdf->ordencompras($cab, $det);
$pdf->Output();
