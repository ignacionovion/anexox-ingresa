<?php
//BindEvents Method @1-F2E27B4E
function BindEvents()
{
    global $VST_PI_REPORTESSearch;
    $VST_PI_REPORTESSearch->CCSEvents["BeforeShow"] = "VST_PI_REPORTESSearch_BeforeShow";
    $VST_PI_REPORTESSearch->CCSEvents["OnValidate"] = "VST_PI_REPORTESSearch_OnValidate";
    $VST_PI_REPORTESSearch->ds->CCSEvents["AfterExecuteSelect"] = "VST_PI_REPORTESSearch_ds_AfterExecuteSelect";
    $VST_PI_REPORTESSearch->ds->CCSEvents["BeforeExecuteSelect"] = "VST_PI_REPORTESSearch_ds_BeforeExecuteSelect";
    $VST_PI_REPORTESSearch->ds->CCSEvents["BeforeBuildSelect"] = "VST_PI_REPORTESSearch_ds_BeforeBuildSelect";
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

// -------------------------
//End Custom Code

//Close VST_PI_REPORTESSearch_BeforeShow @3-477BBB5E
    return $VST_PI_REPORTESSearch_BeforeShow;
}
//End Close VST_PI_REPORTESSearch_BeforeShow

//VST_PI_REPORTESSearch_OnValidate @3-5BCEE147
function VST_PI_REPORTESSearch_OnValidate(& $sender)
{
    $VST_PI_REPORTESSearch_OnValidate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESSearch; //Compatibility
//End VST_PI_REPORTESSearch_OnValidate

//Custom Code @80-2A29BDB7
// -------------------------
    // Write your own code here.
// -------------------------
//End Custom Code

//Close VST_PI_REPORTESSearch_OnValidate @3-7880DFD7
    return $VST_PI_REPORTESSearch_OnValidate;
}
//End Close VST_PI_REPORTESSearch_OnValidate

//VST_PI_REPORTESSearch_ds_AfterExecuteSelect @3-CC94CE52
function VST_PI_REPORTESSearch_ds_AfterExecuteSelect(& $sender)
{
    $VST_PI_REPORTESSearch_ds_AfterExecuteSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESSearch; //Compatibility
//End VST_PI_REPORTESSearch_ds_AfterExecuteSelect

//Custom Code @81-2A29BDB7
// -------------------------

// -------------------------
//End Custom Code

//Close VST_PI_REPORTESSearch_ds_AfterExecuteSelect @3-0DC11A05
    return $VST_PI_REPORTESSearch_ds_AfterExecuteSelect;
}
//End Close VST_PI_REPORTESSearch_ds_AfterExecuteSelect

//DEL  // -------------------------
//DEL  
//DEL  // -------------------------

//VST_PI_REPORTESSearch_ds_BeforeExecuteSelect @3-725567CE
function VST_PI_REPORTESSearch_ds_BeforeExecuteSelect(& $sender)
{
    $VST_PI_REPORTESSearch_ds_BeforeExecuteSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESSearch; //Compatibility
//End VST_PI_REPORTESSearch_ds_BeforeExecuteSelect

//Custom Code @83-2A29BDB7
// -------------------------

// -------------------------
//End Custom Code

//Close VST_PI_REPORTESSearch_ds_BeforeExecuteSelect @3-A29CE9C5
    return $VST_PI_REPORTESSearch_ds_BeforeExecuteSelect;
}
//End Close VST_PI_REPORTESSearch_ds_BeforeExecuteSelect

