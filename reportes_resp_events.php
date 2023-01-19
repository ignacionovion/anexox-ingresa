<?php
//BindEvents Method @1-F463BAE0
function BindEvents()
{
    global $VST_PI_REPORTESSearch;
    $VST_PI_REPORTESSearch->CCSEvents["BeforeShow"] = "VST_PI_REPORTESSearch_BeforeShow";
}
//End BindEvents Method

//VST_PI_REPORTESSearch_BeforeShow @3-A45899BC
function VST_PI_REPORTESSearch_BeforeShow(& $sender)
{
    $VST_PI_REPORTESSearch_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESSearch; //Compatibility
//End VST_PI_REPORTESSearch_BeforeShow

//Custom Code @73-2A29BDB7
// -------------------------
    if (CCGetParam("s_RUT","")){    
	$db = new clsDBOracle_1();
	 	
	if(CCGetParam("s_PAGO_TOTAL",""))
		$pago = CCGetParam("s_PAGO_TOTAL","");

	$resultado = "RUT;RUT BANCO;LICITACIÓN;OPERACIÓN;;ARANCEL SOLICITADO;MONTO CRÉDITO;CREDITO UF;MONTO SEGURO IES;MONTO A PAGAR;MONTO OTRA CTA;PLAZO;TASA;FECHA CURSE;RUT IES;NOMBRE IES;FECHA PAGO; \r\n";		    
   $sql ="SELECT rut, rutbco, licitacion, operacion, arancelsolicitado, montocredito, creditouf, montoseguroies, montoapagar, montootracta, plazo, tasa, fechacurse, ruties, iest_nombre_ies, fecha
    from vst_pi_reportes
	WHERE 1=1 ";
	
	if(CCGetParam("s_RUT",""))
		$sql=$sql."	AND rut = ".CCGetParam("s_RUT","");	
	if(CCGetParam("s_LICITACION",""))
		$sql=$sql."	AND licitacion = ".CCGetParam("s_LICITACION","");
	if(CCGetParam("s_OPERACION",""))
		$sql=$sql."	AND OPERACION = ".CCGetParam("s_OPERACION","");
	if(CCGetParam("s_PAGO_TOTAL",""))
	{
		$sql=$sql."	AND PAGO_TOTAL in (".$pago[0];
		if($pago[1])
			$sql=$sql.",".$pago[1];
		$sql=$sql.")";
	}
	if(CCGetParam("s_IESN_RUT",""))
		$sql=$sql." AND RUTIES = ".CCGetParam("s_IESN_RUT","");
	if(CCGetParam("s_RUTBCO",""))
		$sql=$sql." AND RUTBCO = ".CCGetParam("s_RUTBCO","");
	if(CCGetParam("s_FECHA_d",""))
		$sql=$sql." AND FECHA >= '".CCGetParam("s_FECHA_d","")."'";
	if(CCGetParam("s_FECHA_h",""))
		$sql=$sql." AND FECHA <= '".CCGetParam("s_FECHA_h","")."'";

//	echo $pago[2]; exit;
	
	$nombre_reporte = "Reporte_".$fecha_actual.".csv";
	$db->query($sql);
	echo $sql;exit;
	while ( $db->next_record() )
	{
		$resultado .= str_replace(array("\r", "\n"),"",$db->f("dato"))."\r\n";
		
	}
	 // Exportar el Resultado a un archivo txt y mostrarlo por pantalla
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$nombre_reporte);
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public"); 
	//
	echo $resultado;
	//exit;
}
// -------------------------
//End Custom Code

//Close VST_PI_REPORTESSearch_BeforeShow @3-477BBB5E
    return $VST_PI_REPORTESSearch_BeforeShow;
}
//End Close VST_PI_REPORTESSearch_BeforeShow


?>
