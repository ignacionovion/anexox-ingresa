<?php
//BindEvents Method @1-55AA3EA4
function BindEvents()
{
    global $VST_PI_REPORTESCIERR;
    $VST_PI_REPORTESCIERR->CCSEvents["BeforeShow"] = "VST_PI_REPORTESCIERR_BeforeShow";
    $VST_PI_REPORTESCIERR->CCSEvents["OnValidate"] = "VST_PI_REPORTESCIERR_OnValidate";
    $VST_PI_REPORTESCIERR->ds->CCSEvents["AfterExecuteSelect"] = "VST_PI_REPORTESCIERR_ds_AfterExecuteSelect";
    $VST_PI_REPORTESCIERR->ds->CCSEvents["BeforeExecuteSelect"] = "VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect";
    $VST_PI_REPORTESCIERR->ds->CCSEvents["BeforeBuildSelect"] = "VST_PI_REPORTESCIERR_ds_BeforeBuildSelect";
}
//End BindEvents Method

//VST_PI_REPORTESCIERR_BeforeShow @3-38B55841
function VST_PI_REPORTESCIERR_BeforeShow(& $sender)
{
    $VST_PI_REPORTESCIERR_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESCIERR; //Compatibility
//End VST_PI_REPORTESCIERR_BeforeShow

//Custom Code @73-2A29BDB7
// -------------------------
global $Tpl;

	if(CCGetGroupID() == "A" || CCGetGroupID() == "K")
	{
    	$Tpl->setvar("on_submit",' onsubmit="return validateFormCIERR" ');
	}

if( $VST_PI_REPORTESCIERR->Validate() == 1)
{

	
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
	$error = 0;
	if(CCGetGroupID() == "A" || CCGetGroupID() == "K")
	{
		if(!CCGetParam("s_RUT","") && !CCGetParam("s_LICITACION","") && !CCGetParam("s_OPERACION","") && !CCGetParam("s_IESN_RUT","") && !CCGetParam("s_RUTBCO",""))
			$error = 1;
	}

if (CCGetParam("s_TIPO","") && $error== 0){    

	$db = new clsDBOracle_1();
	$db->query("ALTER SESSION SET NLS_DATE_FORMAT = 'dd/mm/yyyy'"); //AGREGADO PARA FORMATEAR LAS FECHAS
	 	

	$resultado = "RUT;RUT BANCO;NOMBRE BANCO;AÑO LICITACIÓN;AñO OPERACIÓN;ARANCEL SOLICITADO;RUT IES;NOMBRE IES \r\n";		    
    $sql ="SELECT rut||';'||rut_banco_administrador||';'||nombre_banco||';'||ano_licitacion||';'||ano_operacion||';'||arancel_solicitado||';'||iesn_rut||';'||iest_nombre_ies as dato from vst_pi_reporte_hist WHERE 1=1 ";


	if(CCGetParam("s_RUT",""))
		$sql=$sql."	AND rut = ".CCGetParam("s_RUT","");	
	if(CCGetParam("s_LICITACION",""))
		$sql=$sql." AND ano_licitacion = ".CCGetParam("s_LICITACION","");
	if(CCGetParam("s_OPERACION",""))
		$sql=$sql." AND ano_operacion = ".CCGetParam("s_OPERACION","");


	if(CCGetGroupID() == "H" || CCGetGroupID() == "S")
	{
    	$bancn_cod = CCDLookUp("BANCN_COD","VST_USR_USUARIOS","USUAN_COD = ".CCGetUserID(),$db);
		$bancn_rut = CCDLookUp("RUT_BANCO","VST_INGRESA_BNC_BANCOS","BANCN_COD = ".$bancn_cod,$db);
		$sql=$sql."  AND rut_banco_administrador = ".$bancn_rut;
	}
	else
	{
		if(CCGetParam("s_RUTBCO",""))
			$sql=$sql." AND rut_banco_administrador = ".CCGetParam("s_RUTBCO","");
	}
	if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
	{
		$iesn_cod = CCDLookUp("IESN_COD","VST_USR_USUARIOS","USUAN_COD = ".CCGetUserID(),$db);
		$tiesn_cod = CCDLookUp("TIESN_COD","VST_USR_USUARIOS","USUAN_COD = ".CCGetUserID(),$db);

    	$ies_rut = CCDLookUp("IESN_RUT","VST_OA_IES","IESN_COD = ".$iesn_cod." AND TIESN_COD =".$tiesn_cod,$db);

		$sql=$sql." AND IESN_RUT = ".$ies_rut;
	}
	else
	{
		if(CCGetParam("s_IESN_RUT",""))
			$sql=$sql." AND IESN_RUT = ".CCGetParam("s_IESN_RUT","");
	}

	
	$nombre_reporte = "Reporte_Cierre_".$fecha_actual.".csv";
	$db->query($sql);

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
	exit;
}
}
// -------------------------
//End Custom Code

//Close VST_PI_REPORTESCIERR_BeforeShow @3-507B5F58
    return $VST_PI_REPORTESCIERR_BeforeShow;
}
//End Close VST_PI_REPORTESCIERR_BeforeShow

