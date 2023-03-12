<?php
/******************************************************/
/* Funcion paginar
 * actual:          Pagina actual
 * total:           Total de registros
 * por_pagina:      Registros por pagina
 * enlace:          Texto del enlace
 * Devuelve un texto que representa la paginacion
 */
function paginar($actual, $total, $por_pagina, $enlace) {
  $total_paginas = ceil($total/$por_pagina);
  $anterior = $actual - 1;
  $posterior = $actual + 1;
  if ($actual>1)
    $texto = "<a href=\"$enlace$anterior\">&laquo;</a> ";
  else
    $texto = "<b>&laquo;</b> ";
  for ($i=1; $i<$actual; $i++)
    $texto .= "<a href=\"$enlace$i\">$i</a> ";
  $texto .= "<b>$actual</b> ";
  for ($i=$actual+1; $i<=$total_paginas; $i++)
    $texto .= "<a href=\"$enlace$i\">$i</a> ";
  if ($actual<$total_paginas)
    $texto .= "<a href=\"$enlace$posterior\">&raquo;</a>";
  else
    $texto .= "<b>&raquo;</b>";
	$texto2 = "<i class='TEXTOCELDA43'>".$texto."</i>";
  return $texto2;
}
/*fin de la funcion de paginación*/  
?>