<?php
//BindEvents Method @1-2E7F2291
function BindEvents()
{
    global $PI_DATOS_CC;
    global $CCSEvents;
    $PI_DATOS_CC->ds->CCSEvents["BeforeBuildInsert"] = "PI_DATOS_CC_ds_BeforeBuildInsert";
    $PI_DATOS_CC->ds->CCSEvents["BeforeExecuteInsert"] = "PI_DATOS_CC_ds_BeforeExecuteInsert";
    $PI_DATOS_CC->CCSEvents["BeforeSelect"] = "PI_DATOS_CC_BeforeSelect";
    $PI_DATOS_CC->CCSEvents["BeforeInsert"] = "PI_DATOS_CC_BeforeInsert";
    $PI_DATOS_CC->CCSEvents["BeforeShow"] = "PI_DATOS_CC_BeforeShow";
    $PI_DATOS_CC->CCSEvents["OnValidate"] = "PI_DATOS_CC_OnValidate";
}
//End BindEvents Method

//PI_DATOS_CC_ds_BeforeBuildInsert @2-E001F01B
function PI_DATOS_CC_ds_BeforeBuildInsert(& $sender)
{
    $PI_DATOS_CC_ds_BeforeBuildInsert = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_ds_BeforeBuildInsert

//Custom Code @13-2A29BDB7
// -------------------------
 /*   $db = new clsDBOracle_1();
	$seq = CCDLookUp("SEQ_PI_DATOS_CC.nextval","dual","",$db);
    $PI_DATOS_CC->h_datos_cc_cod->SetValue($seq);*/
// -------------------------
//End Custom Code

//Close PI_DATOS_CC_ds_BeforeBuildInsert @2-7686E6B0
    return $PI_DATOS_CC_ds_BeforeBuildInsert;
}
//End Close PI_DATOS_CC_ds_BeforeBuildInsert

//PI_DATOS_CC_ds_BeforeExecuteInsert @2-F4737033
function PI_DATOS_CC_ds_BeforeExecuteInsert(& $sender)
{
    $PI_DATOS_CC_ds_BeforeExecuteInsert = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_ds_BeforeExecuteInsert

//Custom Code @14-2A29BDB7
// -------------------------
    echo "SQL------".$PI_DATOS_CC->ds->SQL."<br>";
	echo "Where------".$PI_DATOS_CC->ds->SQL."<br>";
	//exit;
// -------------------------
//End Custom Code

//Close PI_DATOS_CC_ds_BeforeExecuteInsert @2-239DE040
    return $PI_DATOS_CC_ds_BeforeExecuteInsert;
}
//End Close PI_DATOS_CC_ds_BeforeExecuteInsert

//PI_DATOS_CC_BeforeSelect @2-840EA23E
function PI_DATOS_CC_BeforeSelect(& $sender)
{
    $PI_DATOS_CC_BeforeSelect = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_BeforeSelect

//Custom Code @15-2A29BDB7
// -------------------------
  /*     $db = new clsDBOracle_1();
	$seq = CCDLookUp("SEQ_PI_DATOS_CC.nextval","dual","",$db);
    $PI_DATOS_CC->h_datos_cc_cod->SetValue($seq);*/
// -------------------------
//End Custom Code

//Close PI_DATOS_CC_BeforeSelect @2-CE06F161
    return $PI_DATOS_CC_BeforeSelect;
}
//End Close PI_DATOS_CC_BeforeSelect

//PI_DATOS_CC_BeforeInsert @2-0B5B5355
function PI_DATOS_CC_BeforeInsert(& $sender)
{
    $PI_DATOS_CC_BeforeInsert = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_BeforeInsert

//Custom Code @17-2A29BDB7
// -------------------------
        $db = new clsDBOracle_1();
	$seq = CCDLookUp("SEQ_PI_DATOS_CC.nextval","dual","",$db);
    $PI_DATOS_CC->h_datos_cc_cod->SetValue($seq);
// -------------------------
//End Custom Code

//Close PI_DATOS_CC_BeforeInsert @2-3B5F08CC
    return $PI_DATOS_CC_BeforeInsert;
}
//End Close PI_DATOS_CC_BeforeInsert

