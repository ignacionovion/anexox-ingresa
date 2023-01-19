<?php
//Include Common Files @1-17E27DC0
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "datos_cc.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridPI_DATOS_CC { //PI_DATOS_CC class @2-C33A5E23

//Variables @2-902AB26C

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
    var $Sorter_RUTIES;
    var $Sorter_NOMBRE_IES;
    var $Sorter_BANCO_CC;
    var $Sorter_N_CUENTA;
    var $Sorter_CONTRAPARTE;
    var $Sorter_EMAIL;
//End Variables

//Class_Initialize Event @2-08DD6B18
    function clsGridPI_DATOS_CC($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "PI_DATOS_CC";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid PI_DATOS_CC";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsPI_DATOS_CCDataSource($this);
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
        $this->SorterName = CCGetParam("PI_DATOS_CCOrder", "");
        $this->SorterDirection = CCGetParam("PI_DATOS_CCDir", "");

        $this->RUTIES = & new clsControl(ccsLabel, "RUTIES", "RUTIES", ccsFloat, "", CCGetRequestParam("RUTIES", ccsGet, NULL), $this);
        $this->NOMBRE_IES = & new clsControl(ccsLabel, "NOMBRE_IES", "NOMBRE_IES", ccsText, "", CCGetRequestParam("NOMBRE_IES", ccsGet, NULL), $this);
        $this->BANCO_CC = & new clsControl(ccsLabel, "BANCO_CC", "BANCO_CC", ccsText, "", CCGetRequestParam("BANCO_CC", ccsGet, NULL), $this);
        $this->N_CUENTA = & new clsControl(ccsLabel, "N_CUENTA", "N_CUENTA", ccsText, "", CCGetRequestParam("N_CUENTA", ccsGet, NULL), $this);
        $this->CONTRAPARTE = & new clsControl(ccsLabel, "CONTRAPARTE", "CONTRAPARTE", ccsText, "", CCGetRequestParam("CONTRAPARTE", ccsGet, NULL), $this);
        $this->EMAIL = & new clsControl(ccsLabel, "EMAIL", "EMAIL", ccsText, "", CCGetRequestParam("EMAIL", ccsGet, NULL), $this);
        $this->Link2 = & new clsControl(ccsLink, "Link2", "Link2", ccsText, "", CCGetRequestParam("Link2", ccsGet, NULL), $this);
        $this->Link2->Page = "datos_cc_record.php";
        $this->Sorter_RUTIES = & new clsSorter($this->ComponentName, "Sorter_RUTIES", $FileName, $this);
        $this->Sorter_NOMBRE_IES = & new clsSorter($this->ComponentName, "Sorter_NOMBRE_IES", $FileName, $this);
        $this->Sorter_BANCO_CC = & new clsSorter($this->ComponentName, "Sorter_BANCO_CC", $FileName, $this);
        $this->Sorter_N_CUENTA = & new clsSorter($this->ComponentName, "Sorter_N_CUENTA", $FileName, $this);
        $this->Sorter_CONTRAPARTE = & new clsSorter($this->ComponentName, "Sorter_CONTRAPARTE", $FileName, $this);
        $this->Sorter_EMAIL = & new clsSorter($this->ComponentName, "Sorter_EMAIL", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpSimple, $this);
        $this->Navigator->PageSizes = array("1", "5", "10", "25", "50");
        $this->Link1 = & new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet, NULL), $this);
        $this->Link1->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
        $this->Link1->Page = "datos_cc_record.php";
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