//VST_PI_REPORTESCIERR_OnValidate @3-9A5EC35F
function VST_PI_REPORTESCIERR_OnValidate(& $sender)
{
    $VST_PI_REPORTESCIERR_OnValidate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESCIERR; //Compatibility
//End VST_PI_REPORTESCIERR_OnValidate

//Custom Code @80-2A29BDB7
// -------------------------
	
    if(CCGetGroupID() == "A" || CCGetGroupID() == "K")
	{
		if(!CCGetParam("s_RUT","") && !CCGetParam("s_LICITACION","") && !CCGetParam("s_OPERACION","") && !CCGetParam("s_IESN_RUT","") && !CCGetParam("s_RUTBCO",""))
			$VST_PI_REPORTESCIERR->Errors->addError("Debe seleccionar al menos un filtro");
	}
// -------------------------
//End Custom Code

//Close VST_PI_REPORTESCIERR_OnValidate @3-6F803BD1
    return $VST_PI_REPORTESCIERR_OnValidate;
}
//End Close VST_PI_REPORTESCIERR_OnValidate

//VST_PI_REPORTESCIERR_ds_AfterExecuteSelect @3-CA9E0DAD
function VST_PI_REPORTESCIERR_ds_AfterExecuteSelect(& $sender)
{
    $VST_PI_REPORTESCIERR_ds_AfterExecuteSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESCIERR; //Compatibility
//End VST_PI_REPORTESCIERR_ds_AfterExecuteSelect

//Custom Code @81-2A29BDB7
// -------------------------

// -------------------------
//End Custom Code

//DEL  // -------------------------
//DEL  
//DEL  // -------------------------

//Close VST_PI_REPORTESCIERR_ds_AfterExecuteSelect @3-AD03457F
    return $VST_PI_REPORTESCIERR_ds_AfterExecuteSelect;
}
//End Close VST_PI_REPORTESCIERR_ds_AfterExecuteSelect

//VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect @3-ABB76DEE
function VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect(& $sender)
{
    $VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESCIERR; //Compatibility
//End VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect

//Custom Code @83-2A29BDB7
// -------------------------

// -------------------------
//End Custom Code

//Close VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect @3-12ECB3B8
    return $VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect;
}
//End Close VST_PI_REPORTESCIERR_ds_BeforeExecuteSelect

//VST_PI_REPORTESCIERR_ds_BeforeBuildSelect @3-08FD5F79
function VST_PI_REPORTESCIERR_ds_BeforeBuildSelect(& $sender)
{
    $VST_PI_REPORTESCIERR_ds_BeforeBuildSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTESCIERR; //Compatibility
//End VST_PI_REPORTESCIERR_ds_BeforeBuildSelect

//Custom Code @84-2A29BDB7
// -------------------------

// -------------------------
//End Custom Code

//Close VST_PI_REPORTESCIERR_ds_BeforeBuildSelect @3-0A75ABFB
    return $VST_PI_REPORTESCIERR_ds_BeforeBuildSelect;
}
//End Close VST_PI_REPORTESCIERR_ds_BeforeBuildSelect
?>
