<?php
//function crear1_pdf($nombres,$apellidoPaterno,$apellidoMaterno,$ElFolio){
//error_reporting(0);
include("conex.php");
$link=Conectarse();
require_once('libpdf/fpdf.php');   
require_once('libpdf/fpdi.php');   

	$nombreCompleto = $_GET['txtNom'];
	$ElFolio = $_GET['txtFolio'];
	$sdate=date("d")."/".date("m")."/".date("Y");
	//$sdate="19/05/2013";
	$stime=date("h").":".date("i");
		
	$res = mysql_query("Select * from aspirantes where Folio = " . $ElFolio ,$link);
	//echo $res;
	if ($row = mysql_fetch_array($res)){
		$Curso = $row['Curso'];
		if($Curso==2)
		$inscribe="Segundo";
		if($Curso==3)
		$inscribe="Tercero";
		
		$Direccion = $row['Direccion'];
		$Correo = $row['Correo'];
		$Telefono = $row['Tel1'];
		$Celular = $row['Tel2'];
		$Movimiento = $row['movimiento'];
		/*echo "<script>alert('" . $NomComp . "');</script>";*/
	}				
	/**** Se Genera el PDF a partir de la Plantilla respectiva****/
	
	// initiate FPDI
	$pdf = new FPDI();
	// add a page
	$pdf->AddPage();
	// set the sourcefile
	$pdf->setSourceFile('PDFs/PaseRapida.pdf');		//Ubicaci�n de PDF de la Ficha Rapida
	// import page 1
	$tplIdx = $pdf->importPage(1);
	// use the imported page and place it at point 10,10 with a width of 100 mm
	$pdf->useTemplate($tplIdx, 0, 0);
	
	$pdf->SetFont('Arial','B', 11);			//Tama�o y tipo de la letra
	//$pdf->SetTextColor(0,0,0);
	
	/******* Ahora se imprimen los datos de la HED *******/
	
	
	//$nombreCompleto =  $nombres." ".$apellidoPaterno." ".$apellidoMaterno;
	$pdf->SetXY(175, 84);	//Coordenadas de impresion (columna,fila)
	$pdf->Write(0,$sdate);
	
	$pdf->SetXY(175, 95);	//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $ElFolio);

	$pdf->SetXY(99, 121);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $nombreCompleto);	
	
	$pdf->SetXY(110, 126);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $inscribe);
	
	$pdf->SetXY(80, 131);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $Direccion);
	
	$pdf->SetXY(105, 136);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $Correo);
	
	$pdf->SetXY(100, 141);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $Telefono);
	
	$pdf->SetXY(100, 146);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $Celular);
	
	$pdf->SetXY(125, 151);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $Movimiento);
	$pdf->SetFont('Arial','B', 8);	
	$pdf->SetXY(80, 245);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $nombreCompleto);
	
	$pdf->SetXY(164, 248.5);	
	//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, date("d")."".date("m")."".date("Y")."-".$stime."-".$ElFolio);
	//$pdf->Write(0,"19052013-".$stime."-".$ElFolio);
	/******* Se Genera la salida en un nuevo archivo PDF *******/
	$pdf->Output('PaseDeIngresoExamen'.$ElFolio.'.pdf', 'D');	//Genera el PDF como archivo para descarga	
//}	
?>
