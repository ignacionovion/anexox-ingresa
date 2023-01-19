<?php

//BindEvents Method @1-C7C9C290
function BindEvents()
{
    global $VST_PI_REPORTES;
    global $CCSEvents;
    $VST_PI_REPORTES->CCSEvents["BeforeShow"] = "VST_PI_REPORTES_BeforeShow";
}
//End BindEvents Method

//VST_PI_REPORTES_BeforeShow @2-F05B9B94
function VST_PI_REPORTES_BeforeShow(& $sender)
{
    $VST_PI_REPORTES_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_REPORTES; //Compatibility
//End VST_PI_REPORTES_BeforeShow

//Custom Code @33-2A29BDB7
// -------------------------
 //   echo $VST_PI_REPORTES->ds->Where;
// -------------------------
//End Custom Code

//Close VST_PI_REPORTES_BeforeShow @2-87580859
    return $VST_PI_REPORTES_BeforeShow;
}
//End Close VST_PI_REPORTES_BeforeShow

//Page_BeforeInitialize @1-0DDB08DF
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $consulta_rut; //Compatibility
//End Page_BeforeInitialize

//Custom Code @26-2A29BDB7
// -------------------------
    $db = new clsDBOracle_1();
		
	if(CCGetGroupID() == "H" || CCGetGroupID() == "S")
	{
    	$bancn_cod = CCDLookUp("BANCN_COD","vst_usr_usuarios2016","USUAN_COD = ".CCGetUserID(),$db);
		CCSetSession("BANCN_COD",$bancn_cod);
	}
    else
		CCSetSession("BANCN_COD","");
	if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
	{
    	$iesn_cod = CCDLookUp("IESN_COD","vst_usr_usuarios2016","USUAN_COD = ".CCGetUserID(),$db);
		$tiesn_cod = CCDLookUp("TIESN_COD","vst_usr_usuarios2016","USUAN_COD = ".CCGetUserID(),$db);
		$ies_rut = CCDLookUp("IESN_RUT","VST_SGI_OA_IES","IESN_COD = ".$iesn_cod." AND TIESN_COD =".$tiesn_cod,$db);
		CCSetSession("RUTIES",$ies_rut);
		CCSetSession("ESTADOT",1);
	}
	else
	{
		CCSetSession("IESN_COD",'');
		CCSetSession("TIESN_COD",'');
		CCSetSession("ESTADOT",'');
	}
// -------------------------
//End Custom Code

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
