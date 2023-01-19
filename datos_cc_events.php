<?php

//BindEvents Method @1-B27BA481
function BindEvents()
{
    global $PI_DATOS_CC;
    global $CCSEvents;
    $PI_DATOS_CC->CCSEvents["BeforeShow"] = "PI_DATOS_CC_BeforeShow";
}
//End BindEvents Method

//PI_DATOS_CC_BeforeShow @2-18834B45
function PI_DATOS_CC_BeforeShow(& $sender)
{
    $PI_DATOS_CC_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_BeforeShow

//Custom Code @26-2A29BDB7
// -------------------------
        if((CCGetGroupID() == "C" || CCGetGroupID() == "D") )
	{
		global $Tpl;
		$Tpl->setvar("ies"," style='display: none;' ");
	}


	if((CCGetGroupID() == "H") )
	{
		global $Tpl;
		$Tpl->setvar("bnc"," style='display: none;' ");
	}
	
	if(CCGetParam("consulta","") == 1 )
	{
		global $Tpl;
		$Tpl->setvar("consulta"," style='display: none;' ");
	}
// -------------------------
//End Custom Code

//Close PI_DATOS_CC_BeforeShow @2-536ED776
    return $PI_DATOS_CC_BeforeShow;
}
//End Close PI_DATOS_CC_BeforeShow

//Page_BeforeInitialize @1-DFB9AA73
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $datos_cc; //Compatibility
//End Page_BeforeInitialize

//Custom Code @25-2A29BDB7
// -------------------------

    if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
	{

		$cod = 3;
		global $Redirect;
		$Redirect = "datos_cc_record.php";
		
	}
// -------------------------
//End Custom Code

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
