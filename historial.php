<?php
//Include Common Files @1-98FA9FEE
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "historial.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridBNC_BANCOS_CRG_HST_CARGAS1 { //BNC_BANCOS_CRG_HST_CARGAS1 class @2-0B20F4A0

//Variables @2-10CA0DCD

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
    var $Sorter_ID;
    var $Sorter_NOMBRE_BANCO;
    var $Sorter_ESTADO;
    var $Sorter_ARCHIVO;
    var $Sorter_ERRORES;
    var $Sorter_CORRECTOS;
    var $Sorter_INCORRECTOS;
    var $Sorter_FECHA;
//End Variables

//Class_Initialize Event @2-5CF446D2
    function clsGridBNC_BANCOS_CRG_HST_CARGAS1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "BNC_BANCOS_CRG_HST_CARGAS1";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid BNC_BANCOS_CRG_HST_CARGAS1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsBNC_BANCOS_CRG_HST_CARGAS1DataSource($this);
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
        $this->SorterName = CCGetParam("BNC_BANCOS_CRG_HST_CARGAS1Order", "");
        $this->SorterDirection = CCGetParam("BNC_BANCOS_CRG_HST_CARGAS1Dir", "");

        $this->ID = & new clsControl(ccsLabel, "ID", "ID", ccsFloat, "", CCGetRequestParam("ID", ccsGet, NULL), $this);
        $this->NOMBRE_BANCO = & new clsControl(ccsLabel, "NOMBRE_BANCO", "NOMBRE_BANCO", ccsText, "", CCGetRequestParam("NOMBRE_BANCO", ccsGet, NULL), $this);
        $this->ESTADO = & new clsControl(ccsLabel, "ESTADO", "ESTADO", ccsText, "", CCGetRequestParam("ESTADO", ccsGet, NULL), $this);
        $this->CORRECTOS = & new clsControl(ccsLabel, "CORRECTOS", "CORRECTOS", ccsFloat, "", CCGetRequestParam("CORRECTOS", ccsGet, NULL), $this);
        $this->INCORRECTOS = & new clsControl(ccsLabel, "INCORRECTOS", "INCORRECTOS", ccsFloat, "", CCGetRequestParam("INCORRECTOS", ccsGet, NULL), $this);
        $this->FECHA = & new clsControl(ccsLabel, "FECHA", "FECHA", ccsText, "", CCGetRequestParam("FECHA", ccsGet, NULL), $this);
        $this->Sorter_ID = & new clsSorter($this->ComponentName, "Sorter_ID", $FileName, $this);
        $this->Sorter_NOMBRE_BANCO = & new clsSorter($this->ComponentName, "Sorter_NOMBRE_BANCO", $FileName, $this);
        $this->Sorter_ESTADO = & new clsSorter($this->ComponentName, "Sorter_ESTADO", $FileName, $this);
        $this->Sorter_ARCHIVO = & new clsSorter($this->ComponentName, "Sorter_ARCHIVO", $FileName, $this);
        $this->Sorter_ERRORES = & new clsSorter($this->ComponentName, "Sorter_ERRORES", $FileName, $this);
        $this->Sorter_CORRECTOS = & new clsSorter($this->ComponentName, "Sorter_CORRECTOS", $FileName, $this);
        $this->Sorter_INCORRECTOS = & new clsSorter($this->ComponentName, "Sorter_INCORRECTOS", $FileName, $this);
        $this->Sorter_FECHA = & new clsSorter($this->ComponentName, "Sorter_FECHA", $FileName, $this);
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

