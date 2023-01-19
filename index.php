<?php
//Include Common Files @1-A095D95E
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "index.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridVST_PI_TRANSFERENCIAS { //VST_PI_TRANSFERENCIAS class @2-46973089

//Variables @2-59A23B0E

    // Public variables
    var $ComponentType = "Grid";
    var $ComponentName;
    var $Visible;
    var $Errors;
    var $ErrorBlock;
    var $ds;
    var $DataSource;
    var $PageSize;
    var $IsEmpty;
    var $ForceIteration = false;
    var $HasRecord = false;
    var $SorterName = "";
    var $SorterDirection = "";
    var $PageNumber;
    var $RowNumber;
    var $ControlsVisible = array();

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";
    var $Attributes;

    // Grid Controls
    var $StaticControls;
    var $RowControls;
    var $Sorter_TRANSFERENCIA_COD;
    var $Sorter_IEST_NOMBRE_IES;
    var $Sorter_NOMBRE_BANCO;
    var $Sorter_FECHA;
	var $Sorter_FECHA_COMP;
    var $Sorter_N_REG;
    var $Sorter_MONTO_TOTAL;
	var $Sorter_TIPO_DESC;					   
    var $Sorter_ESTADO_DESC;
//End Variables

//Class_Initialize Event @2-687AE940
    function clsGridVST_PI_TRANSFERENCIAS($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "VST_PI_TRANSFERENCIAS";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid VST_PI_TRANSFERENCIAS";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsVST_PI_TRANSFERENCIASDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 20;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("VST_PI_TRANSFERENCIASOrder", "");
        $this->SorterDirection = CCGetParam("VST_PI_TRANSFERENCIASDir", "");

        $this->TRANSFERENCIA_COD = & new clsControl(ccsLabel, "TRANSFERENCIA_COD", "TRANSFERENCIA_COD", ccsFloat, "", CCGetRequestParam("TRANSFERENCIA_COD", ccsGet, NULL), $this);
        $this->IEST_NOMBRE_IES = & new clsControl(ccsLabel, "IEST_NOMBRE_IES", "IEST_NOMBRE_IES", ccsText, "", CCGetRequestParam("IEST_NOMBRE_IES", ccsGet, NULL), $this);
        $this->NOMBRE_BANCO = & new clsControl(ccsLabel, "NOMBRE_BANCO", "NOMBRE_BANCO", ccsText, "", CCGetRequestParam("NOMBRE_BANCO", ccsGet, NULL), $this);
        $this->FECHA = & new clsControl(ccsLabel, "FECHA", "FECHA", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("FECHA", ccsGet, NULL), $this);
		$this->FECHA_COMP = & new clsControl(ccsLabel, "FECHA_COMP", "FECHA_COMP", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("FECHA_COMP", ccsGet, NULL), $this);

		$this->TIPO_DESC = & new clsControl(ccsLabel, "TIPO_DESC", "TIPO_DESC", ccsText, "", CCGetRequestParam("TIPO_DESC", ccsGet, NULL), $this);
		$this->N_REG = & new clsControl(ccsLabel, "N_REG", "N_REG", ccsFloat, "", CCGetRequestParam("N_REG", ccsGet, NULL), $this);
        $this->MONTO_TOTAL = & new clsControl(ccsLabel, "MONTO_TOTAL", "MONTO_TOTAL", ccsInteger, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("MONTO_TOTAL", ccsGet, NULL), $this);
        $this->ESTADO_DESC = & new clsControl(ccsLabel, "ESTADO_DESC", "ESTADO_DESC", ccsText, "", CCGetRequestParam("ESTADO_DESC", ccsGet, NULL), $this);
        $this->Link1 = & new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet, NULL), $this);
        $this->Link1->HTML = true;
        $this->Link1->Page = "transferencia.php";
        $this->h_estado_cod = & new clsControl(ccsHidden, "h_estado_cod", "h_estado_cod", ccsInteger, "", CCGetRequestParam("h_estado_cod", ccsGet, NULL), $this);
        $this->Link2 = & new clsControl(ccsLink, "Link2", "Link2", ccsText, "", CCGetRequestParam("Link2", ccsGet, NULL), $this);
        $this->Link2->Page = "aceptar_transf.php";
		$this->Link3 = & new clsControl(ccsLink, "Link3", "Link3", ccsText, "", CCGetRequestParam("Link3", ccsGet, NULL), $this);
        $this->Link3->Page = "eliminar_transf.php";
        $this->h_comprobante = & new clsControl(ccsHidden, "h_comprobante", "h_comprobante", ccsText, "", CCGetRequestParam("h_comprobante", ccsGet, NULL), $this);
        $this->h_comentario = & new clsControl(ccsHidden, "h_comentario", "h_comentario", ccsText, "", CCGetRequestParam("h_comentario", ccsGet, NULL), $this);
        $this->Sorter_TRANSFERENCIA_COD = & new clsSorter($this->ComponentName, "Sorter_TRANSFERENCIA_COD", $FileName, $this);
        $this->Sorter_IEST_NOMBRE_IES = & new clsSorter($this->ComponentName, "Sorter_IEST_NOMBRE_IES", $FileName, $this);
        $this->Sorter_NOMBRE_BANCO = & new clsSorter($this->ComponentName, "Sorter_NOMBRE_BANCO", $FileName, $this);
        $this->Sorter_FECHA = & new clsSorter($this->ComponentName, "Sorter_FECHA", $FileName, $this);
		$this->Sorter_FECHA_COMP = & new clsSorter($this->ComponentName, "Sorter_FECHA_COMP", $FileName, $this);

        $this->Sorter_N_REG = & new clsSorter($this->ComponentName, "Sorter_N_REG", $FileName, $this);
		$this->Sorter_TIPO_DESC = & new clsSorter($this->ComponentName, "Sorter_TIPO_DESC", $FileName, $this);
        $this->Sorter_MONTO_TOTAL = & new clsSorter($this->ComponentName, "Sorter_MONTO_TOTAL", $FileName, $this);
        $this->Sorter_ESTADO_DESC = & new clsSorter($this->ComponentName, "Sorter_ESTADO_DESC", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @2-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @2-B944ACC0
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["sesBANCN_COD"] = CCGetSession("BANCN_COD", NULL);
        $this->DataSource->Parameters["urls_FECHA"] = CCGetFromGet("s_FECHA", NULL);
        $this->DataSource->Parameters["sesIESN_COD"] = CCGetSession("IESN_COD", NULL);
        $this->DataSource->Parameters["sesTIESN_COD"] = CCGetSession("TIESN_COD", NULL);
        $this->DataSource->Parameters["sesESTADOT"] = CCGetSession("ESTADOT", NULL);
        $this->DataSource->Parameters["urls_BANCN_COD"] = CCGetFromGet("s_BANCN_COD", NULL);
        $this->DataSource->Parameters["urls_ESTADO_COD"] = CCGetFromGet("s_ESTADO_COD", NULL);
		$this->DataSource->Parameters["urls_ESTADO_COMP"] = CCGetFromGet("s_ESTADO_COMP", NULL);
        $this->DataSource->Parameters["urls_IES"] = CCGetFromGet("s_IES", NULL);
        $this->DataSource->Parameters["urls_FECHA_D"] = CCGetFromGet("s_FECHA_D", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["TRANSFERENCIA_COD"] = $this->TRANSFERENCIA_COD->Visible;
            $this->ControlsVisible["IEST_NOMBRE_IES"] = $this->IEST_NOMBRE_IES->Visible;
            $this->ControlsVisible["NOMBRE_BANCO"] = $this->NOMBRE_BANCO->Visible;
            $this->ControlsVisible["FECHA"] = $this->FECHA->Visible;
			$this->ControlsVisible["FECHA_COMP"] = $this->FECHA->Visible;
            $this->ControlsVisible["N_REG"] = $this->N_REG->Visible;
			$this->ControlsVisible["TIPO_DESC"] = $this->TIPO_DESC->Visible;
            $this->ControlsVisible["MONTO_TOTAL"] = $this->MONTO_TOTAL->Visible;
            $this->ControlsVisible["ESTADO_DESC"] = $this->ESTADO_DESC->Visible;
            $this->ControlsVisible["Link1"] = $this->Link1->Visible;
            $this->ControlsVisible["h_estado_cod"] = $this->h_estado_cod->Visible;
            $this->ControlsVisible["Link2"] = $this->Link2->Visible;
			$this->ControlsVisible["Link3"] = $this->Link3->Visible;
            $this->ControlsVisible["h_comprobante"] = $this->h_comprobante->Visible;
            $this->ControlsVisible["h_comentario"] = $this->h_comentario->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->TRANSFERENCIA_COD->SetValue($this->DataSource->TRANSFERENCIA_COD->GetValue());
                $this->IEST_NOMBRE_IES->SetValue($this->DataSource->IEST_NOMBRE_IES->GetValue());
                $this->NOMBRE_BANCO->SetValue($this->DataSource->NOMBRE_BANCO->GetValue());
                $this->FECHA->SetValue($this->DataSource->FECHA->GetValue());
				$this->FECHA_COMP->SetValue($this->DataSource->FECHA_COMP->GetValue());
                $this->N_REG->SetValue($this->DataSource->N_REG->GetValue());
				$this->TIPO_DESC->SetValue($this->DataSource->TIPO_DESC->GetValue());
                $this->MONTO_TOTAL->SetValue($this->DataSource->MONTO_TOTAL->GetValue());
                $this->ESTADO_DESC->SetValue($this->DataSource->ESTADO_DESC->GetValue());
                $this->Link1->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "TRANSFERENCIA_COD", $this->DataSource->f("TRANSFERENCIA_COD"));
                $this->h_estado_cod->SetValue($this->DataSource->h_estado_cod->GetValue());
                $this->Link2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "TRANSFERENCIA_COD", $this->DataSource->f("TRANSFERENCIA_COD"));
				$this->Link3->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Link3->Parameters = CCAddParam($this->Link3->Parameters, "TRANSFERENCIA_COD", $this->DataSource->f("TRANSFERENCIA_COD"));
                $this->h_comprobante->SetValue($this->DataSource->h_comprobante->GetValue());
                $this->h_comentario->SetValue($this->DataSource->h_comentario->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->TRANSFERENCIA_COD->Show();
                $this->IEST_NOMBRE_IES->Show();
                $this->NOMBRE_BANCO->Show();
                $this->FECHA->Show();
				$this->FECHA_COMP->Show();
                $this->N_REG->Show();
				$this->TIPO_DESC->Show();
                $this->MONTO_TOTAL->Show();
                $this->ESTADO_DESC->Show();
                $this->Link1->Show();
                $this->h_estado_cod->Show();
                $this->Link2->Show();
				$this->Link3->Show();
                $this->h_comprobante->Show();
                $this->h_comentario->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $this->Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->Navigator->TotalPages = $this->DataSource->PageCount();
        if ($this->Navigator->TotalPages <= 1) {
            $this->Navigator->Visible = false;
        }
        $this->Sorter_TRANSFERENCIA_COD->Show();
        $this->Sorter_IEST_NOMBRE_IES->Show();
        $this->Sorter_NOMBRE_BANCO->Show();
        $this->Sorter_FECHA->Show();
		$this->Sorter_FECHA_COMP->Show();
        $this->Sorter_N_REG->Show();
		$this->Sorter_TIPO_DESC->Show();
        $this->Sorter_MONTO_TOTAL->Show();
        $this->Sorter_ESTADO_DESC->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-1E9D35E6
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->TRANSFERENCIA_COD->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IEST_NOMBRE_IES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->NOMBRE_BANCO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->FECHA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->N_REG->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTO_TOTAL->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ESTADO_DESC->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->h_estado_cod->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link2->Errors->ToString());
		$errors = ComposeStrings($errors, $this->Link3->Errors->ToString());
        $errors = ComposeStrings($errors, $this->h_comprobante->Errors->ToString());
        $errors = ComposeStrings($errors, $this->h_comentario->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End VST_PI_TRANSFERENCIAS Class @2-FCB6E20C

class clsVST_PI_TRANSFERENCIASDataSource extends clsDBOracle_1 {  //VST_PI_TRANSFERENCIASDataSource Class @2-93748064

//DataSource Variables @2-E7AC660A
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $TRANSFERENCIA_COD;
    var $IEST_NOMBRE_IES;
    var $NOMBRE_BANCO;
    var $FECHA;
	var $FECHA_COMP;
    var $N_REG;
	var $TIPO_DESC;
    var $MONTO_TOTAL;
    var $ESTADO_DESC;
    var $h_estado_cod;
    var $h_comprobante;
    var $h_comentario;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-286FDAA0
    function clsVST_PI_TRANSFERENCIASDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid VST_PI_TRANSFERENCIAS";
        $this->Initialize();
        $this->TRANSFERENCIA_COD = new clsField("TRANSFERENCIA_COD", ccsFloat, "");
        
        $this->IEST_NOMBRE_IES = new clsField("IEST_NOMBRE_IES", ccsText, "");
        
        $this->NOMBRE_BANCO = new clsField("NOMBRE_BANCO", ccsText, "");
        
        $this->FECHA = new clsField("FECHA", ccsDate, $this->DateFormat);
		$this->FECHA_COMP = new clsField("FECHA_COMP", ccsDate, $this->DateFormat);
		$this->TIPO_DESC = new clsField("TIPO_DESC", ccsText, "");
        
        $this->N_REG = new clsField("N_REG", ccsFloat, "");
        
        $this->MONTO_TOTAL = new clsField("MONTO_TOTAL", ccsInteger, "");
        
        $this->ESTADO_DESC = new clsField("ESTADO_DESC", ccsText, "");
        
        $this->h_estado_cod = new clsField("h_estado_cod", ccsInteger, "");
        
        $this->h_comprobante = new clsField("h_comprobante", ccsText, "");
        
        $this->h_comentario = new clsField("h_comentario", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-05ED09F6
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "TRANSFERENCIA_COD desc";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_TRANSFERENCIA_COD" => array("TRANSFERENCIA_COD", ""), 
            "Sorter_IEST_NOMBRE_IES" => array("IEST_NOMBRE_IES", ""), 
            "Sorter_NOMBRE_BANCO" => array("NOMBRE_BANCO", ""), 
            "Sorter_FECHA" => array("FECHA", ""), 
			"Sorter_FECHA_COMP" => array("FECHA_COMP", ""), 
            "Sorter_N_REG" => array("N_REG", ""), 
			"Sorter_TIPO_DESC" => array("TIPO_DESC", ""), 
            "Sorter_MONTO_TOTAL" => array("MONTO_TOTAL", ""), 
            "Sorter_ESTADO_DESC" => array("ESTADO_DESC", "")));
    }
