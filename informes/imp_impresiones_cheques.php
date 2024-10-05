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

$sql = ("SELECT * FROM v_pagos_cheques WHERE ent_cod = $entcod AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");
$result = pg_query($sql);
$imp = pg_fetch_array($result);

$sqlEntrega = ("SELECT * FROM v_entregas_cheques WHERE ent_cod = $entcod AND cuenta_corriente_cod = $cuenta AND movimiento_nro = $movnro");
$resultEntrega = pg_query($sqlEntrega);
$ent = pg_fetch_array($resultEntrega);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

$con->destruir();
class PDF extends FPDF
{
    // Tabla simple
    function impresiones_cheques($imp, $ent)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12); //TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/ 10, /*3*/ 'ELECTRODOMESTICOS S.A', /*4*/ 'LTR', /*5*/ 0, /*6*/ 'C');
        $this->Cell(/*1*/90, /*2*/ 10, /*3*/ 'PAGOS CHEQUES', /*4*/ 'LTR', /*5*/ 0, /*6*/ 'C');
        $this->Ln();

        // DIRECCION Y RUC DE LA EMPRESA
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Dirección: Asuncion esq. Haedor 687'), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 5, /*3*/ utf8_decode(' RUC: 800012358-5'), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // TELEFONO Y NUMERO DE ORDEN
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Teléfono: 0984-500-500'), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 5, /*3*/ utf8_decode(' Movimiento N.°: ' . $imp['movimiento_nro'] . ''), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Correo: electromesticos@gmail.com'), /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        if ($imp['orden_pago_cod'] !== null) {
            $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Orden Pago N.°: ' . $imp['orden_pago_cod']), /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        } else {
            $this->Cell(/*1*/90, /*2*/ 8, /*3*/ '', /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        }
        $this->Ln();

        //FECHA DE EMISION Y ESTADO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Fecha pago:  ' . $imp['fecha_pago'] . ''), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Estado:  ' . $imp['estado'] . ''), /*4*/ 'BR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // NOMBRE Y APELLIDO LIBRADOR
        if ($imp['librador'] !== null) {
            $this->Cell(/*1*/180, /*2*/ 8, /*3*/ utf8_decode(' Librador:  ' . $imp['librador']), /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        } else {
            $this->Cell(/*1*/180, /*2*/ 8, /*3*/ utf8_decode(' Librador:  ' . $imp['librador_por_defecto']), /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        }
        $this->Ln();

        // TIPO RECLAMO / SUGERENCIAS y ESTADO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Cheque numero: ' . $imp['chque_num'] . ''), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ utf8_decode(' Monto Cheque: ' . $imp['monto_cheque'] . ''), /*4*/ 'BR', /*5*/ 0, /*6*/ 'L');
        $this->Ln(20);

        //ENTREGAS
        if (!empty($ent)) {
            $this->Cell(/*1*/180, /*2*/ 8, /*3*/ utf8_decode(' Firma: ___________________________' . ' Fecha entrega: ' . $ent['entrega_fecha']), /*4*/ 0, /*5*/ 0, /*6*/ 'L');
            $this->Ln();
            $this->Cell(/*1*/180, /*2*/ 8, /*3*/ utf8_decode(' Entregado a: ' . $ent['ent_cheq_nom'] . '        C.I N.°: ' . $ent['ent_cheq_ced']), /*4*/ 0, /*5*/ 0, /*6*/ 'L');
            $this->Ln();
            $this->Cell(/*1*/180, /*2*/ 8, /*3*/ utf8_decode(' Observacion: ' . $ent['ent_cheq_obs']), /*4*/ 0, /*5*/ 0, /*6*/ 'L');
        }
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial', '', 10);
$pdf->AddPage();
$pdf->impresiones_cheques($imp, $ent);
$pdf->Output();
