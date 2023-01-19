<?php
//Include Common Files @1-A22AF2C0
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "datos_cc_record.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordPI_DATOS_CC { //PI_DATOS_CC Class @2-CFAEF936

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

//Class_Initialize Event @2-99D76EFC
    function clsRecordPI_DATOS_CC($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record PI_DATOS_CC/Error";
        $this->DataSource = new clsPI_DATOS_CCDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "PI_DATOS_CC";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Insert = & new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->RUTIES = & new clsControl(ccsTextBox, "RUTIES", "Rut IES", ccsInteger, "", CCGetRequestParam("RUTIES", $Method, NULL), $this);
            $this->RUTIES->Required = true;
            $this->NOMBRE_IES = & new clsControl(ccsTextBox, "NOMBRE_IES", "Nombre IES", ccsText, "", CCGetRequestParam("NOMBRE_IES", $Method, NULL), $this);
            $this->NOMBRE_IES->Required = true;
            $this->BANCO_CC = & new clsControl(ccsTextBox, "BANCO_CC", "Banco CC", ccsText, "", CCGetRequestParam("BANCO_CC", $Method, NULL), $this);
            $this->BANCO_CC->Required = true;
            $this->N_CUENTA = & new clsControl(ccsTextBox, "N_CUENTA", "N° Cuenta", ccsText, "", CCGetRequestParam("N_CUENTA", $Method, NULL), $this);
            $this->N_CUENTA->Required = true;
            $this->CONTRAPARTE = & new clsControl(ccsTextBox, "CONTRAPARTE", "Contraparte", ccsText, "", CCGetRequestParam("CONTRAPARTE", $Method, NULL), $this);
            $this->CONTRAPARTE->Required = true;
            $this->EMAIL = & new clsControl(ccsTextBox, "EMAIL", "E-Mail", ccsText, "", CCGetRequestParam("EMAIL", $Method, NULL), $this);
            $this->EMAIL->Required = true;
            $this->h_datos_cc_cod = & new clsControl(ccsHidden, "h_datos_cc_cod", "h_datos_cc_cod", ccsInteger, "", CCGetRequestParam("h_datos_cc_cod", $Method, NULL), $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->h_datos_cc_cod->Value) && !strlen($this->h_datos_cc_cod->Value) && $this->h_datos_cc_cod->Value !== false)
                    $this->h_datos_cc_cod->SetText(1);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @2-742970C1
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlDATOS_CC_COD"] = CCGetFromGet("DATOS_CC_COD", NULL);
    }
//End Initialize Method

//Validate Method @2-6E8FD8EA
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        if($this->EditMode && strlen($this->DataSource->Where))
            $Where = " AND NOT (" . $this->DataSource->Where . ")";
        $this->DataSource->RUTIES->SetValue($this->RUTIES->GetValue());
        if(CCDLookUp("COUNT(*)", "PI_DATOS_CC", "RUTIES=" . $this->DataSource->ToSQL($this->DataSource->RUTIES->GetDBValue(), $this->DataSource->RUTIES->DataType) . $Where, $this->DataSource) > 0)
            $this->RUTIES->Errors->addError($CCSLocales->GetText("CCS_UniqueValue", "Rut IES"));
        if(strlen($this->EMAIL->GetText()) && !preg_match ("/^[\w\.-]{1,}\@([\da-zA-Z-]{1,}\.){1,}[\da-zA-Z-]+$/", $this->EMAIL->GetText())) {
            $this->EMAIL->Errors->addError($CCSLocales->GetText("CCS_MaskValidation", "E-Mail"));
        }
        $Validation = ($this->RUTIES->Validate() && $Validation);
        $Validation = ($this->NOMBRE_IES->Validate() && $Validation);
        $Validation = ($this->BANCO_CC->Validate() && $Validation);
        $Validation = ($this->N_CUENTA->Validate() && $Validation);
        $Validation = ($this->CONTRAPARTE->Validate() && $Validation);
        $Validation = ($this->EMAIL->Validate() && $Validation);
        $Validation = ($this->h_datos_cc_cod->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->RUTIES->Errors->Count() == 0);
        $Validation =  $Validation && ($this->NOMBRE_IES->Errors->Count() == 0);
        $Validation =  $Validation && ($this->BANCO_CC->Errors->Count() == 0);
        $Validation =  $Validation && ($this->N_CUENTA->Errors->Count() == 0);
        $Validation =  $Validation && ($this->CONTRAPARTE->Errors->Count() == 0);
        $Validation =  $Validation && ($this->EMAIL->Errors->Count() == 0);
        $Validation =  $Validation && ($this->h_datos_cc_cod->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-F2B64A7C
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->RUTIES->Errors->Count());
        $errors = ($errors || $this->NOMBRE_IES->Errors->Count());
        $errors = ($errors || $this->BANCO_CC->Errors->Count());
        $errors = ($errors || $this->N_CUENTA->Errors->Count());
        $errors = ($errors || $this->CONTRAPARTE->Errors->Count());
        $errors = ($errors || $this->EMAIL->Errors->Count());
        $errors = ($errors || $this->h_datos_cc_cod->Errors->Count());
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

