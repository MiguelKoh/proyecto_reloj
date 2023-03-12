<?php
class Laboratorios
{
	public static $IDS_LABS = array(
		'QUI1' => array(
				'MATERIA'=> 82, #Quimica 1
				'LAB'	 => 71  #Lab Quimica 1
			  ),
		'QUI2' => array(
				'MATERIA'=> 16, #Quimica 2
				'LAB'	 => 72  #Lab Quimica 2
			  ),
		'COM1' => array(
				'MATERIA'=> 9, #Computo 1
				'LAB'	 => 9  #Computo 1
			  ),
		'FIS1' => array(
				'MATERIA'=> 25, #Fisica 1
				'LAB'	 => 74  #Lab Fisica 1
			  ),
		'FIS2' => array(
				'MATERIA'=> 34, #Fisica 2
				'LAB'	 => 76  #Lab Fisica 2
			  ),
		'BIO1' => array(
				'MATERIA'=> 2, #Biologia 1
				'LAB'	 => 73 #Lab Biologia 1
			  ),
		'BIO2' => array(
				'MATERIA'=> 32, #Biologia 2
				'LAB'	 => 75  #Lab Biologia 2
			  )
		);

	public static $NOMBRES_LABS = array(
			1 => 'QUI1',
			2 => 'QUI2',
			3 => 'FIS1,BIO1',
			4 => 'FIS2,BIO2'
			);
}
