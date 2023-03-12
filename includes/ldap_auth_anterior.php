<?php
function ldaph_auth_b($l,$p)
{
	$sld="148.209.5.7";
	$portld=389;
	$ds=ldap_connect($sld,$portld);//Debe ser un servidor LDAP valido!
	if($ds && $l != "" && $p != "")
	{
		ldap_set_option($ds,LDAP_OPT_PROTOCOL_VERSION,3);
		if($r=@ldap_bind($ds,$l."@inet.uady.mx",$p)) //Usuario autentificado
		{
			$ret = 1;
		} else
		{
			$ret = 0;
		}																						
		ldap_close($ds);
	}
	else
	{
		$ret = 0;
	}
	return $ret;
}
?>