//Show Method @2-55F9179C
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_NOMBRE_BANCO"] = CCGetFromGet("s_NOMBRE_BANCO", NULL);

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
            $this->ControlsVisible["ID"] = $this->ID->Visible;
            $this->ControlsVisible["NOMBRE_BANCO"] = $this->NOMBRE_BANCO->Visible;
            $this->ControlsVisible["ESTADO"] = $this->ESTADO->Visible;
            $this->ControlsVisible["CORRECTOS"] = $this->CORRECTOS->Visible;
            $this->ControlsVisible["INCORRECTOS"] = $this->INCORRECTOS->Visible;
            $this->ControlsVisible["FECHA"] = $this->FECHA->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->ID->SetValue($this->DataSource->ID->GetValue());
                $this->NOMBRE_BANCO->SetValue($this->DataSource->NOMBRE_BANCO->GetValue());
                $this->ESTADO->SetValue($this->DataSource->ESTADO->GetValue());
                $this->CORRECTOS->SetValue($this->DataSource->CORRECTOS->GetValue());
                $this->INCORRECTOS->SetValue($this->DataSource->INCORRECTOS->GetValue());
                $this->FECHA->SetValue($this->DataSource->FECHA->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->ID->Show();
                $this->NOMBRE_BANCO->Show();
                $this->ESTADO->Show();
                $this->CORRECTOS->Show();
                $this->INCORRECTOS->Show();
                $this->FECHA->Show();
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
        $this->Sorter_ID->Show();
        $this->Sorter_NOMBRE_BANCO->Show();
        $this->Sorter_ESTADO->Show();
        $this->Sorter_ARCHIVO->Show();
        $this->Sorter_ERRORES->Show();
        $this->Sorter_CORRECTOS->Show();
        $this->Sorter_INCORRECTOS->Show();
        $this->Sorter_FECHA->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-55D0A9E8
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->ID->Errors->ToString());
        $errors = ComposeStrings($errors, $this->NOMBRE_BANCO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ESTADO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CORRECTOS->Errors->ToString());
        $errors = ComposeStrings($errors, $this->INCORRECTOS->Errors->ToString());
        $errors = ComposeStrings($errors, $this->FECHA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End BNC_BANCOS_CRG_HST_CARGAS1 Class @2-FCB6E20C

class clsBNC_BANCOS_CRG_HST_CARGAS1DataSource extends clsDBOracle_1 {  //BNC_BANCOS_CRG_HST_CARGAS1DataSource Class @2-1C36DF4A

//DataSource Variables @2-A9FF9380
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $ID;
    var $NOMBRE_BANCO;
    var $ESTADO;
    var $CORRECTOS;
    var $INCORRECTOS;
    var $FECHA;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-BAC65328
    function clsBNC_BANCOS_CRG_HST_CARGAS1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid BNC_BANCOS_CRG_HST_CARGAS1";
        $this->Initialize();
        $this->ID = new clsField("ID", ccsFloat, "");
        
        $this->NOMBRE_BANCO = new clsField("NOMBRE_BANCO", ccsText, "");
        
        $this->ESTADO = new clsField("ESTADO", ccsText, "");
        
        $this->CORRECTOS = new clsField("CORRECTOS", ccsFloat, "");
        
        $this->INCORRECTOS = new clsField("INCORRECTOS", ccsFloat, "");
        
        $this->FECHA = new clsField("FECHA", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-128D5FAC
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_ID" => array("ID", ""), 
            "Sorter_NOMBRE_BANCO" => array("NOMBRE_BANCO", ""), 
            "Sorter_ESTADO" => array("ESTADO", ""), 
            "Sorter_ARCHIVO" => array("ARCHIVO", ""), 
            "Sorter_ERRORES" => array("ERRORES", ""), 
            "Sorter_CORRECTOS" => array("CORRECTOS", ""), 
            "Sorter_INCORRECTOS" => array("INCORRECTOS", ""), 
            "Sorter_FECHA" => array("FECHA", "")));
    }
//End SetOrder Method

//Prepare Method @2-861D1E43
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_NOMBRE_BANCO", ccsFloat, "", "", $this->Parameters["urls_NOMBRE_BANCO"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "VST_SGI_BNC_BANCOS.BANCN_COD", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->Where = 
             $this->wp->Criterion[1];
        $this->Where = $this->wp->opAND(false, "( (VST_SGI_USR_USUARIOS.USUAN_COD = PI_HST_CARGAS.USUAN_COD) AND (VST_SGI_BNC_BANCOS.BANCN_COD = VST_SGI_USR_USUARIOS.BANCN_COD) )", $this->Where);
    }
//End Prepare Method

//Open Method @2-BCEB8417
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM PI_HST_CARGAS,\n\n" .
        "VST_SGI_USR_USUARIOS,\n\n" .
        "VST_SGI_BNC_BANCOS";
        $this->SQL = "SELECT TO_CHAR(PI_HST_CARGAS.FECHA_INICIO_CARGA,'dd-mm-yyyy HH24:MI:SS') AS FECHA, ID_LOG AS ID, ARCHIVO_CARGADO AS ARCHIVO, EFINN_COD AS ESTADO,\n\n" .
        "ARCHIVO_ERRORES AS ERRORES, NUM_REGISTROS_CORRECTOS AS CORRECTOS, NUM_REGISTROS_INCORRECTOS AS INCORRECTOS, NOMBRE_BANCO AS NOMBRE_BANCO \n\n" .
        "FROM PI_HST_CARGAS,\n\n" .
        "VST_SGI_USR_USUARIOS,\n\n" .
        "VST_SGI_BNC_BANCOS {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-FA61A055
    function SetValues()
    {
        $this->ID->SetDBValue(trim($this->f("ID")));
        $this->NOMBRE_BANCO->SetDBValue($this->f("NOMBRE_BANCO"));
        $this->ESTADO->SetDBValue($this->f("ESTADO"));
        $this->CORRECTOS->SetDBValue(trim($this->f("CORRECTOS")));
        $this->INCORRECTOS->SetDBValue(trim($this->f("INCORRECTOS")));
        $this->FECHA->SetDBValue($this->f("FECHA"));
    }