//End SetOrder Method

//Prepare Method @2-7D619397
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "sesBANCN_COD", ccsFloat, "", "", $this->Parameters["sesBANCN_COD"], "", false);
        $this->wp->AddParameter("2", "urls_FECHA", ccsDate, array("dd", "/", "mm", "/", "yyyy"), $this->DateFormat, $this->Parameters["urls_FECHA"], "", false);
        $this->wp->AddParameter("3", "sesIESN_COD", ccsInteger, "", "", $this->Parameters["sesIESN_COD"], "", false);
        $this->wp->AddParameter("4", "sesTIESN_COD", ccsText, "", "", $this->Parameters["sesTIESN_COD"], "", false);
        $this->wp->AddParameter("5", "sesESTADOT", ccsFloat, "", "", $this->Parameters["sesESTADOT"], "", false);
        $this->wp->AddParameter("6", "urls_BANCN_COD", ccsFloat, "", "", $this->Parameters["urls_BANCN_COD"], "", false);
        $this->wp->AddParameter("7", "urls_ESTADO_COD", ccsFloat, "", "", $this->Parameters["urls_ESTADO_COD"], "", false);
        $this->wp->AddParameter("8", "urls_IES", ccsText, "", "", $this->Parameters["urls_IES"], "", false);
        $this->wp->AddParameter("9", "urls_FECHA_D", ccsDate, array("dd", "/", "mm", "/", "yyyy"), $this->DateFormat, $this->Parameters["urls_FECHA_D"], "", false);
		$this->wp->AddParameter("10", "urls_ESTADO_COMP", ccsFloat, "", "", $this->Parameters["urls_ESTADO_COMP"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "BANCN_COD", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opLessThanOrEqual, "FECHA", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsDate),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "IESN_COD", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "TIESN_COD", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsText),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opNotEqual, "ESTADO_COD", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsFloat),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "BANCN_COD", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsFloat),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "ESTADO_COD", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsFloat),false);
        $this->wp->Criterion[8] = $this->wp->Operation(opEqual, "IESN", $this->wp->GetDBValue("8"), $this->ToSQL($this->wp->GetDBValue("8"), ccsText),false);
        $this->wp->Criterion[9] = $this->wp->Operation(opGreaterThanOrEqual, "FECHA", $this->wp->GetDBValue("9"), $this->ToSQL($this->wp->GetDBValue("9"), ccsDate),false);
		$this->wp->Criterion[10] = $this->wp->Operation(opEqual, "ESTADO_COMP", $this->wp->GetDBValue("10"), $this->ToSQL($this->wp->GetDBValue("10"), ccsFloat),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
			 false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]), 
             $this->wp->Criterion[6]), 
             $this->wp->Criterion[7]), 
             $this->wp->Criterion[8]), 
			 $this->wp->Criterion[9]), 
             $this->wp->Criterion[10]);
    }
