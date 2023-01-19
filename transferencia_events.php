 <?php
//BindEvents Method @1-5687964C
function BindEvents()
{
    global $PI_TRANSFERENCIAS;
    $PI_TRANSFERENCIAS->CCSEvents["OnValidate"] = "PI_TRANSFERENCIAS_OnValidate";
	$PI_TRANSFERENCIAS->CCSEvents["AfterUpdate"] = "PI_TRANSFERENCIAS_AfterUpdate";
}
//End BindEvents Method

//DEL  // -------------------------
//DEL      $db = new clsDBOracle_1();
//DEL  
//DEL  	$monto_total = $EditarTransferencia->monto_total->GetValue();
//DEL  
//DEL  	if($monto_total)
//DEL  	{
//DEL  		$suma = CCDLookUp("sum(montocredito)","pi_cargas","transferencia_cod = ".CCGetParam("TRANSFERENCIA_COD",""),$db);
//DEL  		if($suma != $monto_total)
//DEL  			$EditarTransferencia->Errors->addError("El monto total ingresado no corresponde.");
//DEL  
//DEL  	}
//DEL  // -------------------------

//PI_TRANSFERENCIAS_OnValidate @10-BEAD796A
function PI_TRANSFERENCIAS_OnValidate(& $sender)
{
    $PI_TRANSFERENCIAS_OnValidate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_TRANSFERENCIAS; //Compatibility
//End PI_TRANSFERENCIAS_OnValidate

//Custom Code @17-2A29BDB7
// -------------------------
     $db = new clsDBOracle_1();

	$monto_total = $PI_TRANSFERENCIAS->MONTO_TOTAL->GetValue();

	$tipo = CCDLookUp("max(tipo_cod)","pi_cargas","transferencia_cod = ".CCGetParam("TRANSFERENCIA_COD",""),$db);
	$fecha = $PI_TRANSFERENCIAS->FECHA_PAGO->GetValue();
	if($fecha == null)
		$PI_TRANSFERENCIAS->Errors->addError("El campo Fecha de Pago es necesario.");
	if($monto_total && $tipo < 10)
	{
		$suma = CCDLookUp("sum(montoapagar + montootracta)","pi_cargas","transferencia_cod = ".CCGetParam("TRANSFERENCIA_COD",""),$db);
		if($suma != $monto_total)
			$PI_TRANSFERENCIAS->Errors->addError("El monto total ingresado no corresponde.");

	}
// -------------------------
//End Custom Code

//Close PI_TRANSFERENCIAS_OnValidate @10-94946512
    return $PI_TRANSFERENCIAS_OnValidate;
}
//End Close PI_TRANSFERENCIAS_OnValidate
//PI_TRANSFERENCIAS_AfterUpdate @7-99A59964
function PI_TRANSFERENCIAS_AfterUpdate(& $sender)
{
    $PI_TRANSFERENCIAS_AfterUpdate = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $PI_TRANSFERENCIAS; //Compatibility
//End PI_TRANSFERENCIAS_AfterUpdate

//Custom Code @205-2A29BDB7
// -------------------------
	$db = new clsDBOracle_1();
	
	$fecha = $PI_TRANSFERENCIAS->FECHA_PAGO->GetValue();
	$fecha_string = $fecha[1]."/".$fecha[2]."/".$fecha[3];

	$transferencia_cod = CCGetParam("TRANSFERENCIA_COD","");
	$sql = "update pi_cargas set fechapago = '".$fecha_string."' where transferencia_cod=".$transferencia_cod;
	$db->query($sql);
	
	$sql = "update pi_transferencias set fecha_comp = systimestamp where transferencia_cod=".$transferencia_cod;
	$db->query($sql);

// -------------------------
//End Custom Code

//Close PI_TRANSFERENCIAS_AfterUpdate @7-7EF037F5
    return $PI_TRANSFERENCIAS_AfterUpdate;
}
//End Close PI_TRANSFERENCIAS_AfterUpdate


?>
