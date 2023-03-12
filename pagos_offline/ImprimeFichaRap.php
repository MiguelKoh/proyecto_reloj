<?php
//function crear1_pdf($nombres,$apellidoPaterno,$apellidoMaterno,$ElFolio){
//error_reporting(0);
require_once('libpdf/fpdf.php');   
require_once('libpdf/fpdi.php'); 
require_once('funciones.php'); 



	$nombre_Completo = $_GET['txtNom'];
        $nombreCompleto = strtoupper(eliminaAcentos($nombre_Completo));

	$ElFolio = $_GET['txtFolio'];
	/**** Se Genera el PDF a partir de la Plantilla respectiva****/
	
	// initiate FPDI
	$pdf = new FPDI();
	// add a page
	$pdf->AddPage();
	// set the sourcefile
	$pdf->setSourceFile('PDFs/FichaRapida.pdf');		//Ubicaci�n de PDF de la Ficha Rapida
	// import page 1
	$tplIdx = $pdf->importPage(1);
	// use the imported page and place it at point 10,10 with a width of 100 mm
	$pdf->useTemplate($tplIdx, 0, 0);
	
	$pdf->SetFont('Arial','B', 8);			//Tama�o y tipo de la letra
	//$pdf->SetTextColor(0,0,0);
	
	/******* Ahora se imprimen los datos de la HED *******/
	
	
	//$nombreCompleto =  $nombres." ".$apellidoPaterno." ".$apellidoMaterno;

	$pdf->SetXY(99, 51);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $nombreCompleto);	
	
	$pdf->SetXY(99, 143);					//Coordenadas de impresion (columna,fila)
	$pdf->Write(0, $nombreCompleto);		
	
	/******* Se Genera la salida en un nuevo archivo PDF *******/
	$pdf->Output('FichaDePago'.$ElFolio.'.pdf', 'D');	//Genera el PDF como archivo para descarga	
//}	
?>
