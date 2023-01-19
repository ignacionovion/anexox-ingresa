<?php
//BindEvents Method @1-940FAF08
function BindEvents()
{
    global $VST_PI_TRANSFERENCIAS;
    global $CCSEvents;
    $VST_PI_TRANSFERENCIAS->CCSEvents["BeforeShowRow"] = "VST_PI_TRANSFERENCIAS_BeforeShowRow";
    $VST_PI_TRANSFERENCIAS->CCSEvents["BeforeShow"] = "VST_PI_TRANSFERENCIAS_BeforeShow";
}
//End BindEvents Method

//VST_PI_TRANSFERENCIAS_BeforeShowRow @2-E6215548
function VST_PI_TRANSFERENCIAS_BeforeShowRow(& $sender)
{
    $VST_PI_TRANSFERENCIAS_BeforeShowRow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_TRANSFERENCIAS; //Compatibility
//End VST_PI_TRANSFERENCIAS_BeforeShowRow

//Custom Code @20-2A29BDB7
// -------------------------
	global $Tpl;
	$db = new clsDBOracle_1();
	$eliminado = CCDLookUp("ELIMINADO","PI_TRANSFERENCIAS","TRANSFERENCIA_COD = ".$VST_PI_TRANSFERENCIAS->TRANSFERENCIA_COD->GetValue(),$db);
	$comentario_el = CCDLookUp("COMENTARIO_ELIMINADO","PI_TRANSFERENCIAS","TRANSFERENCIA_COD = ".$VST_PI_TRANSFERENCIAS->TRANSFERENCIA_COD->GetValue(),$db);
		
	$urlnomina = "proxy.php?il=".$VST_PI_TRANSFERENCIAS->TRANSFERENCIA_COD->GetValue();
	$nomina = '<a target="_blank" href="'.$urlnomina.'" ">Nómina</a>&nbsp;';
	$Tpl->setvar("h_nomina", $nomina);
	
	if($VST_PI_TRANSFERENCIAS->h_comprobante->GetValue())
	{
		$comprobante = '<a target="_blank" href="documentos/'.rawurlencode($VST_PI_TRANSFERENCIAS->h_comprobante->GetValue()).'" ">Comprobante</a>&nbsp;';
		$Tpl->setvar("h_comp", $comprobante);
	}
	else
		$Tpl->setvar("h_comp", "");

	if($eliminado == 1)
	{
		$Tpl->setvar("ESTADO_DESCEL", "Eliminado");
		$comentario = '<a href="#" onClick="alert('."'".$comentario_el."'".');return false;">';
		$Tpl->setvar("h_comen1", '>'.$comentario);
		$Tpl->setvar("h_comen2", "</a>");
		$Tpl->setvar("h_comen", "<input type='hidden' ");
	}
	else if($VST_PI_TRANSFERENCIAS->h_estado_cod->GetValue() > 2 && $VST_PI_TRANSFERENCIAS->h_estado_cod->GetValue() != 5)
	{
		$Tpl->setvar("ESTADO_DESCEL", "");
		$comentario = '<a href="#" onClick="alert('."'".$VST_PI_TRANSFERENCIAS->h_comentario->GetValue()."'".');return false;">';
		$Tpl->setvar("h_comen", $comentario);
		$Tpl->setvar("h_comen1", "</a>");
	}
	else
	{
		$Tpl->setvar("ESTADO_DESCEL", "");
		$Tpl->setvar("h_comen", "");
		$Tpl->setvar("h_comen1", "");
	}
		

	/***********************Ocultar opcion enviar**************************/
	if($VST_PI_TRANSFERENCIAS->h_estado_cod->GetValue() != 1 || CCGetGroupID() == "K" || CCGetGroupID() == "A" || $eliminado == 1)
		$Tpl->setvar("link_estado1"," style='display: none;' ");
	else
		$Tpl->setvar("link_estado1","");

	/***********************Ocultar buscador************************/
	if(CCGetGroupID() == "H" || CCGetGroupID() == "S")
	{
		$Tpl->setvar("buscador"," style='display: none;' ");
		$Tpl->setvar("revision"," style='display: none;' ");
		$Tpl->setvar("revision1"," style='display: none;' ");
		
	}
	else
	{
		$Tpl->setvar("buscador","");
		if($eliminado == 1)
		{
			$Tpl->setvar("revision1"," style='display: none;' ");
			$Tpl->setvar("revision"," style='display: none;' ");
		}
		
		
		else if(($VST_PI_TRANSFERENCIAS->h_estado_cod->GetValue() != 2 || CCGetGroupID() == "C" || CCGetGroupID() == "K" || CCGetGroupID() == "A"))
		{
			$Tpl->setvar("revision"," style='display: none;' ");
			$Tpl->setvar("revision1","");
		}
		else
		{
			$Tpl->setvar("revision1","");
			$Tpl->setvar("revision","");
		}

		if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
			$Tpl->setvar("buscadories"," style='display: none;' ");
		else
			$Tpl->setvar("buscadories","");
		

	}



// -------------------------
//End Custom Code

//Close VST_PI_TRANSFERENCIAS_BeforeShowRow @2-C051850F
    return $VST_PI_TRANSFERENCIAS_BeforeShowRow;
}
//End Close VST_PI_TRANSFERENCIAS_BeforeShowRow

//VST_PI_TRANSFERENCIAS_BeforeShow @2-18CFA1B3
function VST_PI_TRANSFERENCIAS_BeforeShow(& $sender)
{
    $VST_PI_TRANSFERENCIAS_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $VST_PI_TRANSFERENCIAS; //Compatibility
//End VST_PI_TRANSFERENCIAS_BeforeShow

//Custom Code @35-2A29BDB7
// -------------------------
 //   echo $VST_PI_TRANSFERENCIAS->ds->Where;
// -------------------------
//End Custom Code

//Close VST_PI_TRANSFERENCIAS_BeforeShow @2-374781A1
    return $VST_PI_TRANSFERENCIAS_BeforeShow;
}
//End Close VST_PI_TRANSFERENCIAS_BeforeShow

//Page_BeforeInitialize @1-E5CCA708
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $index; //Compatibility
//End Page_BeforeInitialize

//Custom Code @22-2A29BDB7
// -------------------------
    $db = new clsDBOracle_1();
		
	if(CCGetGroupID() == "H" || CCGetGroupID() == "S")
	{
    	$bancn_cod = CCDLookUp("BANCN_COD","vst_usr_usuarios2016","USUAN_COD = ".CCGetUserID(),$db);
		CCSetSession("BANCN_COD",$bancn_cod);
	}
	else
		CCSetSession("BANCN_COD",'');
	if(CCGetGroupID() == "C" || CCGetGroupID() == "D")
	{
    	$iesn_cod = CCDLookUp("IESN_COD","vst_usr_usuarios2016","USUAN_COD = ".CCGetUserID(),$db);
		CCSetSession("IESN_COD",$iesn_cod);
		$tiesn_cod = CCDLookUp("TIESN_COD","vst_usr_usuarios2016","USUAN_COD = ".CCGetUserID(),$db);
		CCSetSession("TIESN_COD",$tiesn_cod);
		CCSetSession("ESTADOT",1);
	}
	else
	{
		CCSetSession("IESN_COD",'');
		CCSetSession("TIESN_COD",'');
		CCSetSession("ESTADOT",'');
	}
	if(CCGetGroupID() == "K" || CCGetGroupID() == "A")
	{
    	global $Redirect;
	//	$Redirect = "consulta_rut.php";
	}


// -------------------------
//End Custom Code

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
