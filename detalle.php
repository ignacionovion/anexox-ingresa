<?php
//Include Common Files @1-88BE17D2
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "detalle.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsRecordVST_PI_REPORTES { //VST_PI_REPORTES Class @2-BF6F3DE6

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

//Class_Initialize Event @2-D77E306E
    function clsRecordVST_PI_REPORTES($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record VST_PI_REPORTES/Error";
        $this->DataSource = new clsVST_PI_REPORTESDataSource($this);
        $this->ds = & $this->DataSource;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "VST_PI_REPORTES";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_Cancel = & new clsButton("Button_Cancel", $Method, $this);
            $this->RUT = & new clsControl(ccsLabel, "RUT", $CCSLocales->GetText("RUT"), ccsInteger, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("RUT", $Method, NULL), $this);
            $this->RUTBCO = & new clsControl(ccsLabel, "RUTBCO", $CCSLocales->GetText("RUTBCO"), ccsInteger, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("RUTBCO", $Method, NULL), $this);
            $this->LICITACION = & new clsControl(ccsLabel, "LICITACION", $CCSLocales->GetText("LICITACION"), ccsFloat, "", CCGetRequestParam("LICITACION", $Method, NULL), $this);
            $this->OPERACION = & new clsControl(ccsLabel, "OPERACION", $CCSLocales->GetText("OPERACION"), ccsFloat, "", CCGetRequestParam("OPERACION", $Method, NULL), $this);
            $this->ARANCELSOLICITADO = & new clsControl(ccsLabel, "ARANCELSOLICITADO", $CCSLocales->GetText("ARANCELSOLICITADO"), ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("ARANCELSOLICITADO", $Method, NULL), $this);
            $this->MONTOCREDITO = & new clsControl(ccsLabel, "MONTOCREDITO", $CCSLocales->GetText("MONTOCREDITO"), ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("MONTOCREDITO", $Method, NULL), $this);
            $this->CREDITOUF = & new clsControl(ccsLabel, "CREDITOUF", $CCSLocales->GetText("CREDITOUF"), ccsFloat, "", CCGetRequestParam("CREDITOUF", $Method, NULL), $this);
            $this->MONTOSEGUROIES = & new clsControl(ccsLabel, "MONTOSEGUROIES", $CCSLocales->GetText("MONTOSEGUROIES"), ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("MONTOSEGUROIES", $Method, NULL), $this);
            $this->MONTOAPAGAR = & new clsControl(ccsLabel, "MONTOAPAGAR", $CCSLocales->GetText("MONTOAPAGAR"), ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("MONTOAPAGAR", $Method, NULL), $this);
            $this->MONTOOTRACTA = & new clsControl(ccsLabel, "MONTOOTRACTA", $CCSLocales->GetText("MONTOOTRACTA"), ccsFloat, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("MONTOOTRACTA", $Method, NULL), $this);
            $this->PLAZO = & new clsControl(ccsLabel, "PLAZO", $CCSLocales->GetText("PLAZO"), ccsFloat, "", CCGetRequestParam("PLAZO", $Method, NULL), $this);
            $this->TASA = & new clsControl(ccsLabel, "TASA", $CCSLocales->GetText("TASA"), ccsFloat, "", CCGetRequestParam("TASA", $Method, NULL), $this);
            $this->FECHACURSE = & new clsControl(ccsLabel, "FECHACURSE", $CCSLocales->GetText("FECHACURSE"), ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("FECHACURSE", $Method, NULL), $this);
            $this->RUTIES = & new clsControl(ccsLabel, "RUTIES", $CCSLocales->GetText("RUTIES"), ccsInteger, array(False, 0, Null, ".", False, "", "", 1, True, ""), CCGetRequestParam("RUTIES", $Method, NULL), $this);
            $this->IEST_NOMBRE_IES = & new clsControl(ccsLabel, "IEST_NOMBRE_IES", $CCSLocales->GetText("IEST_NOMBRE_IES"), ccsText, "", CCGetRequestParam("IEST_NOMBRE_IES", $Method, NULL), $this);
            $this->FECHA = & new clsControl(ccsLabel, "FECHA", $CCSLocales->GetText("FECHA"), ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("FECHA", $Method, NULL), $this);
            $this->TIPO_DESC = & new clsControl(ccsLabel, "TIPO_DESC", "TIPO_DESC", ccsText, "", CCGetRequestParam("TIPO_DESC", $Method, NULL), $this);
            $this->OBSERVACION = & new clsControl(ccsLabel, "OBSERVACION", "OBSERVACION", ccsText, "", CCGetRequestParam("OBSERVACION", $Method, NULL), $this);
            $this->h_tipo_cod = & new clsControl(ccsHidden, "h_tipo_cod", "h_tipo_cod", ccsInteger, "", CCGetRequestParam("h_tipo_cod", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @2-6C4D8845
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urlCARGA_COD"] = CCGetFromGet("CARGA_COD", NULL);
    }
//End Initialize Method

//Validate Method @2-8618DEBB
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->h_tipo_cod->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->h_tipo_cod->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @2-5A054791
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->RUT->Errors->Count());
        $errors = ($errors || $this->RUTBCO->Errors->Count());
        $errors = ($errors || $this->LICITACION->Errors->Count());
        $errors = ($errors || $this->OPERACION->Errors->Count());
        $errors = ($errors || $this->ARANCELSOLICITADO->Errors->Count());
        $errors = ($errors || $this->MONTOCREDITO->Errors->Count());
        $errors = ($errors || $this->CREDITOUF->Errors->Count());
        $errors = ($errors || $this->MONTOSEGUROIES->Errors->Count());
        $errors = ($errors || $this->MONTOAPAGAR->Errors->Count());
        $errors = ($errors || $this->MONTOOTRACTA->Errors->Count());
        $errors = ($errors || $this->PLAZO->Errors->Count());
        $errors = ($errors || $this->TASA->Errors->Count());
        $errors = ($errors || $this->FECHACURSE->Errors->Count());
        $errors = ($errors || $this->RUTIES->Errors->Count());
        $errors = ($errors || $this->IEST_NOMBRE_IES->Errors->Count());
        $errors = ($errors || $this->FECHA->Errors->Count());
        $errors = ($errors || $this->TIPO_DESC->Errors->Count());
        $errors = ($errors || $this->OBSERVACION->Errors->Count());
        $errors = ($errors || $this->h_tipo_cod->Errors->Count());
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

//Operation Method @2-69B69318
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
            $this->PressedButton = "Button_Cancel";
            if($this->Button_Cancel->Pressed) {
                $this->PressedButton = "Button_Cancel";
            }
        }
        $Redirect = "consulta_rut.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->PressedButton == "Button_Cancel") {
            if(!CCGetEvent($this->Button_Cancel->CCSEvents, "OnClick", $this->Button_Cancel)) {
                $Redirect = "";
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//Show Method @2-4C0D511A
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
                $this->RUT->SetValue($this->DataSource->RUT->GetValue());
                $this->RUTBCO->SetValue($this->DataSource->RUTBCO->GetValue());
                $this->LICITACION->SetValue($this->DataSource->LICITACION->GetValue());
                $this->OPERACION->SetValue($this->DataSource->OPERACION->GetValue());
                $this->ARANCELSOLICITADO->SetValue($this->DataSource->ARANCELSOLICITADO->GetValue());
                $this->MONTOCREDITO->SetValue($this->DataSource->MONTOCREDITO->GetValue());
                $this->CREDITOUF->SetValue($this->DataSource->CREDITOUF->GetValue());
                $this->MONTOSEGUROIES->SetValue($this->DataSource->MONTOSEGUROIES->GetValue());
                $this->MONTOAPAGAR->SetValue($this->DataSource->MONTOAPAGAR->GetValue());
                $this->MONTOOTRACTA->SetValue($this->DataSource->MONTOOTRACTA->GetValue());
                $this->PLAZO->SetValue($this->DataSource->PLAZO->GetValue());
                $this->TASA->SetValue($this->DataSource->TASA->GetValue());
                $this->FECHACURSE->SetValue($this->DataSource->FECHACURSE->GetValue());
                $this->RUTIES->SetValue($this->DataSource->RUTIES->GetValue());
                $this->IEST_NOMBRE_IES->SetValue($this->DataSource->IEST_NOMBRE_IES->GetValue());
                $this->FECHA->SetValue($this->DataSource->FECHA->GetValue());
                $this->TIPO_DESC->SetValue($this->DataSource->TIPO_DESC->GetValue());
                $this->OBSERVACION->SetValue($this->DataSource->OBSERVACION->GetValue());
                if(!$this->FormSubmitted){
                    $this->h_tipo_cod->SetValue($this->DataSource->h_tipo_cod->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->RUT->Errors->ToString());
            $Error = ComposeStrings($Error, $this->RUTBCO->Errors->ToString());
            $Error = ComposeStrings($Error, $this->LICITACION->Errors->ToString());
            $Error = ComposeStrings($Error, $this->OPERACION->Errors->ToString());
            $Error = ComposeStrings($Error, $this->ARANCELSOLICITADO->Errors->ToString());
            $Error = ComposeStrings($Error, $this->MONTOCREDITO->Errors->ToString());
            $Error = ComposeStrings($Error, $this->CREDITOUF->Errors->ToString());
            $Error = ComposeStrings($Error, $this->MONTOSEGUROIES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->MONTOAPAGAR->Errors->ToString());
            $Error = ComposeStrings($Error, $this->MONTOOTRACTA->Errors->ToString());
            $Error = ComposeStrings($Error, $this->PLAZO->Errors->ToString());
            $Error = ComposeStrings($Error, $this->TASA->Errors->ToString());
            $Error = ComposeStrings($Error, $this->FECHACURSE->Errors->ToString());
            $Error = ComposeStrings($Error, $this->RUTIES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->IEST_NOMBRE_IES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->FECHA->Errors->ToString());
            $Error = ComposeStrings($Error, $this->TIPO_DESC->Errors->ToString());
            $Error = ComposeStrings($Error, $this->OBSERVACION->Errors->ToString());
            $Error = ComposeStrings($Error, $this->h_tipo_cod->Errors->ToString());
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

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->Button_Cancel->Show();
        $this->RUT->Show();
        $this->RUTBCO->Show();
        $this->LICITACION->Show();
        $this->OPERACION->Show();
        $this->ARANCELSOLICITADO->Show();
        $this->MONTOCREDITO->Show();
        $this->CREDITOUF->Show();
        $this->MONTOSEGUROIES->Show();
        $this->MONTOAPAGAR->Show();
        $this->MONTOOTRACTA->Show();
        $this->PLAZO->Show();
        $this->TASA->Show();
        $this->FECHACURSE->Show();
        $this->RUTIES->Show();
        $this->IEST_NOMBRE_IES->Show();
        $this->FECHA->Show();
        $this->TIPO_DESC->Show();
        $this->OBSERVACION->Show();
        $this->h_tipo_cod->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End VST_PI_REPORTES Class @2-FCB6E20C

class clsVST_PI_REPORTESDataSource extends clsDBOracle_1 {  //VST_PI_REPORTESDataSource Class @2-C20BD3CB

//DataSource Variables @2-B24B6416
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $wp;
    var $AllParametersSet;


    // Datasource fields
    var $RUT;
    var $RUTBCO;
    var $LICITACION;
    var $OPERACION;
    var $ARANCELSOLICITADO;
    var $MONTOCREDITO;
    var $CREDITOUF;
    var $MONTOSEGUROIES;
    var $MONTOAPAGAR;
    var $MONTOOTRACTA;
    var $PLAZO;
    var $TASA;
    var $FECHACURSE;
    var $RUTIES;
    var $IEST_NOMBRE_IES;
    var $FECHA;
    var $TIPO_DESC;
    var $OBSERVACION;
    var $h_tipo_cod;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-3F55A4F2
    function clsVST_PI_REPORTESDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record VST_PI_REPORTES/Error";
        $this->Initialize();
        $this->RUT = new clsField("RUT", ccsInteger, "");
        
        $this->RUTBCO = new clsField("RUTBCO", ccsInteger, "");
        
        $this->LICITACION = new clsField("LICITACION", ccsFloat, "");
        
        $this->OPERACION = new clsField("OPERACION", ccsFloat, "");
        
        $this->ARANCELSOLICITADO = new clsField("ARANCELSOLICITADO", ccsFloat, "");
        
        $this->MONTOCREDITO = new clsField("MONTOCREDITO", ccsFloat, "");
        
        $this->CREDITOUF = new clsField("CREDITOUF", ccsFloat, "");
        
        $this->MONTOSEGUROIES = new clsField("MONTOSEGUROIES", ccsFloat, "");
        
        $this->MONTOAPAGAR = new clsField("MONTOAPAGAR", ccsFloat, "");
        
        $this->MONTOOTRACTA = new clsField("MONTOOTRACTA", ccsFloat, "");
        
        $this->PLAZO = new clsField("PLAZO", ccsFloat, "");
        
        $this->TASA = new clsField("TASA", ccsFloat, "");
        
        $this->FECHACURSE = new clsField("FECHACURSE", ccsDate, $this->DateFormat);
        
        $this->RUTIES = new clsField("RUTIES", ccsInteger, "");
        
        $this->IEST_NOMBRE_IES = new clsField("IEST_NOMBRE_IES", ccsText, "");
        
        $this->FECHA = new clsField("FECHA", ccsDate, $this->DateFormat);
        
        $this->TIPO_DESC = new clsField("TIPO_DESC", ccsText, "");
        
        $this->OBSERVACION = new clsField("OBSERVACION", ccsText, "");
        
        $this->h_tipo_cod = new clsField("h_tipo_cod", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//Prepare Method @2-E7D9FD0A
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlCARGA_COD", ccsFloat, "", "", $this->Parameters["urlCARGA_COD"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "CARGA_COD", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @2-9C659382
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM VST_PI_REPORTES {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @2-C007C015
    function SetValues()
    {
        $this->RUT->SetDBValue(trim($this->f("RUT")));
        $this->RUTBCO->SetDBValue(trim($this->f("RUTBCO")));
        $this->LICITACION->SetDBValue(trim($this->f("LICITACION")));
        $this->OPERACION->SetDBValue(trim($this->f("OPERACION")));
        $this->ARANCELSOLICITADO->SetDBValue(trim($this->f("ARANCELSOLICITADO")));
        $this->MONTOCREDITO->SetDBValue(trim($this->f("MONTOCREDITO")));
        $this->CREDITOUF->SetDBValue(trim($this->f("CREDITOUF")));
        $this->MONTOSEGUROIES->SetDBValue(trim($this->f("MONTOSEGUROIES")));
        $this->MONTOAPAGAR->SetDBValue(trim($this->f("MONTOAPAGAR")));
        $this->MONTOOTRACTA->SetDBValue(trim($this->f("MONTOOTRACTA")));
        $this->PLAZO->SetDBValue(trim($this->f("PLAZO")));
        $this->TASA->SetDBValue(trim($this->f("TASA")));
        $this->FECHACURSE->SetDBValue(trim($this->f("FECHACURSE")));
        $this->RUTIES->SetDBValue(trim($this->f("RUTIES")));
        $this->IEST_NOMBRE_IES->SetDBValue($this->f("IEST_NOMBRE_IES"));
        $this->FECHA->SetDBValue(trim($this->f("FECHA")));
        $this->TIPO_DESC->SetDBValue($this->f("TIPO_DESC"));
        $this->OBSERVACION->SetDBValue($this->f("OBSERVACION"));
        $this->h_tipo_cod->SetDBValue(trim($this->f("TIPO_COD")));
    }
//End SetValues Method

} //End VST_PI_REPORTESDataSource Class @2-FCB6E20C

//Initialize Page @1-E17DFB72
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
$TemplateFileName = "detalle.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Include events file @1-6655032E
include_once("./detalle_events.php");
//End Include events file

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-7918AA27
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$VST_PI_REPORTES = & new clsRecordVST_PI_REPORTES("", $MainPage);
$MainPage->VST_PI_REPORTES = & $VST_PI_REPORTES;
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

//Execute Components @1-E9C5FC46
$VST_PI_REPORTES->Operation();
//End Execute Components

//Go to destination page @1-302BF19A
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($VST_PI_REPORTES);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-1BCD21F7
$VST_PI_REPORTES->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-380C76AC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($VST_PI_REPORTES);
unset($Tpl);
//End Unload Page


?>
