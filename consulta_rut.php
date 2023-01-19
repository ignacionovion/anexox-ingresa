<?php
//Include Common Files @1-FA15F2C7
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "consulta_rut.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridVST_PI_REPORTES { //VST_PI_REPORTES class @2-1804D29B

//Variables @2-B8D7A881

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
    var $Sorter_RUT;
    var $Sorter_IEST_NOMBRE_IES;
    var $Sorter_NOMBRE_BANCO;
    var $Sorter_OPERACION;
    var $Sorter_LICITACION;
    var $Sorter_MONTOCREDITO;
    var $Sorter_FECHA;
    var $Sorter_ESTADO_DESC;
    var $Sorter1;
//End Variables

//Class_Initialize Event @2-B99EDE24
    function clsGridVST_PI_REPORTES($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "VST_PI_REPORTES";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid VST_PI_REPORTES";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsVST_PI_REPORTESDataSource($this);
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
        $this->SorterName = CCGetParam("VST_PI_REPORTESOrder", "");
        $this->SorterDirection = CCGetParam("VST_PI_REPORTESDir", "");

        $this->TRANSFERENCIA_COD = & new clsControl(ccsLabel, "TRANSFERENCIA_COD", "TRANSFERENCIA_COD", ccsFloat, "", CCGetRequestParam("TRANSFERENCIA_COD", ccsGet, NULL), $this);
        $this->RUT = & new clsControl(ccsLabel, "RUT", "RUT", ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("RUT", ccsGet, NULL), $this);
        $this->IEST_NOMBRE_IES = & new clsControl(ccsLabel, "IEST_NOMBRE_IES", "IEST_NOMBRE_IES", ccsText, "", CCGetRequestParam("IEST_NOMBRE_IES", ccsGet, NULL), $this);
        $this->NOMBRE_BANCO = & new clsControl(ccsLabel, "NOMBRE_BANCO", "NOMBRE_BANCO", ccsText, "", CCGetRequestParam("NOMBRE_BANCO", ccsGet, NULL), $this);
        $this->OPERACION = & new clsControl(ccsLabel, "OPERACION", "OPERACION", ccsFloat, "", CCGetRequestParam("OPERACION", ccsGet, NULL), $this);
        $this->LICITACION = & new clsControl(ccsLabel, "LICITACION", "LICITACION", ccsFloat, "", CCGetRequestParam("LICITACION", ccsGet, NULL), $this);
        $this->MONTOCREDITO = & new clsControl(ccsLabel, "MONTOCREDITO", "MONTOCREDITO", ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("MONTOCREDITO", ccsGet, NULL), $this);
        $this->FECHA = & new clsControl(ccsLabel, "FECHA", "FECHA", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("FECHA", ccsGet, NULL), $this);
        $this->ESTADO_DESC = & new clsControl(ccsLabel, "ESTADO_DESC", "ESTADO_DESC", ccsText, "", CCGetRequestParam("ESTADO_DESC", ccsGet, NULL), $this);
        $this->Link1 = & new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet, NULL), $this);
        $this->Link1->Page = "detalle.php";
        $this->TIPO_DESC = & new clsControl(ccsLabel, "TIPO_DESC", "TIPO_DESC", ccsText, "", CCGetRequestParam("TIPO_DESC", ccsGet, NULL), $this);
        $this->Sorter_TRANSFERENCIA_COD = & new clsSorter($this->ComponentName, "Sorter_TRANSFERENCIA_COD", $FileName, $this);
        $this->Sorter_RUT = & new clsSorter($this->ComponentName, "Sorter_RUT", $FileName, $this);
        $this->Sorter_IEST_NOMBRE_IES = & new clsSorter($this->ComponentName, "Sorter_IEST_NOMBRE_IES", $FileName, $this);
        $this->Sorter_NOMBRE_BANCO = & new clsSorter($this->ComponentName, "Sorter_NOMBRE_BANCO", $FileName, $this);
        $this->Sorter_OPERACION = & new clsSorter($this->ComponentName, "Sorter_OPERACION", $FileName, $this);
        $this->Sorter_LICITACION = & new clsSorter($this->ComponentName, "Sorter_LICITACION", $FileName, $this);
        $this->Sorter_MONTOCREDITO = & new clsSorter($this->ComponentName, "Sorter_MONTOCREDITO", $FileName, $this);
        $this->Sorter_FECHA = & new clsSorter($this->ComponentName, "Sorter_FECHA", $FileName, $this);
        $this->Sorter_ESTADO_DESC = & new clsSorter($this->ComponentName, "Sorter_ESTADO_DESC", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->Sorter1 = & new clsSorter($this->ComponentName, "Sorter1", $FileName, $this);
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

//Show Method @2-9C9261DF
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_RUT"] = CCGetFromGet("s_RUT", NULL);
        $this->DataSource->Parameters["sesBANCN_COD"] = CCGetSession("BANCN_COD", NULL);
        $this->DataSource->Parameters["sesRUTIES"] = CCGetSession("RUTIES", NULL);
        $this->DataSource->Parameters["sesESTADOT"] = CCGetSession("ESTADOT", NULL);
        $this->DataSource->Parameters["urls_TIPO"] = CCGetFromGet("s_TIPO", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();

		if(CCGetParam("h_search",""))
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
            $this->ControlsVisible["RUT"] = $this->RUT->Visible;
            $this->ControlsVisible["IEST_NOMBRE_IES"] = $this->IEST_NOMBRE_IES->Visible;
            $this->ControlsVisible["NOMBRE_BANCO"] = $this->NOMBRE_BANCO->Visible;
            $this->ControlsVisible["OPERACION"] = $this->OPERACION->Visible;
            $this->ControlsVisible["LICITACION"] = $this->LICITACION->Visible;
            $this->ControlsVisible["MONTOCREDITO"] = $this->MONTOCREDITO->Visible;
            $this->ControlsVisible["FECHA"] = $this->FECHA->Visible;
            $this->ControlsVisible["ESTADO_DESC"] = $this->ESTADO_DESC->Visible;
            $this->ControlsVisible["Link1"] = $this->Link1->Visible;
            $this->ControlsVisible["TIPO_DESC"] = $this->TIPO_DESC->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->TRANSFERENCIA_COD->SetValue($this->DataSource->TRANSFERENCIA_COD->GetValue());
                $this->RUT->SetValue($this->DataSource->RUT->GetValue());
                $this->IEST_NOMBRE_IES->SetValue($this->DataSource->IEST_NOMBRE_IES->GetValue());
                $this->NOMBRE_BANCO->SetValue($this->DataSource->NOMBRE_BANCO->GetValue());
                $this->OPERACION->SetValue($this->DataSource->OPERACION->GetValue());
                $this->LICITACION->SetValue($this->DataSource->LICITACION->GetValue());
                $this->MONTOCREDITO->SetValue($this->DataSource->MONTOCREDITO->GetValue());
                $this->FECHA->SetValue($this->DataSource->FECHA->GetValue());
                $this->ESTADO_DESC->SetValue($this->DataSource->ESTADO_DESC->GetValue());
                $this->Link1->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Link1->Parameters = CCAddParam($this->Link1->Parameters, "CARGA_COD", $this->DataSource->f("CARGA_COD"));
                $this->TIPO_DESC->SetValue($this->DataSource->TIPO_DESC->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->TRANSFERENCIA_COD->Show();
                $this->RUT->Show();
                $this->IEST_NOMBRE_IES->Show();
                $this->NOMBRE_BANCO->Show();
                $this->OPERACION->Show();
                $this->LICITACION->Show();
                $this->MONTOCREDITO->Show();
                $this->FECHA->Show();
                $this->ESTADO_DESC->Show();
                $this->Link1->Show();
                $this->TIPO_DESC->Show();
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
        $this->Sorter_RUT->Show();
        $this->Sorter_IEST_NOMBRE_IES->Show();
        $this->Sorter_NOMBRE_BANCO->Show();
        $this->Sorter_OPERACION->Show();
        $this->Sorter_LICITACION->Show();
        $this->Sorter_MONTOCREDITO->Show();
        $this->Sorter_FECHA->Show();
        $this->Sorter_ESTADO_DESC->Show();
        $this->Navigator->Show();
        $this->Sorter1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method


//GetErrors Method @2-956EA8CE
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->TRANSFERENCIA_COD->Errors->ToString());
        $errors = ComposeStrings($errors, $this->RUT->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IEST_NOMBRE_IES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->NOMBRE_BANCO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->OPERACION->Errors->ToString());
        $errors = ComposeStrings($errors, $this->LICITACION->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTOCREDITO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->FECHA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ESTADO_DESC->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link1->Errors->ToString());
        $errors = ComposeStrings($errors, $this->TIPO_DESC->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End VST_PI_REPORTES Class @2-FCB6E20C

class clsVST_PI_REPORTESDataSource extends clsDBOracle_1 {  //VST_PI_REPORTESDataSource Class @2-C20BD3CB

//DataSource Variables @2-03C762F9
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $TRANSFERENCIA_COD;
    var $RUT;
    var $IEST_NOMBRE_IES;
    var $NOMBRE_BANCO;
    var $OPERACION;
    var $LICITACION;
    var $MONTOCREDITO;
    var $FECHA;
    var $ESTADO_DESC;
    var $TIPO_DESC;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-015B0F9C
    function clsVST_PI_REPORTESDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid VST_PI_REPORTES";
        $this->Initialize();
        $this->TRANSFERENCIA_COD = new clsField("TRANSFERENCIA_COD", ccsFloat, "");
        
        $this->RUT = new clsField("RUT", ccsFloat, "");
        
        $this->IEST_NOMBRE_IES = new clsField("IEST_NOMBRE_IES", ccsText, "");
        
        $this->NOMBRE_BANCO = new clsField("NOMBRE_BANCO", ccsText, "");
        
        $this->OPERACION = new clsField("OPERACION", ccsFloat, "");
        
        $this->LICITACION = new clsField("LICITACION", ccsFloat, "");
        
        $this->MONTOCREDITO = new clsField("MONTOCREDITO", ccsFloat, "");
        
        $this->FECHA = new clsField("FECHA", ccsDate, $this->DateFormat);
        
        $this->ESTADO_DESC = new clsField("ESTADO_DESC", ccsText, "");
        
        $this->TIPO_DESC = new clsField("TIPO_DESC", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-426D4C3F
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "TRANSFERENCIA_COD";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_TRANSFERENCIA_COD" => array("TRANSFERENCIA_COD", ""), 
            "Sorter_RUT" => array("RUT", ""), 
            "Sorter_IEST_NOMBRE_IES" => array("IEST_NOMBRE_IES", ""), 
            "Sorter_NOMBRE_BANCO" => array("NOMBRE_BANCO", ""), 
            "Sorter_OPERACION" => array("OPERACION", ""), 
            "Sorter_LICITACION" => array("LICITACION", ""), 
            "Sorter_MONTOCREDITO" => array("MONTOCREDITO", ""), 
            "Sorter_FECHA" => array("FECHA", ""), 
            "Sorter_ESTADO_DESC" => array("ESTADO_DESC", ""), 
            "Sorter1" => array("TIPO_DESC", "")));
    }
//End SetOrder Method

//Prepare Method @2-25976751
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_RUT", ccsFloat, "", "", $this->Parameters["urls_RUT"], "", false);
        $this->wp->AddParameter("2", "sesBANCN_COD", ccsFloat, "", "", $this->Parameters["sesBANCN_COD"], "", false);
        $this->wp->AddParameter("3", "sesRUTIES", ccsFloat, "", "", $this->Parameters["sesRUTIES"], "", false);
        $this->wp->AddParameter("4", "sesESTADOT", ccsFloat, "", "", $this->Parameters["sesESTADOT"], "", false);
        $this->wp->AddParameter("5", "urls_TIPO", ccsFloat, "", "", $this->Parameters["urls_TIPO"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "RUT", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "BANCN_COD", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsFloat),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "IESN_RUT", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsFloat),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opNotEqual, "ESTADO_COD", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsFloat),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "TIPO_COD", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsFloat),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]);
    }
