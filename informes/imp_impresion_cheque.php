<?php

require "../funciones/fpdf/fpdf.php";

require "../clases/conexion.php";
$id = $_GET["id"];
$idArr = explode('_', $id);
$entcod = $idArr[0];
$cuenta = $idArr[1];
$movnro = $idArr[2];

$con = new conexion();
$con->conectar();

$sql = ("SELECT *, fecha_pago::date as fecha_pag_f FROM v_pagos_cheques WHERE ent_cod = $entcod AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");
$result = pg_query($sql);
$imp = pg_fetch_array($result);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


$custom_width = 230; // Ancho en mm
$custom_height = 76; // Alto en mm

$con->destruir();
class PDF extends FPDF
{
    // Tabla simple
    function impresiones_cheques($imp)
    {
        // MONTO EN NUMERO 
        $this->SetFont('Times', 'B', 12); //TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/180, /*2*/ 3, /*3*/ '', /*4*/ '0', /*5*/ 0, /*6*/ 'C');
        $this->Cell(/*1*/50, /*2*/ 3, /*3*/ number_format($imp['monto_cheque'], 0, ',', '.'), /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->Ln(10);

        // FECHA 
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/15, /*2*/ 3, /*3*/ '', /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/215, /*2*/ 3, /*3*/ $imp['fecha_pag_f'], /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // DIA, MES Y ANIO EN DOS NUMERO 
        $this->SetFont('Times', 'B', 12); //TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/140, /*2*/ 3, /*3*/ '', /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 3, /*3*/ $imp['dia'] . '         ' . $imp['mes'] . '              ' . $imp['anio'], /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->Ln(10);

        // LIBRADOR
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/ 3, /*3*/ '', /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        if ($imp['librador'] !== null) {
            $this->Cell(/*1*/130, /*2*/ 3, /*3*/ utf8_decode($imp['librador']), /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        } else {
            $this->Cell(/*1*/130, /*2*/ 3, /*3*/ utf8_decode($imp['librador_por_defecto']), /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        }
        $this->Ln(7);

        // MONTO EN LETRAS
        $this->Cell(/*1*/55, /*2*/ 3, /*3*/ '', /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->MultiCell(/*1*/130, /*2*/ 7, /*3*/ utf8_decode('                                       ' . $imp['monto_txt']), /*4*/ '0', /*5*/ 'L');
        $this->Ln(5);

        // TOTAL 
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/15, /*2*/ 3, /*3*/ '', /*4*/ '0', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/215, /*2*/ 3, /*3*/ number_format($imp['monto_cheque'], 0, ',', '.'), /*4*/ '0', /*5*/ 0, /*6*/ 'L');
    }
}
$pdf = new PDF('L', 'mm', array($custom_width, $custom_height));
// Carga de datos
$pdf->SetFont('Arial', '', 10);
$pdf->AddPage();
$pdf->impresiones_cheques($imp);
$pdf->Output();