//Show Method @2-401F8511
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_RUTIES"] = CCGetFromGet("s_RUTIES", NULL);
        $this->DataSource->Parameters["urls_NOMBRE_IES"] = CCGetFromGet("s_NOMBRE_IES", NULL);

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
            $this->ControlsVisible["RUTIES"] = $this->RUTIES->Visible;
            $this->ControlsVisible["NOMBRE_IES"] = $this->NOMBRE_IES->Visible;
            $this->ControlsVisible["BANCO_CC"] = $this->BANCO_CC->Visible;
            $this->ControlsVisible["N_CUENTA"] = $this->N_CUENTA->Visible;
            $this->ControlsVisible["CONTRAPARTE"] = $this->CONTRAPARTE->Visible;
            $this->ControlsVisible["EMAIL"] = $this->EMAIL->Visible;
            $this->ControlsVisible["Link2"] = $this->Link2->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->RUTIES->SetValue($this->DataSource->RUTIES->GetValue());
                $this->NOMBRE_IES->SetValue($this->DataSource->NOMBRE_IES->GetValue());
                $this->BANCO_CC->SetValue($this->DataSource->BANCO_CC->GetValue());
                $this->N_CUENTA->SetValue($this->DataSource->N_CUENTA->GetValue());
                $this->CONTRAPARTE->SetValue($this->DataSource->CONTRAPARTE->GetValue());
                $this->EMAIL->SetValue($this->DataSource->EMAIL->GetValue());
                $this->Link2->Parameters = CCGetQueryString("QueryString", array("ccsForm"));
                $this->Link2->Parameters = CCAddParam($this->Link2->Parameters, "DATOS_CC_COD", $this->DataSource->f("DATOS_CC_COD"));
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->RUTIES->Show();
                $this->NOMBRE_IES->Show();
                $this->BANCO_CC->Show();
                $this->N_CUENTA->Show();
                $this->CONTRAPARTE->Show();
                $this->EMAIL->Show();
                $this->Link2->Show();
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
        $this->Sorter_RUTIES->Show();
        $this->Sorter_NOMBRE_IES->Show();
        $this->Sorter_BANCO_CC->Show();
        $this->Sorter_N_CUENTA->Show();
        $this->Sorter_CONTRAPARTE->Show();
        $this->Sorter_EMAIL->Show();
        $this->Navigator->Show();
        $this->Link1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-309090C5
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->RUTIES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->NOMBRE_IES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->BANCO_CC->Errors->ToString());
        $errors = ComposeStrings($errors, $this->N_CUENTA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CONTRAPARTE->Errors->ToString());
        $errors = ComposeStrings($errors, $this->EMAIL->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Link2->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End PI_DATOS_CC Class @2-FCB6E20C

class clsPI_DATOS_CCDataSource extends clsDBOracle_1 {  //PI_DATOS_CCDataSource Class @2-0970AB6A

//DataSource Variables @2-197738F1
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $RUTIES;
    var $NOMBRE_IES;
    var $BANCO_CC;
    var $N_CUENTA;
    var $CONTRAPARTE;
    var $EMAIL;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-1FE7595E
    function clsPI_DATOS_CCDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid PI_DATOS_CC";
        $this->Initialize();
        $this->RUTIES = new clsField("RUTIES", ccsFloat, "");
        
        $this->NOMBRE_IES = new clsField("NOMBRE_IES", ccsText, "");
        
        $this->BANCO_CC = new clsField("BANCO_CC", ccsText, "");
        
        $this->N_CUENTA = new clsField("N_CUENTA", ccsText, "");
        
        $this->CONTRAPARTE = new clsField("CONTRAPARTE", ccsText, "");
        
        $this->EMAIL = new clsField("EMAIL", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-05A9C9FC
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "NOMBRE_IES";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_RUTIES" => array("RUTIES", ""), 
            "Sorter_NOMBRE_IES" => array("NOMBRE_IES", ""), 
            "Sorter_BANCO_CC" => array("BANCO_CC", ""), 
            "Sorter_N_CUENTA" => array("N_CUENTA", ""), 
            "Sorter_CONTRAPARTE" => array("CONTRAPARTE", ""), 
            "Sorter_EMAIL" => array("EMAIL", "")));
    }
//End SetOrder Method

//Prepare Method @2-CE65F520
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_RUTIES", ccsFloat, "", "", $this->Parameters["urls_RUTIES"], "", false);
        $this->wp->AddParameter("2", "urls_NOMBRE_IES", ccsText, "", "", $this->Parameters["urls_NOMBRE_IES"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "RUTIES", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opContains, "NOMBRE_IES", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsText),false);
        $this->Where = $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]);
    }