//VST_PI_REPORTESSearch_ds_BeforeBuildSelect @3-A4EE93AD
function VST_PI_REPORTESSearch_ds_BeforeBuildSelect(& $sender)
{
    $VST_PI_REPORTESSearch_ds_BeforeBuildSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESSearch; //Compatibility
//End VST_PI_REPORTESSearch_ds_BeforeBuildSelect

//Custom Code @84-2A29BDB7
// -------------------------
            		global $Tpl;
	if(CCGetGroupID() == "H" || CCGetGroupID() == "S")
	{
    	$Tpl->setvar("h_banco"," style='display: none;' ");
	}
	else
	{
		$Tpl->setvar("h_banco","");
	}
	if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
	{
		$Tpl->setvar("h_ies"," style='display: none;' ");
	}
	else
	{
		$Tpl->setvar("h_ies","");
	}
    if (CCGetParam("s_TIPO","")){    
	$db = new clsDBOracle_1();
	$db->query("ALTER SESSION SET NLS_DATE_FORMAT = 'dd/mm/yyyy'"); //AGREGADO PARA FORMATEAR LAS FECHAS
	 	
	if(CCGetParam("s_PAGO_TOTAL",""))
		$pago = CCGetParam("s_PAGO_TOTAL","");

	$resultado = "RUT;RUT BANCO;LICITACIÓN;OPERACIÓN;ARANCEL SOLICITADO;MONTO CRÉDITO;CREDITO UF;MONTO SEGURO IES;MONTO A PAGAR;MONTO OTRA CTA;PLAZO;TASA;FECHA CURSE;RUT IES;NOMBRE IES;FECHA PAGO; \r\n";		    
   $sql ="SELECT rut||';'||rutbco||';'||licitacion||';'||operacion||';'||arancelsolicitado||';'||montocredito||';'||creditouf||';'||montoseguroies||';'||montoapagar||';'||montootracta||';'||plazo||';'||tasa||';'||TO_CHAR(fechacurse, 'dd/mm/yyyy')||';'||ruties||';'||iest_nombre_ies||';'||TO_CHAR(fecha, 'dd/mm/yyyy') as dato from vst_pi_reportes WHERE 1=1 ";
	
	if(CCGetParam("s_RUT",""))
		$sql=$sql."	AND rut = ".CCGetParam("s_RUT","");	
	if(CCGetParam("s_LICITACION",""))
		$sql=$sql." AND licitacion = ".CCGetParam("s_LICITACION","");
	if(CCGetParam("s_OPERACION",""))
		$sql=$sql." AND OPERACION = ".CCGetParam("s_OPERACION","");
	if(CCGetParam("s_PAGO_TOTAL",""))
	{
		$sql=$sql." AND PAGO_TOTAL in (".$pago[0];
		if($pago[1])
			$sql=$sql.",".$pago[1];
		$sql=$sql.")";
	}
	

	if(CCGetParam("s_FECHA_d",""))
		$sql=$sql." AND trunc(FECHA) >= '".CCGetParam("s_FECHA_d","")."'";
	if(CCGetParam("s_FECHA_h",""))
		$sql=$sql." AND trunc(FECHA) <= '".CCGetParam("s_FECHA_h","")."'";


	if(CCGetGroupID() == "H" || CCGetGroupID() == "S")
	{
    	$bancn_cod = CCDLookUp("BANCN_COD","VST_USR_USUARIOS","USUAN_COD = ".CCGetUserID(),$db);
		$bancn_rut = CCDLookUp("RUT_BANCO","VST_INGRESA_BNC_BANCOS","BANCN_COD = ".$bancn_cod,$db);
		$sql=$sql."  AND RUTBCO = ".$bancn_rut;
	}
	else
	{
		if(CCGetParam("s_RUTBCO",""))
			$sql=$sql." AND RUTBCO = ".CCGetParam("s_RUTBCO","");
	}
		if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
	{
		$iesn_cod = CCDLookUp("IESN_COD","VST_USR_USUARIOS","USUAN_COD = ".CCGetUserID(),$db);
		$tiesn_cod = CCDLookUp("TIESN_COD","VST_USR_USUARIOS","USUAN_COD = ".CCGetUserID(),$db);

    	$ies_rut = CCDLookUp("IESN_RUT","VST_OA_IES","IESN_COD = ".$iesn_cod." AND TIESN_COD =".$tiesn_cod,$db);

		$sql=$sql." AND RUTIES = ".$ies_rut;
	}
	else
	{
		if(CCGetParam("s_IESN_RUT",""))
			$sql=$sql." AND RUTIES = ".CCGetParam("s_IESN_RUT","");
	}
//	echo $pago[2]; exit;
	
	$nombre_reporte = "Reporte_".$fecha_actual.".csv";
	$db->query($sql);
	//echo $sql;exit;
	while ( $db->next_record() )
	{
		$resultado .= str_replace(array("\r", "\n"),"",$db->f("dato"))."\r\n";
		//echo $resultado;exit;
		
	}
	 // Exportar el Resultado a un archivo txt y mostrarlo por pantalla
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename='.$nombre_reporte);
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public"); 
	//
	echo $resultado;
//	exit;
}
// -------------------------
//End Custom Code

//Close VST_PI_REPORTESSearch_ds_BeforeBuildSelect @3-C2C8A91B
    return $VST_PI_REPORTESSearch_ds_BeforeBuildSelect;
}
//End Close VST_PI_REPORTESSearch_ds_BeforeBuildSelect


?>