//End Prepare Method

//Open Method @2-154F1CF4
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM VST_PI_TRANSFERENCIAS";
        $this->SQL = "SELECT * \n\n" .
        "FROM VST_PI_TRANSFERENCIAS {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @2-9C3B0191
    function SetValues()
    {
        $this->TRANSFERENCIA_COD->SetDBValue(trim($this->f("TRANSFERENCIA_COD")));
        $this->IEST_NOMBRE_IES->SetDBValue($this->f("IEST_NOMBRE_IES"));
        $this->NOMBRE_BANCO->SetDBValue($this->f("NOMBRE_BANCO"));
        $this->FECHA->SetDBValue(trim($this->f("FECHA")));
		$this->FECHA_COMP->SetDBValue(trim($this->f("FECHA_COMP")));
		$this->TIPO_DESC->SetDBValue($this->f("TIPO_DESC"));
        $this->N_REG->SetDBValue(trim($this->f("N_REG")));
        $this->MONTO_TOTAL->SetDBValue(trim($this->f("MONTO_TOTAL")));
        $this->ESTADO_DESC->SetDBValue($this->f("ESTADO_DESC"));
        $this->h_estado_cod->SetDBValue(trim($this->f("ESTADO_COD")));
        $this->h_comprobante->SetDBValue($this->f("COMPROBANTE"));
        $this->h_comentario->SetDBValue($this->f("COMENTARIO"));
    }
