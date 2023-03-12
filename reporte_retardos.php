<?php
    session_start();
    include('conex.php'); 
    include("funciones_reloj.php");
    $cn = ConectaBD();
    require('includes/fpdf.php');
    setlocale(LC_ALL,'esp');
    $fechaini = $_SESSION["fechaini"];
    $fechafin = $_SESSION["fechafin"]; 
    $BuscoPeriodo="SELECT idperiodo FROM periodos WHERE fecha_inicio='".$fechaini."' 
    AND fecha_fin='".$fechafin."'";
    $queryBusca=mysqli_query($cn,$BuscoPeriodo);
    $busca=mysqli_fetch_array($queryBusca);
    $id_periodo=$busca['idperiodo'];       
    error_reporting (E_ALL ^ E_NOTICE);
    $salto_linea = 0;
    $veces = 1;

    $sente = "Select distinct e.idemp, e.nombre, r.departamento, t.idTipo,t.descripcion as tipoEmp,p.checa from retardos_temp as r 
	inner join empleado as e on r.idemp = e.idemp inner join tipoEmpleado as t on e.idtipo=t.idtipo 
	LEFT JOIN empleados_privilegios p ON p.idEmp=r.idEmp
	where (e.estatus is null || e.estatus ='') and (e.practicante is null || e.practicante='') 
   AND p.checa is null order by t.idtipo,e.nombre,r.departamento";
    $result = mysqli_query($cn,$sente); 
    $verifica=mysqli_num_rows($result); 
       

class PDF extends FPDF
{
	//Cabecera de página
	function Header()
	{
		$this->Ln(10);
	}

	//Pie de página
	function Footer()
	{
		//Posición: a 1,5 cm del final
		$this->SetY(-15);

	}

}
//Cell($w,$h=0,$txt='',$border=0,$ln=0,$align='',$fill=0,$link='')
//Image($file,$x,$y,$w=0,$h=0,$type='',$link='')
//MultiCell($w,$h,$txt,$border=0,$align='J',$fill=0)
//Creación del objeto de la clase heredada


