﻿<?php

require "../funciones/fpdf/fpdf.php";

require "../clases/conexion.php";
$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$sql = ("SELECT * FROM v_asignacion_fondo_fijos  WHERE asignacion_responsable_cod = ".$id."");
$result = pg_query($sql);
$cab = pg_fetch_array($result);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

$con->destruir();
class PDF extends FPDF
{
// Tabla simple
    function presupuestos_cliente($cab)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12);//TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/10, /*3*/$cab['emp_nom'], /*4*/'LTR', /*5*/0, /*6*/'C');
        $this->Cell(/*1*/90, /*2*/10, /*3*/'ASIGNACION FONDO FIJOS', /*4*/'LTR', /*5*/0, /*6*/'C');
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
            
        $this->Cell(/*1*/90, /*2*/5, /*3*/utf8_decode('Asignacion Fondo N.°: '.$cab['asignacion_responsable_cod'].''), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/35, /*2*/8, /*3*/utf8_decode('Correo: '), /*4*/'LB', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/55, /*2*/8, /*3*/utf8_decode($cab['emp_email']), /*4*/'RB', /*5*/0, /*6*/'L');

        $this->Cell(/*1*/90, /*2*/8, /*3*/'', /*4*/'RB', /*5*/0, /*6*/'L');
        $this->Ln();
        
        //FECHA DE EMISION Y ESTADO
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('Fecha de Operacion:  '.$cab['fecha_asignacion'].''), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('Fondo Asignacion:  '.$cab['tipo_asignacion'].''), /*4*/'BR', /*5*/0, /*6*/'L');
        $this->Ln();
        
        // NOMBRE Y APELLIDO
        $this->Cell(/*1*/180, /*2*/8, /*3*/utf8_decode('Nombre y Apellido del Responsable:  '.$cab['fun_res_nom'].''), /*4*/'LR', /*5*/0, /*6*/'L');
        $this->Ln();

        // NUMERO DE CI DEL FUNCIONARIO
        $this->Cell(/*1*/180, /*2*/8, /*3*/utf8_decode(' C.I:  '.$cab['fun_res_ci'].''), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Ln();

        // TIPO RECLAMO / SUGERENCIAS y ESTADO
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('  Monto Asignado: '.$cab['monto_asignado'].''), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Cell(/*1*/90, /*2*/8, /*3*/utf8_decode('  Estado: '.$cab['asignacion_estado'].''), /*4*/'BR', /*5*/0, /*6*/'L');
        $this->Ln();

        // OBSERVACION
        $this->Cell(/*1*/180, /*2*/8, /*3*/utf8_decode(' Observacion: '.$cab['observacion'].''), /*4*/'LBR', /*5*/0, /*6*/'L');
        $this->Ln();
     }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->presupuestos_cliente($cab);
$pdf->Output();
?>

