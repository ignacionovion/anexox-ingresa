<?php
//BindEvents Method @1-1F1E873B
function BindEvents()
{
    global $PI_TRANSFERENCIAS;
    $PI_TRANSFERENCIAS->CCSEvents["BeforeShow"] = "PI_TRANSFERENCIAS_BeforeShow";
    $PI_TRANSFERENCIAS->CCSEvents["OnValidate"] = "PI_TRANSFERENCIAS_OnValidate";
}
//End BindEvents Method

//PI_TRANSFERENCIAS_BeforeShow @2-F5A298CC
function PI_TRANSFERENCIAS_BeforeShow(& $sender)
{
    $PI_TRANSFERENCIAS_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_TRANSFERENCIAS; //Compatibility
//End PI_TRANSFERENCIAS_BeforeShow

//Custom Code @14-2A29BDB7
// -------------------------
	global $Tpl;
 	$db = new clsDBOracle_1();
 	
	$nbanco = CCDLookUp("nombre_banco","VST_ingresa_bnc_bancos","rut_banco = ".$PI_TRANSFERENCIAS->Hidden1->GetValue(),$db);
	$Tpl->setvar("nombre_banco",$nbanco);

// -------------------------
//End Custom Code

//Close PI_TRANSFERENCIAS_BeforeShow @2-AB6F019B
    return $PI_TRANSFERENCIAS_BeforeShow;
}
//End Close PI_TRANSFERENCIAS_BeforeShow

//PI_TRANSFERENCIAS_OnValidate @2-BEAD796A
function PI_TRANSFERENCIAS_OnValidate(& $sender)
{
    $PI_TRANSFERENCIAS_OnValidate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_TRANSFERENCIAS; //Compatibility
//End PI_TRANSFERENCIAS_OnValidate

//Custom Code @16-2A29BDB7
// -------------------------
	if($PI_TRANSFERENCIAS->ESTADO_COD->GetValue() == 4 && trim($PI_TRANSFERENCIAS->COMENTARIO->GetValue()) == '' )
		$PI_TRANSFERENCIAS->Errors->addError("El comentario es obligatorio en caso de rechazo.");
	if(strlen($PI_TRANSFERENCIAS->COMENTARIO->GetValue())> 200)
		$PI_TRANSFERENCIAS->Errors->addError("El comentario tiene un máximo de 200 caracteres.");
// -------------------------
//End Custom Code

//Close PI_TRANSFERENCIAS_OnValidate @2-94946512
    return $PI_TRANSFERENCIAS_OnValidate;
}
//End Close PI_TRANSFERENCIAS_OnValidate


?>