//Operation Method @2-6D52D40C
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
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            }
        }
        $Redirect = "datos_cc.php" . "?" . CCGetQueryString("QueryString", array("ccsForm", "DATOS_CC_COD"));
        if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
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

//InsertRow Method @2-2B239008
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->RUTIES->SetValue($this->RUTIES->GetValue(true));
        $this->DataSource->NOMBRE_IES->SetValue($this->NOMBRE_IES->GetValue(true));
        $this->DataSource->BANCO_CC->SetValue($this->BANCO_CC->GetValue(true));
        $this->DataSource->N_CUENTA->SetValue($this->N_CUENTA->GetValue(true));
        $this->DataSource->CONTRAPARTE->SetValue($this->CONTRAPARTE->GetValue(true));
        $this->DataSource->EMAIL->SetValue($this->EMAIL->GetValue(true));
        $this->DataSource->h_datos_cc_cod->SetValue($this->h_datos_cc_cod->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @2-2F9BDFDC
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->RUTIES->SetValue($this->RUTIES->GetValue(true));
        $this->DataSource->NOMBRE_IES->SetValue($this->NOMBRE_IES->GetValue(true));
        $this->DataSource->BANCO_CC->SetValue($this->BANCO_CC->GetValue(true));
        $this->DataSource->N_CUENTA->SetValue($this->N_CUENTA->GetValue(true));
        $this->DataSource->CONTRAPARTE->SetValue($this->CONTRAPARTE->GetValue(true));
        $this->DataSource->EMAIL->SetValue($this->EMAIL->GetValue(true));
        $this->DataSource->h_datos_cc_cod->SetValue($this->h_datos_cc_cod->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @2-BA493A49
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
                if(!$this->FormSubmitted){
                    $this->RUTIES->SetValue($this->DataSource->RUTIES->GetValue());
                    $this->NOMBRE_IES->SetValue($this->DataSource->NOMBRE_IES->GetValue());
                    $this->BANCO_CC->SetValue($this->DataSource->BANCO_CC->GetValue());
                    $this->N_CUENTA->SetValue($this->DataSource->N_CUENTA->GetValue());
                    $this->CONTRAPARTE->SetValue($this->DataSource->CONTRAPARTE->GetValue());
                    $this->EMAIL->SetValue($this->DataSource->EMAIL->GetValue());
                    $this->h_datos_cc_cod->SetValue($this->DataSource->h_datos_cc_cod->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->RUTIES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->NOMBRE_IES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->BANCO_CC->Errors->ToString());
            $Error = ComposeStrings($Error, $this->N_CUENTA->Errors->ToString());
            $Error = ComposeStrings($Error, $this->CONTRAPARTE->Errors->ToString());
            $Error = ComposeStrings($Error, $this->EMAIL->Errors->ToString());
            $Error = ComposeStrings($Error, $this->h_datos_cc_cod->Errors->ToString());
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
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->RUTIES->Show();
        $this->NOMBRE_IES->Show();
        $this->BANCO_CC->Show();
        $this->N_CUENTA->Show();
        $this->CONTRAPARTE->Show();
        $this->EMAIL->Show();
        $this->h_datos_cc_cod->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End PI_DATOS_CC Class @2-FCB6E20C

class clsPI_DATOS_CCDataSource extends clsDBOracle_1 {  //PI_DATOS_CCDataSource Class @2-0970AB6A

//DataSource Variables @2-57D616A9
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $InsertParameters;
    var $UpdateParameters;
    var $wp;
    var $AllParametersSet;

    var $InsertFields = array();
    var $UpdateFields = array();

    // Datasource fields
    var $RUTIES;
    var $NOMBRE_IES;
    var $BANCO_CC;
    var $N_CUENTA;
    var $CONTRAPARTE;
    var $EMAIL;
    var $h_datos_cc_cod;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-22F46335
    function clsPI_DATOS_CCDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record PI_DATOS_CC/Error";
        $this->Initialize();
        $this->RUTIES = new clsField("RUTIES", ccsInteger, "");
        
        $this->NOMBRE_IES = new clsField("NOMBRE_IES", ccsText, "");
        
        $this->BANCO_CC = new clsField("BANCO_CC", ccsText, "");
        
        $this->N_CUENTA = new clsField("N_CUENTA", ccsText, "");
        
        $this->CONTRAPARTE = new clsField("CONTRAPARTE", ccsText, "");
        
        $this->EMAIL = new clsField("EMAIL", ccsText, "");
        
        $this->h_datos_cc_cod = new clsField("h_datos_cc_cod", ccsInteger, "");
        

        $this->InsertFields["RUTIES"] = array("Name" => "RUTIES", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["NOMBRE_IES"] = array("Name" => "NOMBRE_IES", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["BANCO_CC"] = array("Name" => "BANCO_CC", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["N_CUENTA"] = array("Name" => "N_CUENTA", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["CONTRAPARTE"] = array("Name" => "CONTRAPARTE", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["EMAIL"] = array("Name" => "EMAIL", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["DATOS_CC_COD"] = array("Name" => "DATOS_CC_COD", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["RUTIES"] = array("Name" => "RUTIES", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["NOMBRE_IES"] = array("Name" => "NOMBRE_IES", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["BANCO_CC"] = array("Name" => "BANCO_CC", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["N_CUENTA"] = array("Name" => "N_CUENTA", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["CONTRAPARTE"] = array("Name" => "CONTRAPARTE", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["EMAIL"] = array("Name" => "EMAIL", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["DATOS_CC_COD"] = array("Name" => "DATOS_CC_COD", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-F778D19A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlDATOS_CC_COD", ccsFloat, "", "", $this->Parameters["urlDATOS_CC_COD"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "DATOS_CC_COD", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-C117BB95
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM PI_DATOS_CC {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-C032A203
    function SetValues()
    {
        $this->RUTIES->SetDBValue(trim($this->f("RUTIES")));
        $this->NOMBRE_IES->SetDBValue($this->f("NOMBRE_IES"));
        $this->BANCO_CC->SetDBValue($this->f("BANCO_CC"));
        $this->N_CUENTA->SetDBValue($this->f("N_CUENTA"));
        $this->CONTRAPARTE->SetDBValue($this->f("CONTRAPARTE"));
        $this->EMAIL->SetDBValue($this->f("EMAIL"));
        $this->h_datos_cc_cod->SetDBValue(trim($this->f("DATOS_CC_COD")));
    }
//End SetValues Method

//Insert Method @2-7CBAD680
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["RUTIES"]["Value"] = $this->RUTIES->GetDBValue(true);
        $this->InsertFields["NOMBRE_IES"]["Value"] = $this->NOMBRE_IES->GetDBValue(true);
        $this->InsertFields["BANCO_CC"]["Value"] = $this->BANCO_CC->GetDBValue(true);
        $this->InsertFields["N_CUENTA"]["Value"] = $this->N_CUENTA->GetDBValue(true);
        $this->InsertFields["CONTRAPARTE"]["Value"] = $this->CONTRAPARTE->GetDBValue(true);
        $this->InsertFields["EMAIL"]["Value"] = $this->EMAIL->GetDBValue(true);
        $this->InsertFields["DATOS_CC_COD"]["Value"] = $this->h_datos_cc_cod->GetDBValue(true);
        $this->SQL = CCBuildInsert("PI_DATOS_CC", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @2-384F1B6D
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["RUTIES"]["Value"] = $this->RUTIES->GetDBValue(true);
        $this->UpdateFields["NOMBRE_IES"]["Value"] = $this->NOMBRE_IES->GetDBValue(true);
        $this->UpdateFields["BANCO_CC"]["Value"] = $this->BANCO_CC->GetDBValue(true);
        $this->UpdateFields["N_CUENTA"]["Value"] = $this->N_CUENTA->GetDBValue(true);
        $this->UpdateFields["CONTRAPARTE"]["Value"] = $this->CONTRAPARTE->GetDBValue(true);
        $this->UpdateFields["EMAIL"]["Value"] = $this->EMAIL->GetDBValue(true);
        $this->UpdateFields["DATOS_CC_COD"]["Value"] = $this->h_datos_cc_cod->GetDBValue(true);
        $this->SQL = CCBuildUpdate("PI_DATOS_CC", $this->UpdateFields, $this);
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

} //End PI_DATOS_CCDataSource Class @2-FCB6E20C

//Initialize Page @1-323649CC
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
$TemplateFileName = "datos_cc_record.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Authenticate User @1-5C03D13B
CCSecurityRedirect("A;C;D;H;K;S", "");
//End Authenticate User

//Include events file @1-DECAA047
include_once("./datos_cc_record_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-DD14C064
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$PI_DATOS_CC = & new clsRecordPI_DATOS_CC("", $MainPage);
$MainPage->PI_DATOS_CC = & $PI_DATOS_CC;
$PI_DATOS_CC->Initialize();

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

//Execute Components @1-521CF6C1
$PI_DATOS_CC->Operation();
//End Execute Components

//Go to destination page @1-BB3C4B76
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($PI_DATOS_CC);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-D825238B
$PI_DATOS_CC->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-8F6FD08F
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($PI_DATOS_CC);
unset($Tpl);
//End Unload Page


?>
