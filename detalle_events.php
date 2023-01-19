<?php
//BindEvents Method @1-6F728963
function BindEvents()
{
    global $VST_PI_REPORTES;
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

//Custom Code @23-2A29BDB7
// -------------------------
	$tipo_cod = $VST_PI_REPORTES->h_tipo_cod->GetValue();
    if($tipo_cod==10 || $tipo_cod==11 || $tipo_cod==12 || $tipo_cod==20 )
	{
		global $Tpl;
		$Tpl->setvar('tipo1'," style='display: none;' ");
		$Tpl->setvar('noaplica',"No Aplica");



	}
// -------------------------
//End Custom Code

//Close VST_PI_REPORTES_BeforeShow @2-87580859
    return $VST_PI_REPORTES_BeforeShow;
}
//End Close VST_PI_REPORTES_BeforeShow


?>