//PI_DATOS_CC_BeforeShow @2-18834B45
function PI_DATOS_CC_BeforeShow(& $sender)
{
    $PI_DATOS_CC_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_BeforeShow

//Custom Code @18-2A29BDB7
// -------------------------
    if((CCGetGroupID() == "C" || CCGetGroupID() == "D") )
	{
		global $Tpl;
		
		$db = new clsDBOracle_1();
		$id_user = CCGetUserID();
		$iesn_cod = CCDLookUp("iesn_cod","ingresa_usuarios.usr_usuarios","usuan_cod = ".$id_user ,$db);
		$tiesn_cod = CCDLookUp("tiesn_cod","ingresa_usuarios.usr_usuarios","usuan_cod = ".$id_user ,$db);
		$rut_ies = CCDLookUp("max(iesn_rut)","VST_SGI_OA_IES","iesn_cod = ".$iesn_cod." and tiesn_cod=".$tiesn_cod ,$db);
		$PI_DATOS_CC->RUTIES->SetValue($rut_ies);
		$Tpl->setvar("ies"," readonly ");
		$Tpl->setvar("boton"," style='display: none;' ");
	}



// -------------------------
//End Custom Code

//Close PI_DATOS_CC_BeforeShow @2-536ED776
    return $PI_DATOS_CC_BeforeShow;
}
//End Close PI_DATOS_CC_BeforeShow

//PI_DATOS_CC_OnValidate @2-C505765E
function PI_DATOS_CC_OnValidate(& $sender)
{
    $PI_DATOS_CC_OnValidate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_DATOS_CC; //Compatibility
//End PI_DATOS_CC_OnValidate

//Custom Code @19-2A29BDB7
// -------------------------
    $ruties = $PI_DATOS_CC->RUTIES->GetValue();
	$db = new clsDBOracle_1();
	$comp = CCDLookUp("iesn_rut","VST_SGI_OA_IES","iesn_rut = '".$ruties."'",$db);
	if(!$comp)
		$PI_DATOS_CC->Errors->addError("El rut ingresado no existe en el sistema.");

	$allowed = array("-");

 	 if ( !ctype_alnum( str_replace($allowed, '', $PI_DATOS_CC->N_CUENTA->GetValue() ) ) ) {
    
   		 $PI_DATOS_CC->Errors->addError("El número de cuenta no es válido.");
   
 	 }
// -------------------------
//End Custom Code

//Close PI_DATOS_CC_OnValidate @2-6C95B3FF
    return $PI_DATOS_CC_OnValidate;
}
//End Close PI_DATOS_CC_OnValidate

//Page_BeforeInitialize @1-362718DF
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $datos_cc_record; //Compatibility
//End Page_BeforeInitialize

//Custom Code @16-2A29BDB7
// -------------------------
    if((CCGetGroupID() == "C" || CCGetGroupID() == "D") )
	{
		global $Redirect;
		$id_user = CCGetUserID();
		$db = new clsDBOracle_1();
		$iesn_cod = CCDLookUp("iesn_cod","ingresa_usuarios.usr_usuarios","usuan_cod = ".$id_user ,$db);
		$tiesn_cod = CCDLookUp("tiesn_cod","ingresa_usuarios.usr_usuarios","usuan_cod = ".$id_user ,$db);
		$rut_ies = CCDLookUp("max(iesn_rut)","VST_SGI_OA_IES","iesn_cod = ".$iesn_cod." and tiesn_cod=".$tiesn_cod ,$db);
		$cc_cod = CCDLookUp("max(datos_cc_cod)","pi_datos_cc","ruties = ".$rut_ies ,$db);
		//echo $rut_ies;exit;
		if( CCGetParam("red","") != 1)
		{
			if($cc_cod)
			{
				$Redirect = "datos_cc_record.php?DATOS_CC_COD=".$cc_cod."&red=1";
			}
			else
				$Redirect = "datos_cc_record.php?red=1";
		}
	}
// -------------------------
//End Custom Code

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