//End Prepare Method

//Open Method @2-800C26FA
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM PI_DATOS_CC";
        $this->SQL = "SELECT * \n\n" .
        "FROM PI_DATOS_CC {SQL_Where} {SQL_OrderBy}";
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

//SetValues Method @2-4F8ABF8A
    function SetValues()
    {
        $this->RUTIES->SetDBValue(trim($this->f("RUTIES")));
        $this->NOMBRE_IES->SetDBValue($this->f("NOMBRE_IES"));
        $this->BANCO_CC->SetDBValue($this->f("BANCO_CC"));
        $this->N_CUENTA->SetDBValue($this->f("N_CUENTA"));
        $this->CONTRAPARTE->SetDBValue($this->f("CONTRAPARTE"));
        $this->EMAIL->SetDBValue($this->f("EMAIL"));
    }
//End SetValues Method

} //End PI_DATOS_CCDataSource Class @2-FCB6E20C

class clsRecordPI_DATOS_CCSearch { //PI_DATOS_CCSearch Class @3-F0EF2BEE

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

//Class_Initialize Event @3-FF5BE0AE
    function clsRecordPI_DATOS_CCSearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record PI_DATOS_CCSearch/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "PI_DATOS_CCSearch";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_RUTIES = & new clsControl(ccsTextBox, "s_RUTIES", "s_RUTIES", ccsFloat, "", CCGetRequestParam("s_RUTIES", $Method, NULL), $this);
            $this->s_NOMBRE_IES = & new clsControl(ccsTextBox, "s_NOMBRE_IES", "s_NOMBRE_IES", ccsText, "", CCGetRequestParam("s_NOMBRE_IES", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @3-02B95AE8
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_RUTIES->Validate() && $Validation);
        $Validation = ($this->s_NOMBRE_IES->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_RUTIES->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_NOMBRE_IES->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-56C776ED
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_RUTIES->Errors->Count());
        $errors = ($errors || $this->s_NOMBRE_IES->Errors->Count());
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

//Operation Method @3-CE7DE4E5
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
        $Redirect = "datos_cc.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "datos_cc.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-2569A5C7
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
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->s_RUTIES->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_NOMBRE_IES->Errors->ToString());
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
        $this->s_RUTIES->Show();
        $this->s_NOMBRE_IES->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End PI_DATOS_CCSearch Class @3-FCB6E20C

//Initialize Page @1-838C72DA
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
$TemplateFileName = "datos_cc.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
//End Initialize Page

//Authenticate User @1-5C03D13B
CCSecurityRedirect("A;C;D;H;K;S", "");
//End Authenticate User

//Include events file @1-3A55C522
include_once("./datos_cc_events.php");
//End Include events file

//BeforeInitialize Binding @1-17AC9191
$CCSEvents["BeforeInitialize"] = "Page_BeforeInitialize";
//End BeforeInitialize Binding

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-806544F1
$DBOracle_1 = new clsDBOracle_1();
$MainPage->Connections["Oracle_1"] = & $DBOracle_1;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$PI_DATOS_CC = & new clsGridPI_DATOS_CC("", $MainPage);
$PI_DATOS_CCSearch = & new clsRecordPI_DATOS_CCSearch("", $MainPage);
$MainPage->PI_DATOS_CC = & $PI_DATOS_CC;
$MainPage->PI_DATOS_CCSearch = & $PI_DATOS_CCSearch;
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

//Execute Components @1-46915A1E
$PI_DATOS_CCSearch->Operation();
//End Execute Components

//Go to destination page @1-7C05F3B5
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBOracle_1->close();
    header("Location: " . $Redirect);
    unset($PI_DATOS_CC);
    unset($PI_DATOS_CCSearch);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-190328BF
$PI_DATOS_CC->Show();
$PI_DATOS_CCSearch->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $CCSLocales->GetFormatInfo("Encoding"));
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-597AE855
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBOracle_1->close();
unset($PI_DATOS_CC);
unset($PI_DATOS_CCSearch);
unset($Tpl);
//End Unload Page


?>