//End SetValues Method

} //End VST_PI_TRANSFERENCIASDataSource Class @2-FCB6E20C

class clsRecordVST_PI_TRANSFERENCIAS1 { //VST_PI_TRANSFERENCIAS1 Class @25-DF92335E

//Variables @25-D6FF3E86

    // Public variables
    var $ComponentType = "Record";
    var $ComponentName;
    var $Parent;
    var $HTMLFormAction;
    var $PressedButton;
    var $Errors;
    var $ErrorBlock;
    var $FormSubmitted;
    var $FormEnctype;
    var $Visible;
    var $IsEmpty;

    var $CCSEvents = "";
    var $CCSEventResult;

    var $RelativePath = "";

    var $InsertAllowed = false;
    var $UpdateAllowed = false;
    var $DeleteAllowed = false;
    var $ReadAllowed   = false;
    var $EditMode      = false;
    var $ds;
    var $DataSource;
    var $ValidatingControls;
    var $Controls;
    var $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @25-8791DDDA
    function clsRecordVST_PI_TRANSFERENCIAS1($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record VST_PI_TRANSFERENCIAS1/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "VST_PI_TRANSFERENCIAS1";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_FECHA = & new clsControl(ccsTextBox, "s_FECHA", "Fecha Carga", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("s_FECHA", $Method, NULL), $this);
            $this->DatePicker_s_FECHA1 = & new clsDatePicker("DatePicker_s_FECHA1", "VST_PI_TRANSFERENCIAS1", "s_FECHA", $this);
            $this->s_BANCN_COD = & new clsControl(ccsListBox, "s_BANCN_COD", "Banco", ccsText, "", CCGetRequestParam("s_BANCN_COD", $Method, NULL), $this);
            $this->s_BANCN_COD->DSType = dsSQL;
            $this->s_BANCN_COD->DataSource = new clsDBOracle_1();
            $this->s_BANCN_COD->ds = & $this->s_BANCN_COD->DataSource;
            list($this->s_BANCN_COD->BoundColumn, $this->s_BANCN_COD->TextColumn, $this->s_BANCN_COD->DBFormat) = array("BANCN_COD", "NOMBRE_BANCO", "");
            $this->s_BANCN_COD->DataSource->SQL = "SELECT * \n" .
            "FROM VST_SGI_BNC_BANCOS  where bancn_cod <> 6";
            $this->s_BANCN_COD->DataSource->Order = "";
            $this->s_IES = & new clsControl(ccsListBox, "s_IES", "IES", ccsText, "", CCGetRequestParam("s_IES", $Method, NULL), $this);
            $this->s_IES->DSType = dsSQL;
            $this->s_IES->DataSource = new clsDBOracle_1();
            $this->s_IES->ds = & $this->s_IES->DataSource;
            list($this->s_IES->BoundColumn, $this->s_IES->TextColumn, $this->s_IES->DBFormat) = array("IESN_COD||'_'||TIESN_COD", "IEST_NOMBRE_IES", "");
            $this->s_IES->DataSource->SQL = "SELECT iesn_cod||'_'||tiesn_cod,iest_nombre_ies\n" .
            "FROM VST_SGI_OA_IES {SQL_OrderBy}";
            $this->s_IES->DataSource->Order = "iest_nombre_ies asc";
            $this->s_ESTADO_COD = & new clsControl(ccsListBox, "s_ESTADO_COD", "Estado", ccsText, "", CCGetRequestParam("s_ESTADO_COD", $Method, NULL), $this);
            $this->s_ESTADO_COD->DSType = dsTable;
            $this->s_ESTADO_COD->DataSource = new clsDBOracle_1();
            $this->s_ESTADO_COD->ds = & $this->s_ESTADO_COD->DataSource;
            $this->s_ESTADO_COD->DataSource->SQL = "SELECT * \n" .
"FROM PI_PRM_ESTADO {SQL_Where} {SQL_OrderBy}";
            list($this->s_ESTADO_COD->BoundColumn, $this->s_ESTADO_COD->TextColumn, $this->s_ESTADO_COD->DBFormat) = array("ESTADO_COD", "ESTADO_DESC", "");
			
			$this->s_ESTADO_COMP = & new clsControl(ccsListBox, "s_ESTADO_COMP", "Estado Comp", ccsText, "", CCGetRequestParam("s_ESTADO_COMP", $Method, NULL), $this);
            $this->s_ESTADO_COMP->DSType = dsTable;
            $this->s_ESTADO_COMP->DataSource = new clsDBOracle_1();
            $this->s_ESTADO_COMP->ds = & $this->s_ESTADO_COMP->DataSource;
            $this->s_ESTADO_COMP->DataSource->SQL = "SELECT 1 as ESTADO_COMP,'Sin Comprobante' as ESTADO_DESC from Dual union all select 2,'Comprobante Cargado' from dual";
            list($this->s_ESTADO_COMP->BoundColumn, $this->s_ESTADO_COMP->TextColumn, $this->s_ESTADO_COMP->DBFormat) = array("ESTADO_COMP", "ESTADO_DESC", "");
			
            $this->s_FECHA_D = & new clsControl(ccsTextBox, "s_FECHA_D", "Fecha Carga", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("s_FECHA_D", $Method, NULL), $this);
            $this->DatePicker_s_FECHA_D1 = & new clsDatePicker("DatePicker_s_FECHA_D1", "VST_PI_TRANSFERENCIAS1", "s_FECHA_D", $this);
        }
    }