//End Prepare Method

//Open Method @2-46940079
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM VST_PI_REPORTES";
        $this->SQL = "SELECT * \n\n" .
        "FROM VST_PI_REPORTES {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-64175F24
    function SetValues()
    {
        $this->TRANSFERENCIA_COD->SetDBValue(trim($this->f("TRANSFERENCIA_COD")));
        $this->RUT->SetDBValue(trim($this->f("RUT")));
        $this->IEST_NOMBRE_IES->SetDBValue($this->f("IEST_NOMBRE_IES"));
        $this->NOMBRE_BANCO->SetDBValue($this->f("NOMBRE_BANCO"));
        $this->OPERACION->SetDBValue(trim($this->f("OPERACION")));
        $this->LICITACION->SetDBValue(trim($this->f("LICITACION")));
        $this->MONTOCREDITO->SetDBValue(trim($this->f("MONTOCREDITO")));
        $this->FECHA->SetDBValue(trim($this->f("FECHA")));
        $this->ESTADO_DESC->SetDBValue($this->f("ESTADO_DESC"));
        $this->TIPO_DESC->SetDBValue($this->f("TIPO_DESC"));
    }
//End SetValues Method

} //End VST_PI_REPORTESDataSource Class @2-FCB6E20C

class clsRecordVST_PI_REPORTESSearch { //VST_PI_REPORTESSearch Class @3-644498E9

//Variables @3-D6FF3E86

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

//Class_Initialize Event @3-BFDD495C
    function clsRecordVST_PI_REPORTESSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record VST_PI_REPORTESSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "VST_PI_REPORTESSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->s_RUT = & new clsControl(ccsTextBox, "s_RUT", "Rut", ccsFloat, "", CCGetRequestParam("s_RUT", $Method, NULL), $this);
            $this->h_search = & new clsControl(ccsHidden, "h_search", "h_search", ccsText, "", CCGetRequestParam("h_search", $Method, NULL), $this);
            $this->s_TIPO = & new clsControl(ccsListBox, "s_TIPO", "Tipo Pago", ccsFloat, "", CCGetRequestParam("s_TIPO", $Method, NULL), $this);
            $this->s_TIPO->DSType = dsTable;
            $this->s_TIPO->DataSource = new clsDBOracle_1();
            $this->s_TIPO->ds = & $this->s_TIPO->DataSource;
            $this->s_TIPO->DataSource->SQL = "SELECT * \n" .
"FROM PI_PRM_TIPO {SQL_Where} {SQL_OrderBy}";
            list($this->s_TIPO->BoundColumn, $this->s_TIPO->TextColumn, $this->s_TIPO->DBFormat) = array("TIPO_COD", "TIPO_DESC", "");
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->h_search->Value) && !strlen($this->h_search->Value) && $this->h_search->Value !== false)
                    $this->h_search->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @3-999226EC
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_RUT->Validate() && $Validation);
        $Validation = ($this->h_search->Validate() && $Validation);
        $Validation = ($this->s_TIPO->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_RUT->Errors->Count() == 0);
        $Validation =  $Validation && ($this->h_search->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_TIPO->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-94DC3699
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_RUT->Errors->Count());
        $errors = ($errors || $this->h_search->Errors->Count());
        $errors = ($errors || $this->s_TIPO->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @3-ED598703
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

//Operation Method @3-A86C00C0
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
        $Redirect = "consulta_rut.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "consulta_rut.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-2A526A8D
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

        $this->s_TIPO->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_RUT->Errors->ToString());
            $Error = ComposeStrings($Error, $this->h_search->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_TIPO->Errors->ToString());
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

        $this->s_RUT->Show();
        $this->h_search->Show();
        $this->s_TIPO->Show();
        $this->Button_DoSearch->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End VST_PI_REPORTESSearch Class @3-FCB6E20C

