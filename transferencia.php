<?php
//Include Common Files @1-2816F7B6
ERROR_REPORTING(1);
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "transferencia.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files



class clsRecordPI_TRANSFERENCIAS { //PI_TRANSFERENCIAS Class @10-765634DE

//Variables @10-D6FF3E86

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

//Class_Initialize Event @10-FD86209D
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
            $this->FormEnctype = "multipart/form-data";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Update = & new clsButton("Button_Update", $Method, $this);
            $this->MONTO_TOTAL = & new clsControl(ccsTextBox, "MONTO_TOTAL", "Monto total", ccsFloat, "", CCGetRequestParam("MONTO_TOTAL", $Method, NULL), $this);
            $this->MONTO_TOTAL->Required = true;
            $this->ESTADO_COD = & new clsControl(ccsHidden, "ESTADO_COD", $CCSLocales->GetText("ESTADO_COD"), ccsInteger, "", CCGetRequestParam("ESTADO_COD", $Method, NULL), $this);
            $this->ESTADO_COD->Required = true;
            $this->FileUpload1 = & new clsFileUpload("FileUpload1", "Comprobante Transferencia", "temporal/", "documentos/", "*.ZIP;*.RAR;*.PDF;*.JPG;*.GIF", "", 10485760, $this);
            $this->FileUpload1->Required = true;
			
			//CB
			$this->FECHA_PAGO = & new clsControl(ccsTextBox, "FECHA_PAGO", "Fecha del Pago", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("FECHA_PAGO", $Method, NULL), $this);
			$this->FECHA_PAGO->Required = true;
			$this->DatePicker_FECHA_PAGO = & new clsDatePicker("DatePicker_FECHA_PAGO", "PI_TRANSFERENCIAS", "FECHA_PAGO", $this);

		
            $this->Button1 = & new clsButton("Button1", $Method, $this);
            if(!$this->FormSubmitted) {
                if(!is_array($this->ESTADO_COD->Value) && !strlen($this->ESTADO_COD->Value) && $this->ESTADO_COD->Value !== false)
                    $this->ESTADO_COD->SetText(2);
            }
        }
    }
//End Class_Initialize Event

//Initialize Method @10-90EC5F9E
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlTRANSFERENCIA_COD"] = CCGetFromGet("TRANSFERENCIA_COD", NULL);
    }
//End Initialize Method

//Validate Method @10-BC7E19DA
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->MONTO_TOTAL->Validate() && $Validation);
        $Validation = ($this->ESTADO_COD->Validate() && $Validation);
		$Validation = ($this->FECHA_PAGO->Validate() && $Validation);
        $Validation = ($this->FileUpload1->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->MONTO_TOTAL->Errors->Count() == 0);
        $Validation =  $Validation && ($this->ESTADO_COD->Errors->Count() == 0);
        $Validation =  $Validation && ($this->FileUpload1->Errors->Count() == 0);
		$Validation =  $Validation && ($this->FECHA_PAGO->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @10-FAE15838
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->MONTO_TOTAL->Errors->Count());
        $errors = ($errors || $this->ESTADO_COD->Errors->Count());
        $errors = ($errors || $this->FileUpload1->Errors->Count());
		$errors = ($errors || $this->FECHA_PAGO->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @10-ED598703
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

//Operation Method @10-8FA3EF38
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

        $this->FileUpload1->Upload();

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Button_Update" : "Button1";
            if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button1->Pressed) {
                $this->PressedButton = "Button1";
            }
        }
        $Redirect = "index.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button1") {
            $Redirect = "index.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
            if(!CCGetEvent($this->Button1->CCSEvents, "OnClick", $this->Button1)) {
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

//UpdateRow Method @10-25921D45
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->MONTO_TOTAL->SetValue($this->MONTO_TOTAL->GetValue(true));
        $this->DataSource->ESTADO_COD->SetValue($this->ESTADO_COD->GetValue(true));
        $this->DataSource->FileUpload1->SetValue($this->FileUpload1->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        if($this->DataSource->Errors->Count() == 0) {
            $this->FileUpload1->Move();
        }
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//Show Method @10-67FE6CB1
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
                    $this->MONTO_TOTAL->SetValue($this->DataSource->MONTO_TOTAL->GetValue());
                    $this->ESTADO_COD->SetValue($this->DataSource->ESTADO_COD->GetValue());
                    $this->FileUpload1->SetValue($this->DataSource->FileUpload1->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->MONTO_TOTAL->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ESTADO_COD->Errors->ToString());
            $Error = ComposeStrings($Error, $this->FileUpload1->Errors->ToString());
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
        $this->MONTO_TOTAL->Show();
        $this->ESTADO_COD->Show();
        $this->FileUpload1->Show();
        $this->Button1->Show();
		$this->FECHA_PAGO->Show();
		$this->DatePicker_FECHA_PAGO->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End PI_TRANSFERENCIAS Class @10-FCB6E20C

class clsPI_TRANSFERENCIASDataSource extends clsDBOracle_1 {  //PI_TRANSFERENCIASDataSource Class @10-F1BBB850

//DataSource Variables @10-3EB51A3C
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
    var $MONTO_TOTAL;
    var $ESTADO_COD;
    var $FileUpload1;
//End DataSource Variables

//DataSourceClass_Initialize Event @10-22C5002F
    function clsPI_TRANSFERENCIASDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record PI_TRANSFERENCIAS/Error";
        $this->Initialize();
        $this->MONTO_TOTAL = new clsField("MONTO_TOTAL", ccsFloat, "");
        
        $this->ESTADO_COD = new clsField("ESTADO_COD", ccsInteger, "");
        
        $this->FileUpload1 = new clsField("FileUpload1", ccsText, "");
        

        $this->UpdateFields["MONTO_TOTAL"] = array("Name" => "MONTO_TOTAL", "Value" => "", "DataType" => ccsFloat, "OmitIfEmpty" => 1);
        $this->UpdateFields["ESTADO_COD"] = array("Name" => "ESTADO_COD", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["COMPROBANTE"] = array("Name" => "COMPROBANTE", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @10-BFBC8920
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

//Open Method @10-B63F820F
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

//SetValues Method @10-F23CC676
    function SetValues()
    {
        $this->MONTO_TOTAL->SetDBValue(trim($this->f("MONTO_TOTAL")));
        $this->ESTADO_COD->SetDBValue(trim($this->f("ESTADO_COD")));
        $this->FileUpload1->SetDBValue($this->f("COMPROBANTE"));
    }
//End SetValues Method

//Update Method @10-EF4B5015
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["MONTO_TOTAL"]["Value"] = $this->MONTO_TOTAL->GetDBValue(true);
        $this->UpdateFields["ESTADO_COD"]["Value"] = $this->ESTADO_COD->GetDBValue(true);
        $this->UpdateFields["COMPROBANTE"]["Value"] = $this->FileUpload1->GetDBValue(true);
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

} //End PI_TRANSFERENCIASDataSource Class @10-FCB6E20C

//Initialize Page @1-24937470
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
$TemplateFileName = "transferencia.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Authenticate User @1-A81ACA16
CCSecurityRedirect("H;S", "");
//End Authenticate User

//Include events file @1-BD027D6B
include_once("./transferencia_events.php");
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