$pdf=new PDF('P','mm','A4');
$pdf->AliasNbPages();
//$pdf->AddPage();
//datos del alumno
   
   
    while ($row = mysqli_fetch_array($result)) {

    	$clave  = $row['idemp'];
        $nombre =$row['nombre'];
        
        $departamento = utf8_encode($row['departamento']);
        $tipoEmp = $row['tipoEmp'];
        $idTipoE= $row['idTipo'];
        if($idTipoE==1 || $idTipoE==2)
        	$fechas="";
        else
        	$fechas='';
        $encabezado = $departamento . " - " . $clave . " - " . $nombre  ;    
        $clave = $row['idemp'];
        //$nombre =  $row['nombre'];
        $departamento = utf8_encode($row['departamento']);
        $encabezado = " [" . $clave  . "] ".$nombre ." [" . $departamento."]" ;
    	/***********************si tiene caso mostrar su descuento*****/
			$numr=0;
			$sente1 = "select fecha, horario, checadaini1, checadafin1, ausente, mindescuento, totminsdesc, " .
			"minPermiso, observaciones from retardos_temp " .
			"where  minDescuento>0 ".$fechas." AND idemp=" . $clave ;
			$result1 = mysqli_query($cn,$sente1) ;
			$numr=mysqli_num_rows($result1);
			while ($row1 = mysqli_fetch_array($result1)) {
				$SQLeventos="SELECT tipoempleado FROM tcalendario 
	        	WHERE fecha='".$row1['fecha']."' AND tipoempleado=".$idTipoE;
	        	$querySQL=mysqli_query($cn,$SQLeventos);
	        	$existeEvento=mysqli_num_rows($querySQL);
	        	if($existeEvento>0)
	        	$numr--;
        }
        //echo $numr."<br>";
        	if($numr>0){

		/****************************************************************/	

         $veces ++;
         $quita=0;
         $numbaja=0;
        
        $se_imprime = esPar($veces);       
            
        
    
        
if($se_imprime==1){
	$pdf->AddPage();
	
}
  
    $pdf->SetLineWidth(.5);
    $pdf->Rect(8, 15, 193, 25, 'D');
    if( $verifica>1){
    $pdf->Rect(8, 155, 193, 25, 'D');	
    }
	$t=0;
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,5,"",0,0,'C');
	$pdf->Cell(150,5,"ESCUELA PREPARATORIA DOS - UADY",0,1,'C');
	$pdf->Cell(15,5,"",0,0,'C');
	$pdf->Cell(150,5,"REPORTE DE RETARDOS",0,1,'C');
	$pdf->Cell(5,5,"",0,0,'C');
	$pdf->Cell(160,5,$encabezado,0,1,'L');
	$pdf->Cell(5,5,"",0,0,'C');
	$pdf->Cell(16,5,"Periodo:",0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(50,5,$fechaini." al ".$fechafin,0,0,'L');
	$pdf->Cell(80,5,"  Tipo de Empleado: ".$tipoEmp,0,0,'L');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(40,5,"  Impreso: ".date(d."/".m."/".Y),0,1,'L');	
	$pdf->SetLineWidth(.5);
	$pdf->Rect(8, 40, 193, 75, 'D');
	  if( $verifica>1){
		$pdf->Rect(8, 180, 193, 75, 'D');
	}	
	$pdf->SetFont('Arial','B',10);
	//$pdf->Cell(5,5,"",0,0,'C');
	$pdf->Cell(35,5,"Fecha",0,0,'C');
	$pdf->Cell(20,5,"Horario",0,0,'C');
	$pdf->Cell(20,5,"Entrada",0,0,'C');
	$pdf->Cell(20,5,"Salida",0,0,'C');
	$pdf->Cell(20,5,"Permiso",0,0,'C');
	$pdf->Cell(20,5,"Ausente",0,0,'C');
	$pdf->Cell(20,5,"Descuento",0,0,'C');
	$pdf->Cell(30,5,"Observaciones",0,1,'C');
	$sum=0;
	$sente1 = "select fecha, horario, checadaini1, checadafin1, ausente, mindescuento, totminsdesc, " .
                    "minPermiso, observaciones from retardos_temp " .
                     "where  minDescuento>0 ".$fechas." AND idemp=" . $clave ;
                 $result1 = mysqli_query($cn,$sente1) ;
                 $numr=mysqli_num_rows($result1);

             
                $quita=5*$numr;
                $numbaja=65-$quita;                 
                $tot_min_descuento = 0;
                // # lineas del encabezado y titulaes
                
                while ($row1 = mysqli_fetch_array($result1)) {

                	$SQLeventos="SELECT tipoempleado FROM tcalendario 
                	WHERE fecha='".$row1['fecha']."' AND tipoempleado=".$idTipoE;
                	$querySQL=mysqli_query($cn,$SQLeventos);
                	$existeEvento=mysqli_num_rows($querySQL);
                	if($existeEvento>0){
                		$sum=$sum+5;
					}else{
                	$horario = $row1['horario'];
                    $fecha = $row1['fecha'];
                    $checadaIni1 = $row1['checadaini1'];
                    $checadaFin1 = $row1['checadafin1'];
                    $permiso = $row1['minPermiso'];
                    $ausente = $row1['ausente'];
                    $minDescuento = $row1['mindescuento'];
                    $tot_min_descuento += $minDescuento;
					$observaciones = $row1['observaciones'];                  
                    
                    //Pasa minutos a formato hh:mm
                    
                    $permiso_formato = convierte_mins_a_horas($permiso,"");
                    $minDescuento_formato = convierte_mins_a_horas($minDescuento,"");
                    $tot_min_descuento_formato = convierte_mins_a_horas($tot_min_descuento,"N");
                    //se agrega para cambiar formato de fecha 
                   $pdf->SetFont('Arial','',10);
                    list($dia, $mes, $año) = explode('/', cambiafnormal($fecha)); 
                    $fechaMuestra=$dia."-".mes($mes)."-".$año; 
					//$pdf->Cell(5,5,"",0,0,'C');
					$pdf->Cell(35,5,saber_dia($año."-".$mes."-".$dia).", ". $fechaMuestra,0,0,'L');
					$pdf->Cell(20,5,$horario,0,0,'C');
					$pdf->Cell(20,5,$checadaIni1,0,0,'C');
					$pdf->Cell(20,5,$checadaFin1,0,0,'C');
					$pdf->Cell(20,5,$permiso_formato,0,0,'C');
					$pdf->Cell(20,5,$ausente,0,0,'C');
					$pdf->Cell(20,5,$minDescuento_formato ,0,0,'C');
					$pdf->Cell(20,5,$observaciones,0,1,'C');
				}//fin del if evento

	  // # lineas del encabezado y titulaes
	 }
            //deben ser 75 maximo
	//echo $numbaja."<br>";
                $pdf->Ln($numbaja+$sum);
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(5,5,"",0,0,'C');
$texto="";
				$cantidad=0;
				if($id_periodo%2==0)
				 $idPeriodoAnt=$id_periodo-1;
				else
                 $idPeriodoAnt=$id_periodo;

			     $SQLinicio="SELECT cantidad FROM descuento_administrativos 
			      WHERE idEmp='".$clave."' AND idPeriodo='".$idPeriodoAnt."'";
			      $queryexiste=mysqli_query($cn,$SQLinicio); 
			      $numReg=mysqli_num_rows($queryexiste);
			      if($numReg>0){
			        $rowc=mysqli_fetch_array($queryexiste);
			        $cantidad=$rowc['cantidad']*15;
			      }
			      if($cantidad>0 && $cantidad<90){
			      	$texto="En la 1era. quincena de noviembre utilizaste ".$cantidad." de los 90 minutos permitidos al mes.";
			      }else{
			      	 if($cantidad>0 && $cantidad>=90){
			      	$texto="En la 1era. quincena de noviembre agotaste los 90 minutos permitidos al mes.";
			      	}
			      }
			      $pdf->SetFont('Arial','',8);
			      //$pdf->Cell(90,5,$texto,0,0,'L');  
			      $pdf->Cell(90,5,'',0,0,'L');  
			     $pdf->SetFont('Arial','B',10);
			   $pdf->Cell(90,5,"Total de Horas a Descontar:       ".$tot_min_descuento_formato,0,1,'R');
			    $pdf->SetLineWidth(.5);
				$pdf->Rect(8, 110, 193, 30, 'D');
				  if( $verifica>1){
				$pdf->Rect(8, 250, 193, 30, 'D');	
				}				
				$pdf->Cell(5,5,"",0,0,'C');
				$pdf->Cell(170,5,"Firma del Empleado:" ,0,1,'L');
				$pdf->Ln(40);
			}//de mostrar descuento
}


$pdf->Output();?>