//Initialize Page @1-8D1C5C60
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
$TemplateFileName = "consulta_rut.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Authenticate User @1-5C03D13B
CCSecurityRedirect("A;C;D;H;K;S;R;U;B;T;X", "");
//End Authenticate User

//Include events file @1-EDD2F4E6
include_once("./consulta_rut_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-5144E2D8
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$VST_PI_REPORTES = & new clsGridVST_PI_REPORTES("", $MainPage);
$VST_PI_REPORTESSearch = & new clsRecordVST_PI_REPORTESSearch("", $MainPage);
$MainPage->VST_PI_REPORTES = & $VST_PI_REPORTES;
$MainPage->VST_PI_REPORTESSearch = & $VST_PI_REPORTESSearch;
$VST_PI_REPORTES->Initialize();

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

//Execute Components @1-9DC3813D
$VST_PI_REPORTESSearch->Operation();
//End Execute Components

//Go to destination page @1-518BECE0
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($VST_PI_REPORTES);
    unset($VST_PI_REPORTESSearch);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-D09E35F3
$VST_PI_REPORTES->Show();
$VST_PI_REPORTESSearch->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-B5C6ACF2
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($VST_PI_REPORTES);
unset($VST_PI_REPORTESSearch);
unset($Tpl);
//End Unload Page


?>