//End Class_Initialize Event

//Validate Method @25-29665D73
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_FECHA->Validate() && $Validation);
        $Validation = ($this->s_BANCN_COD->Validate() && $Validation);
        $Validation = ($this->s_IES->Validate() && $Validation);
        $Validation = ($this->s_ESTADO_COD->Validate() && $Validation);
		$Validation = ($this->s_ESTADO_COMP->Validate() && $Validation);
        $Validation = ($this->s_FECHA_D->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_FECHA->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_BANCN_COD->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IES->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_ESTADO_COD->Errors->Count() == 0);
		$Validation =  $Validation && ($this->s_ESTADO_COMP->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_FECHA_D->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @25-A1346ACA
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_FECHA->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_FECHA1->Errors->Count());
        $errors = ($errors || $this->s_BANCN_COD->Errors->Count());
        $errors = ($errors || $this->s_IES->Errors->Count());
        $errors = ($errors || $this->s_ESTADO_COD->Errors->Count());
		$errors = ($errors || $this->s_ESTADO_COMP->Errors->Count());
        $errors = ($errors || $this->s_FECHA_D->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_FECHA_D1->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @25-ED598703
function SetPrimaryKeys($keyArray)
{
    $this->PrimaryKeys = $keyArray;
}
function GetPrimaryKeys()
{
    return $this->PrimaryKeys;
}
function GetPrimaryKey($keyName)
{
    return $this->PrimaryKeys[$keyName];
}
//End MasterDetail

//Operation Method @25-670B96B7
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_DoSearch";
            if($this->Button_DoSearch->Pressed) {
                $this->PressedButton = "Button_DoSearch";
            }
        }
        $Redirect = "index.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "index.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @25-64C0E260
    function Show()
    {
        global $CCSUseAmp;
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->s_BANCN_COD->Prepare();
        $this->s_IES->Prepare();
        $this->s_ESTADO_COD->Prepare();
		$this->s_ESTADO_COMP->Prepare();


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_FECHA->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_s_FECHA1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_BANCN_COD->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_IES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_ESTADO_COD->Errors->ToString());
			$Error = ComposeStrings($Error, $this->s_ESTADO_COMP->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_FECHA_D->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_s_FECHA_D1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_DoSearch->Show();
        $this->s_FECHA->Show();
        $this->DatePicker_s_FECHA1->Show();
        $this->s_BANCN_COD->Show();
        $this->s_IES->Show();
        $this->s_ESTADO_COD->Show();
		$this->s_ESTADO_COMP->Show();
        $this->s_FECHA_D->Show();
        $this->DatePicker_s_FECHA_D1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End VST_PI_TRANSFERENCIAS1 Class @25-FCB6E20C

//Initialize Page @1-EBE5C78D
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "index.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Authenticate User @1-5C03D13B
CCSecurityRedirect("A;B;T;C;D;H;K;S;R;U", "");
//End Authenticate User

//Include events file @1-B7D86394
include_once("./index_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-CDA749AB
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$VST_PI_TRANSFERENCIAS = & new clsGridVST_PI_TRANSFERENCIAS("", $MainPage);
$VST_PI_TRANSFERENCIAS1 = & new clsRecordVST_PI_TRANSFERENCIAS1("", $MainPage);
$MainPage->VST_PI_TRANSFERENCIAS = & $VST_PI_TRANSFERENCIAS;
$MainPage->VST_PI_TRANSFERENCIAS1 = & $VST_PI_TRANSFERENCIAS1;
$VST_PI_TRANSFERENCIAS->Initialize();

BindEvents();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-5D56EDDA
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "Windows-1252", "replace");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "../");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-E5F367E8
$VST_PI_TRANSFERENCIAS1->Operation();
//End Execute Components

//Go to destination page @1-9A625E1D
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($VST_PI_TRANSFERENCIAS);
    unset($VST_PI_TRANSFERENCIAS1);
    unset($Tpl);
    exit;
}
//End Go to destination page
//	$db = new clsDBOracle_1();
//	$db->query("ALTER SESSION SET NLS_DATE_FORMAT = 'dd/mm/yyyy'"); //AGREGADO PARA FORMATEAR LAS FECHAS
//Show Page @1-BCE73485
$VST_PI_TRANSFERENCIAS->Show();
$VST_PI_TRANSFERENCIAS1->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-02641FA8
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($VST_PI_TRANSFERENCIAS);
unset($VST_PI_TRANSFERENCIAS1);
unset($Tpl);
//End Unload Page


?>
