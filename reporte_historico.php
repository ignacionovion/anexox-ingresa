<?php
//Include Common Files @1-F42FCE1F
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "reporte_historico.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordVST_PI_REPORTESHST { //VST_PI_REPORTESHST Class @3-70AFB68A

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

//Class_Initialize Event @3-292AA441
    function clsRecordVST_PI_REPORTESHST($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record VST_PI_REPORTESHST/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "VST_PI_REPORTESHST";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_LICITACION = & new clsControl(ccsTextBox, "s_LICITACION", "Año Licitación", ccsInteger, "", CCGetRequestParam("s_LICITACION", $Method, NULL), $this);
            $this->s_IESN_RUT = & new clsControl(ccsListBox, "s_IESN_RUT", "IES", ccsFloat, "", CCGetRequestParam("s_IESN_RUT", $Method, NULL), $this);
            $this->s_IESN_RUT->DSType = dsSQL;
            $this->s_IESN_RUT->DataSource = new clsDBOracle_1();
            $this->s_IESN_RUT->ds = & $this->s_IESN_RUT->DataSource;
            list($this->s_IESN_RUT->BoundColumn, $this->s_IESN_RUT->TextColumn, $this->s_IESN_RUT->DBFormat) = array("IESN_RUT", "IEST_NOMBRE_IES", "");
            $this->s_IESN_RUT->DataSource->SQL = "SELECT * \n" .
            "FROM VST_SGI_OA_IES  {SQL_OrderBy}";
            $this->s_IESN_RUT->DataSource->Order = "IEST_NOMBRE_IES";
            $this->s_RUTBCO = & new clsControl(ccsListBox, "s_RUTBCO", "Banco", ccsFloat, "", CCGetRequestParam("s_RUTBCO", $Method, NULL), $this);
            $this->s_RUTBCO->DSType = dsSQL;
            $this->s_RUTBCO->DataSource = new clsDBOracle_1();
            $this->s_RUTBCO->ds = & $this->s_RUTBCO->DataSource;
            list($this->s_RUTBCO->BoundColumn, $this->s_RUTBCO->TextColumn, $this->s_RUTBCO->DBFormat) = array("RUT_BANCO", "NOMBRE_BANCO", "");
            $this->s_RUTBCO->DataSource->SQL = "SELECT * \n" .
            "FROM VST_SGI_BNC_BANCOS where bancn_cod <> 6";
            $this->s_RUTBCO->DataSource->Order = "";
            $this->s_RUT = & new clsControl(ccsTextBox, "s_RUT", "RUT", ccsInteger, "", CCGetRequestParam("s_RUT", $Method, NULL), $this);
            $this->s_OPERACION = & new clsControl(ccsTextBox, "s_OPERACION", "Año Operación", ccsInteger, "", CCGetRequestParam("s_OPERACION", $Method, NULL), $this);
            $this->s_TIPO = & new clsControl(ccsHidden, "s_TIPO", "s_TIPO", ccsText, "", CCGetRequestParam("s_TIPO", $Method, NULL), $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->s_TIPO->Value) && !strlen($this->s_TIPO->Value) && $this->s_TIPO->Value !== false)
                    $this->s_TIPO->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Validate Method @3-1F7DD3F7
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_LICITACION->Validate() && $Validation);
        $Validation = ($this->s_IESN_RUT->Validate() && $Validation);
        $Validation = ($this->s_RUTBCO->Validate() && $Validation);
        $Validation = ($this->s_RUT->Validate() && $Validation);
        $Validation = ($this->s_OPERACION->Validate() && $Validation);
        $Validation = ($this->s_TIPO->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_LICITACION->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IESN_RUT->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_RUTBCO->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_RUT->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_OPERACION->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_TIPO->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-1ED4702D
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_LICITACION->Errors->Count());
        $errors = ($errors || $this->s_IESN_RUT->Errors->Count());
        $errors = ($errors || $this->s_RUTBCO->Errors->Count());
        $errors = ($errors || $this->s_RUT->Errors->Count());
        $errors = ($errors || $this->s_OPERACION->Errors->Count());
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

//Operation Method @3-5927D824
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
        $Redirect = "reporte_historico.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "reporte_historico.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-8D8AA3BC
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

        $this->s_IESN_RUT->Prepare();
        $this->s_RUTBCO->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_LICITACION->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_IESN_RUT->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_RUTBCO->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_RUT->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_OPERACION->Errors->ToString());
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

        $this->Button_DoSearch->Show();
        $this->s_LICITACION->Show();
        $this->s_IESN_RUT->Show();
        $this->s_RUTBCO->Show();
        $this->s_RUT->Show();
        $this->s_OPERACION->Show();
        $this->s_TIPO->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End VST_PI_REPORTESHST Class @3-FCB6E20C



//Initialize Page @1-82236169
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
$TemplateFileName = "reporte_historico.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Authenticate User @1-5C03D13B
CCSecurityRedirect("A;C;D;H;K;S", "");
//End Authenticate User

//Include events file @1-4A566D15
include_once("./reporte_historico_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-E0F8249E
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$VST_PI_REPORTESHST = & new clsRecordVST_PI_REPORTESHST("", $MainPage);
$MainPage->VST_PI_REPORTESHST = & $VST_PI_REPORTESHST;

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

//Execute Components @1-7261EFF0
$VST_PI_REPORTESHST->Operation();
//End Execute Components

//Go to destination page @1-881CBF70
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($VST_PI_REPORTESHST);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-97B8F9FE
$VST_PI_REPORTESHST->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-28E97277
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($VST_PI_REPORTESHST);
unset($Tpl);
//End Unload Page


?>
