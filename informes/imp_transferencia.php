<?php

require "../funciones/fpdf/fpdf.php";
require "../clases/conexion.php";
$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$sql1 = ("select * from v_transferencias_cab where trans_cod = " . $id . "");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);

$det = array();
$sql2 = ("select * from v_transferencias_det where trans_cod = " . $id . " ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}

$con->destruir();
class PDF extends FPDF
{
    function transferencias($cab, $det)
    {
        // Nombre de la empresa y movimiento al que corresponde
        $this->SetFont('Times', 'B', 12); //TIPO DE LETRA PARA TITULO
        $this->Cell(/*1*/90, /*2*/ 10, /*3*/ $cab['emp_nom'], /*4*/ 'LTR', /*5*/ 0, /*6*/ 'C');
        $this->Cell(/*1*/90, /*2*/ 10, /*3*/ 'TRANSFERENCIAS', /*4*/ 'LTR', /*5*/ 0, /*6*/ 'C');
        $this->Ln();

        // DIRECCION Y RUC DE LA EMPRESA
        $this->SetFont('Times', '', 10); //TIPO DE LETRA PARA SUBTITULO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ mb_convert_encoding('Dirección: ' . $cab['emp_dir'], 'ISO-8859-1', 'UTF-8'), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');

        $this->Cell(/*1*/35, /*2*/ 5, /*3*/ '', /*4*/ 'L', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/55, /*2*/ 5, /*3*/ '', /*4*/ 'R', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // TELEFONO Y NUMERO DE ORDEN
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ mb_convert_encoding('Teléfono: ' . $cab['emp_tel'], 'ISO-8859-1', 'UTF-8'), /*4*/ 'L', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 5, /*3*/ mb_convert_encoding('Transferencia N.°: ' . $cab['trans_cod'], 'ISO-8859-1', 'UTF-8'), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        //CUARTA LINEA
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ mb_convert_encoding('Correo: ' . $cab['emp_email'], 'ISO-8859-1', 'UTF-8'), /*4*/ 'LBR', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ '', /*4*/ 'RB', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        //FECHA DE EMISION Y ESTADO
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ mb_convert_encoding('Fecha de salida: ' . $cab['fecha_envio'], 'ISO-8859-1', 'UTF-8'), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ mb_convert_encoding('Fecha de llegada estimada: ' . $cab['fecha_entrega'], 'ISO-8859-1', 'UTF-8'), /*4*/ 'BR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        // NOMBRE O RAZON SOCIAL Y RUC DEL PROVEEDOR
        $this->Cell(/*1*/180, /*2*/ 8, /*3*/ mb_convert_encoding('Nombre o Razón Social: ', 'ISO-8859-1', 'UTF-8'), /*4*/ 'LR', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ mb_convert_encoding('', 'ISO-8859-1', 'UTF-8'), /*4*/ 'LB', /*5*/ 0, /*6*/ 'L');
        $this->Cell(/*1*/90, /*2*/ 8, /*3*/ '', /*4*/ 'RB', /*5*/ 0, /*6*/ 'L');
        $this->Ln();

        //Titulo
        $header = array('Código', 'Cantidad Env.', 'Descripción', 'Cant. Rec', 'Diferencia');
        // Cabecera
        $this->Cell(15, 7, mb_convert_encoding($header[0], 'ISO-8859-1', 'UTF-8'), 1);
        $this->Cell(20, 7, mb_convert_encoding($header[1], 'ISO-8859-1', 'UTF-8'), 1);
        $this->Cell(100, 7, mb_convert_encoding($header[2], 'ISO-8859-1', 'UTF-8'), 1);
        $this->Cell(25, 7, mb_convert_encoding($header[3], 'ISO-8859-1', 'UTF-8'), 1);
        $this->Cell(20, 7, mb_convert_encoding($header[4], 'ISO-8859-1', 'UTF-8'), 1);
        $this->Ln();

        $totalDiferencias = 0;
        foreach ($det as $row) {
            $diferencia = $row["trans_cant_recibida"] - $row["trans_cantidad"];
            $totalDiferencias  += $diferencia;
            $this->Cell(15, 6, $row["item_cod"], 'LR');
            $this->Cell(20, 6, $row["trans_cantidad"], 'R');
            $this->setFont('Times', '', 8);
            $this->Cell(100, 6, mb_convert_encoding($row["item_desc"], 'ISO-8859-1', 'UTF-8'), 'R');
            $this->setFont('Times', '', 10);
            $this->Cell(25, 6, number_format($row["trans_cant_recibida"], 0, ',', '.'), 'R');
            $this->Cell(20, 6, number_format($diferencia, 0, ',', '.'), 'R');
            $this->Ln();
        }
        $this->Cell(180, 6, '', 1);
        // $this->Cell(160,6,'Totales',1);
        // $this->Cell(20,6,number_format($totalDiferencias,0,',','.'),1);
        $this->Ln();
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial', '', 10);
$pdf->AddPage();
$pdf->transferencias($cab, $det);
$pdf->Output();
