<?php

require "../funciones/fpdf/fpdf.php";

require "../clases/conexion.php";
$id = $_GET["id"];
$con = new conexion();
$con->conectar();

$det = array();
$sql1 = ("select * from v_ordenes_compras_cab where orden_nro = ".$id." order by 1");
$result1 = pg_query($sql1);
$cab = pg_fetch_array($result1);
$sql2 = ("select * from v_ordenes_compras_det where orden_nro = ".$id." ");
$result2 = pg_query($sql2);
while ($row = pg_fetch_array($result2)) {
    $det[] = $row;
}





$con->destruir();
class PDF extends FPDF
{
// Tabla simple
    function ordencompras($cab,$det)
    {
      //  $this->Image('../img/INFORMES.JPG',60,11,80,25,'JPG');
        $this->Cell(165,30,"");
        $this->Ln();
        $this->Cell(165,10,"Ordenes de Compras");
        $this->Ln();

       $this->Cell(20,6,utf8_decode('Pedido N°: '));
        $this->Cell(33,6,$cab["orden_nro"]);
          $this->Cell(15,6,utf8_decode('Fecha: '));
        $this->Cell(30,6,$cab["orden_fecha"]);
        $this->Ln();
        $this->Cell(15,6,utf8_decode('Estado: '));
        $this->Cell(30,6,$cab["orden_estado"]);
        $this->Ln();
        $this->Cell(20,6,utf8_decode('Cuotas: '));
        $this->Cell(9,6,$cab["orden_cuotas"]);
        $this->Cell(18,6,utf8_decode('Plazo: '));
        $this->Cell(70,6,$cab["orden_plazo"]);
        $this->Ln();
        $this->Cell(18,6,utf8_decode('Empresa: '));
        $this->Cell(15,6,$cab["emp_nom"]);
        $this->Ln();
        // $this->Cell(9,6,utf8_decode('RUC: '));
        // $this->Cell(18,6,$cab["emp_ruc"]);
        // $this->Ln();
        $this->Cell(18,6,utf8_decode('Direccion: '));
        $this->Cell(15,6,utf8_decode($cab["emp_dir"]));
        $this->Ln();
        $this->Cell(15,6,utf8_decode('Email: '));
        $this->Cell(50,6,utf8_decode($cab["emp_email"]));
        $this->Ln();
        $this->Cell(18,6,utf8_decode('Proveedor: '));
        $this->Cell(40,6,$cab["prov_nombre"]);
        $this->Cell(15,6,utf8_decode('RUC: '));
        $this->Cell(25,6,$cab["prov_ruc"]);
        $this->Cell(18,6,utf8_decode('Direccion: '));
        $this->Cell(50,6,$cab["prov_dir"]);
            $this->Ln();
        $this->Cell(18,6,utf8_decode('Email: '));
        $this->Cell(15,6,$cab["per_email"]);
         $this->Ln();
        $this->Cell(25,6,utf8_decode('Tipo Factura: '));
        $this->Cell(100,6,$cab["tipo_fact_desc"]);
        
        $this->Ln();
        $this->Ln();
        $this->Ln();
        //Titulo
        $header = array('Codigo','Cantidad','Descripcion','Precio','Subtotal');
        // Cabecera
        $this->Cell(14,7,$header[0],1);
        $this->Cell(20,7,$header[1],1);
        $this->Cell(100,7,$header[2],1);
        $this->Cell(25,7,$header[3],1);
        $this->Cell(25,7,$header[4],1);
        $this->Ln();
        // Datos
        $total = 0;
        foreach($det as $row)
        {
            $subtotal = $row["orden_cantidad"] * $row["orden_precio"];
            $total = $total + $subtotal;
            $this->Cell(14,6,$row["item_cod"],2);
            $this->Cell(20,6,$row["orden_cantidad"],2);
            $this->Cell(100,6,$row["item_desc"],2);
            $this->Cell(25,6,number_format($row["orden_precio"],0,',','.'),2);
            $this->Cell(25,6,number_format($subtotal,0,',','.'),2);
            $this->Ln();
        }
        $this->Cell(159,6,'Totales',1);
        $this->Cell(25,6,number_format($total,0,',','.'),1);
        $this->Ln();
    }
}
$pdf = new PDF();
// Carga de datos
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->ordencompras($cab,$det);
$pdf->Output();
?>


<!-- Sintaxis para el ciclo while!!!


$i=0;
while($i < 10){
    echo $i;
    $i++;    
}


-->