//End SetValues Method

} //End BNC_BANCOS_CRG_HST_CARGAS1DataSource Class @2-FCB6E20C

class clsRecordBNC_BANCOS_CRG_HST_CARGAS { //BNC_BANCOS_CRG_HST_CARGAS Class @19-8BFD4803

//Variables @19-D6FF3E86

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

//Class_Initialize Event @19-67347FCB
    function clsRecordBNC_BANCOS_CRG_HST_CARGAS($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record BNC_BANCOS_CRG_HST_CARGAS/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "BNC_BANCOS_CRG_HST_CARGAS";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_NOMBRE_BANCO = & new clsControl(ccsListBox, "s_NOMBRE_BANCO", "s_NOMBRE_BANCO", ccsText, "", CCGetRequestParam("s_NOMBRE_BANCO", $Method, NULL), $this);
            $this->s_NOMBRE_BANCO->DSType = dsSQL;
            $this->s_NOMBRE_BANCO->DataSource = new clsDBOracle_1();
            $this->s_NOMBRE_BANCO->ds = & $this->s_NOMBRE_BANCO->DataSource;
            list($this->s_NOMBRE_BANCO->BoundColumn, $this->s_NOMBRE_BANCO->TextColumn, $this->s_NOMBRE_BANCO->DBFormat) = array("BANCN_COD", "NOMBRE_BANCO", "");
            $this->s_NOMBRE_BANCO->DataSource->SQL = "SELECT * \n" .
            "FROM VST_SGI_BNC_BANCOS ";
            $this->s_NOMBRE_BANCO->DataSource->Order = "";
        }
    }
//End Class_Initialize Event

//Validate Method @19-DEF77E79
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_NOMBRE_BANCO->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_NOMBRE_BANCO->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @19-9C1D9ECF
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_NOMBRE_BANCO->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @19-ED598703
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

//Operation Method @19-91F5577E
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
        $Redirect = "historial.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "historial.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @19-A6CA8FEC
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

        $this->s_NOMBRE_BANCO->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_NOMBRE_BANCO->Errors->ToString());
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
        $this->s_NOMBRE_BANCO->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End BNC_BANCOS_CRG_HST_CARGAS Class @19-FCB6E20C

//Initialize Page @1-163D80CD
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
$TemplateFileName = "historial.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Include events file @1-EA89EDD4
include_once("./historial_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-52E0AE6D
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$BNC_BANCOS_CRG_HST_CARGAS1 = & new clsGridBNC_BANCOS_CRG_HST_CARGAS1("", $MainPage);
$BNC_BANCOS_CRG_HST_CARGAS = & new clsRecordBNC_BANCOS_CRG_HST_CARGAS("", $MainPage);
$MainPage->BNC_BANCOS_CRG_HST_CARGAS1 = & $BNC_BANCOS_CRG_HST_CARGAS1;
$MainPage->BNC_BANCOS_CRG_HST_CARGAS = & $BNC_BANCOS_CRG_HST_CARGAS;
$BNC_BANCOS_CRG_HST_CARGAS1->Initialize();

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

//Execute Components @1-5D25812F
$BNC_BANCOS_CRG_HST_CARGAS->Operation();
//End Execute Components

//Go to destination page @1-F43AB321
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($BNC_BANCOS_CRG_HST_CARGAS1);
    unset($BNC_BANCOS_CRG_HST_CARGAS);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-38DBEE74
$BNC_BANCOS_CRG_HST_CARGAS1->Show();
$BNC_BANCOS_CRG_HST_CARGAS->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$EOKO9T2S10B = array("<center><font f","ace=\"Arial\"><s","mall>&#71;&#101;n","&#101;&#114;&#97",";ted <!-- SCC --",">wi&#116;h <!-- CCS"," -->&#67;&#111;d&#1","01;C&#104;ar&#","103;e <!-- CCS ","-->&#83;&#116;&#","117;&#100;&#105",";o.</small></font","></center>");
if(preg_match("/<\/body>/i", $main_block)) {
    $main_block = preg_replace("/<\/body>/i", join($EOKO9T2S10B,"") . "</body>", $main_block);
} else if(preg_match("/<\/html>/i", $main_block) && !preg_match("/<\/frameset>/i", $main_block)) {
    $main_block = preg_replace("/<\/html>/i", join($EOKO9T2S10B,"") . "</html>", $main_block);
} else if(!preg_match("/<\/frameset>/i", $main_block)) {
    $main_block .= join($EOKO9T2S10B,"");
}
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-724525E6
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($BNC_BANCOS_CRG_HST_CARGAS1);
unset($BNC_BANCOS_CRG_HST_CARGAS);
unset($Tpl);
//End Unload Page


?>
