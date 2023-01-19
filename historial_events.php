<?php
//BindEvents Method @1-2249C393
function BindEvents()
{
    global $BNC_BANCOS_CRG_HST_CARGAS1;
    $BNC_BANCOS_CRG_HST_CARGAS1->CCSEvents["BeforeShowRow"] = "BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow";
}
//End BindEvents Method

//BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow @2-4BC2255C
function BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow(& $sender)
{
    $BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $BNC_BANCOS_CRG_HST_CARGAS1; //Compatibility
//End BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow

//Custom Code @41-2A29BDB7
// -------------------------
    global $Tpl;
      	if ($BNC_BANCOS_CRG_HST_CARGAS1->ESTADO->GetValue() == "Finalizada Exitosamente" )
		{
			$Tpl->setvar("errores_ies","none");
		}
		else
		{
			$Tpl->setvar("errores_ies","display");
		}
// -------------------------
//End Custom Code

//Close BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow @2-79901216
    return $BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow;
}
//End Close BNC_BANCOS_CRG_HST_CARGAS1_BeforeShowRow


?>
