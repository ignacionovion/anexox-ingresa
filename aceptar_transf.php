<?php
//Include Common Files @1-69EBEFCD
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "aceptar_transf.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordPI_TRANSFERENCIAS { //PI_TRANSFERENCIAS Class @2-765634DE

//Variables @2-D6FF3E86

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

//Class_Initialize Event @2-EDEC036E
    function clsRecordPI_TRANSFERENCIAS($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record PI_TRANSFERENCIAS/Error";
        $this->DataSource = new clsPI_TRANSFERENCIASDataSource($this);
        $this->ds = & $this->DataSource;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "PI_TRANSFERENCIAS";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
            $this->ESTADO_COD = & new clsControl(ccsRadioButton, "ESTADO_COD", "Estado Transferencia", ccsFloat, "", CCGetRequestParam("ESTADO_COD", $Method, NULL), $this);
            $this->ESTADO_COD->DSType = dsSQL;
            $this->ESTADO_COD->DataSource = new clsDBOracle_1();
            $this->ESTADO_COD->ds = & $this->ESTADO_COD->DataSource;
            list($this->ESTADO_COD->BoundColumn, $this->ESTADO_COD->TextColumn, $this->ESTADO_COD->DBFormat) = array("ESTADO_COD", "ESTADO_DESC", "");
            $this->ESTADO_COD->DataSource->SQL = "SELECT estado_cod,\n" .
            "case when estado_cod = 3 then 'Acepta (La transferencia está correcta)' else 'Rechaza (Hay error o el monto no corresponde)'\n" .
            "end as estado_desc\n" .
            "FROM PI_PRM_ESTADO \n" .
            "where estado_cod in (3,4)";
            $this->ESTADO_COD->DataSource->Order = "";
            $this->ESTADO_COD->HTML = true;
            $this->ESTADO_COD->Required = true;
            $this->COMENTARIO = & new clsControl(ccsTextArea, "COMENTARIO", $CCSLocales->GetText("COMENTARIO"), ccsText, "", CCGetRequestParam("COMENTARIO", $Method, NULL), $this);
            $this->Label1 = & new clsControl(ccsLabel, "Label1", "Label1", ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("Label1", $Method, NULL), $this);
            $this->Hidden1 = & new clsControl(ccsHidden, "Hidden1", "Hidden1", ccsText, "", CCGetRequestParam("Hidden1", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @2-90EC5F9E
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlTRANSFERENCIA_COD"] = CCGetFromGet("TRANSFERENCIA_COD", NULL);
    }
//End Initialize Method

//Validate Method @2-5207B476
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->ESTADO_COD->Validate() && $Validation);
        $Validation = ($this->COMENTARIO->Validate() && $Validation);
        $Validation = ($this->Hidden1->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->ESTADO_COD->Errors->Count() == 0);
        $Validation =  $Validation && ($this->COMENTARIO->Errors->Count() == 0);
        $Validation =  $Validation && ($this->Hidden1->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-02F82B8E
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->ESTADO_COD->Errors->Count());
        $errors = ($errors || $this->COMENTARIO->Errors->Count());
        $errors = ($errors || $this->Label1->Errors->Count());
        $errors = ($errors || $this->Hidden1->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @2-ED598703
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

//Operation Method @2-54ED6DDB
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Cancel";
            if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "index.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//UpdateRow Method @2-A5FAF1AA
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->ESTADO_COD->SetValue($this->ESTADO_COD->GetValue(true));
        $this->DataSource->COMENTARIO->SetValue($this->COMENTARIO->GetValue(true));
        $this->DataSource->Label1->SetValue($this->Label1->GetValue(true));
        $this->DataSource->Hidden1->SetValue($this->Hidden1->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @2-8D2A6D01
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

        $this->ESTADO_COD->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
                $this->Label1->SetValue($this->DataSource->Label1->GetValue());
                if(!$this->FormSubmitted){
                    $this->ESTADO_COD->SetValue($this->DataSource->ESTADO_COD->GetValue());
                    $this->COMENTARIO->SetValue($this->DataSource->COMENTARIO->GetValue());
                    $this->Hidden1->SetValue($this->DataSource->Hidden1->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->ESTADO_COD->Errors->ToString());
            $Error = ComposeStrings($Error, $this->COMENTARIO->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Label1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Hidden1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Update->Show();
        $this->Button_Cancel->Show();
        $this->ESTADO_COD->Show();
        $this->COMENTARIO->Show();
        $this->Label1->Show();
        $this->Hidden1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End PI_TRANSFERENCIAS Class @2-FCB6E20C

class clsPI_TRANSFERENCIASDataSource extends clsDBOracle_1 {  //PI_TRANSFERENCIASDataSource Class @2-F1BBB850

//DataSource Variables @2-7C7375A1
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;

    var $UpdateFields = array();

    // Datasource fields
    var $ESTADO_COD;
    var $COMENTARIO;
    var $Label1;
    var $Hidden1;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-9755E502
    function clsPI_TRANSFERENCIASDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record PI_TRANSFERENCIAS/Error";
        $this->Initialize();
        $this->ESTADO_COD = new clsField("ESTADO_COD", ccsFloat, "");
        
        $this->COMENTARIO = new clsField("COMENTARIO", ccsText, "");
        
        $this->Label1 = new clsField("Label1", ccsFloat, "");
        
        $this->Hidden1 = new clsField("Hidden1", ccsText, "");
        

        $this->UpdateFields["ESTADO_COD"] = array("Name" => "ESTADO_COD", "Value" => "", "DataType" => ccsFloat, "OmitIfEmpty" => 1);
        $this->UpdateFields["COMENTARIO"] = array("Name" => "COMENTARIO", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["RUTBANCO"] = array("Name" => "RUTBANCO", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-BFBC8920
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlTRANSFERENCIA_COD", ccsFloat, "", "", $this->Parameters["urlTRANSFERENCIA_COD"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "TRANSFERENCIA_COD", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-B63F820F
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM PI_TRANSFERENCIAS {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-E4CB3F99
    function SetValues()
    {
        $this->ESTADO_COD->SetDBValue(trim($this->f("ESTADO_COD")));
        $this->COMENTARIO->SetDBValue($this->f("COMENTARIO"));
        $this->Label1->SetDBValue(trim($this->f("MONTO_TOTAL")));
        $this->Hidden1->SetDBValue($this->f("RUTBANCO"));
    }
//End SetValues Method

//Update Method @2-34A2F87E
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["ESTADO_COD"]["Value"] = $this->ESTADO_COD->GetDBValue(true);
        $this->UpdateFields["COMENTARIO"]["Value"] = $this->COMENTARIO->GetDBValue(true);
        $this->UpdateFields["RUTBANCO"]["Value"] = $this->Hidden1->GetDBValue(true);
        $this->SQL = CCBuildUpdate("PI_TRANSFERENCIAS", $this->UpdateFields, $this);
        $this->SQL .= strlen($this->Where) ? " WHERE " . $this->Where : $this->Where;
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

} //End PI_TRANSFERENCIASDataSource Class @2-FCB6E20C

//Initialize Page @1-1243CDAF
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
$TemplateFileName = "aceptar_transf.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Authenticate User @1-8836F68B
CCSecurityRedirect("C;D", "");
//End Authenticate User

//Include events file @1-F7F50B16
include_once("./aceptar_transf_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-6F25E05C
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$PI_TRANSFERENCIAS = & new clsRecordPI_TRANSFERENCIAS("", $MainPage);
$MainPage->PI_TRANSFERENCIAS = & $PI_TRANSFERENCIAS;
$PI_TRANSFERENCIAS->Initialize();

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

//Execute Components @1-F0B9FCFC
$PI_TRANSFERENCIAS->Operation();
//End Execute Components

//Go to destination page @1-2268B48A
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($PI_TRANSFERENCIAS);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-A31BD866
$PI_TRANSFERENCIAS->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-A2E9BAE6
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($PI_TRANSFERENCIAS);
unset($Tpl);
//End Unload Page


?>
