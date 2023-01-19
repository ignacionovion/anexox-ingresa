<?php
//Include Common Files @1-95A134DE
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "reportes_resp.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

class clsGridVST_PI_REPORTES { //VST_PI_REPORTES class @2-1804D29B

//Variables @2-8D734FEB

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
    var $Sorter_CARGA_COD;
    var $Sorter_TRANSFERENCIA_COD;
    var $Sorter_RUT;
    var $Sorter_RUTBCO;
    var $Sorter_IEST_NOMBRE_IES;
    var $Sorter_IESN_RUT;
    var $Sorter_NOMBRE_BANCO;
    var $Sorter_BANCN_COD;
    var $Sorter_OPERACION;
    var $Sorter_LICITACION;
    var $Sorter_MONTOCREDITO;
    var $Sorter_FECHA;
    var $Sorter_ESTADO_DESC;
    var $Sorter_ARANCELSOLICITADO;
    var $Sorter_CREDITOUF;
    var $Sorter_MONTOSEGUROIES;
    var $Sorter_MONTOAPAGAR;
    var $Sorter_MONTOOTRACTA;
    var $Sorter_PLAZO;
    var $Sorter_TASA;
    var $Sorter_FECHACURSE;
    var $Sorter_RUTIES;
    var $Sorter_PAGO_FINAL;
    var $Sorter_MONTO_CREDITO;
    var $Sorter_PAGO_TOTAL;
//End Variables

//Class_Initialize Event @2-4D181943
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
            $this->PageSize = 10;
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

        $this->CARGA_COD = & new clsControl(ccsLabel, "CARGA_COD", "CARGA_COD", ccsFloat, "", CCGetRequestParam("CARGA_COD", ccsGet, NULL), $this);
        $this->TRANSFERENCIA_COD = & new clsControl(ccsLabel, "TRANSFERENCIA_COD", "TRANSFERENCIA_COD", ccsFloat, "", CCGetRequestParam("TRANSFERENCIA_COD", ccsGet, NULL), $this);
        $this->RUT = & new clsControl(ccsLabel, "RUT", "RUT", ccsFloat, "", CCGetRequestParam("RUT", ccsGet, NULL), $this);
        $this->RUTBCO = & new clsControl(ccsLabel, "RUTBCO", "RUTBCO", ccsFloat, "", CCGetRequestParam("RUTBCO", ccsGet, NULL), $this);
        $this->IEST_NOMBRE_IES = & new clsControl(ccsLabel, "IEST_NOMBRE_IES", "IEST_NOMBRE_IES", ccsText, "", CCGetRequestParam("IEST_NOMBRE_IES", ccsGet, NULL), $this);
        $this->IESN_RUT = & new clsControl(ccsLabel, "IESN_RUT", "IESN_RUT", ccsFloat, "", CCGetRequestParam("IESN_RUT", ccsGet, NULL), $this);
        $this->NOMBRE_BANCO = & new clsControl(ccsLabel, "NOMBRE_BANCO", "NOMBRE_BANCO", ccsText, "", CCGetRequestParam("NOMBRE_BANCO", ccsGet, NULL), $this);
        $this->BANCN_COD = & new clsControl(ccsLabel, "BANCN_COD", "BANCN_COD", ccsFloat, "", CCGetRequestParam("BANCN_COD", ccsGet, NULL), $this);
        $this->OPERACION = & new clsControl(ccsLabel, "OPERACION", "OPERACION", ccsFloat, "", CCGetRequestParam("OPERACION", ccsGet, NULL), $this);
        $this->LICITACION = & new clsControl(ccsLabel, "LICITACION", "LICITACION", ccsFloat, "", CCGetRequestParam("LICITACION", ccsGet, NULL), $this);
        $this->MONTOCREDITO = & new clsControl(ccsLabel, "MONTOCREDITO", "MONTOCREDITO", ccsFloat, "", CCGetRequestParam("MONTOCREDITO", ccsGet, NULL), $this);
        $this->FECHA = & new clsControl(ccsLabel, "FECHA", "FECHA", ccsDate, $DefaultDateFormat, CCGetRequestParam("FECHA", ccsGet, NULL), $this);
        $this->ESTADO_DESC = & new clsControl(ccsLabel, "ESTADO_DESC", "ESTADO_DESC", ccsText, "", CCGetRequestParam("ESTADO_DESC", ccsGet, NULL), $this);
        $this->ARANCELSOLICITADO = & new clsControl(ccsLabel, "ARANCELSOLICITADO", "ARANCELSOLICITADO", ccsFloat, "", CCGetRequestParam("ARANCELSOLICITADO", ccsGet, NULL), $this);
        $this->CREDITOUF = & new clsControl(ccsLabel, "CREDITOUF", "CREDITOUF", ccsFloat, "", CCGetRequestParam("CREDITOUF", ccsGet, NULL), $this);
        $this->MONTOSEGUROIES = & new clsControl(ccsLabel, "MONTOSEGUROIES", "MONTOSEGUROIES", ccsFloat, "", CCGetRequestParam("MONTOSEGUROIES", ccsGet, NULL), $this);
        $this->MONTOAPAGAR = & new clsControl(ccsLabel, "MONTOAPAGAR", "MONTOAPAGAR", ccsFloat, "", CCGetRequestParam("MONTOAPAGAR", ccsGet, NULL), $this);
        $this->MONTOOTRACTA = & new clsControl(ccsLabel, "MONTOOTRACTA", "MONTOOTRACTA", ccsFloat, "", CCGetRequestParam("MONTOOTRACTA", ccsGet, NULL), $this);
        $this->PLAZO = & new clsControl(ccsLabel, "PLAZO", "PLAZO", ccsFloat, "", CCGetRequestParam("PLAZO", ccsGet, NULL), $this);
        $this->TASA = & new clsControl(ccsLabel, "TASA", "TASA", ccsFloat, "", CCGetRequestParam("TASA", ccsGet, NULL), $this);
        $this->FECHACURSE = & new clsControl(ccsLabel, "FECHACURSE", "FECHACURSE", ccsDate, $DefaultDateFormat, CCGetRequestParam("FECHACURSE", ccsGet, NULL), $this);
        $this->RUTIES = & new clsControl(ccsLabel, "RUTIES", "RUTIES", ccsFloat, "", CCGetRequestParam("RUTIES", ccsGet, NULL), $this);
        $this->PAGO_FINAL = & new clsControl(ccsLabel, "PAGO_FINAL", "PAGO_FINAL", ccsFloat, "", CCGetRequestParam("PAGO_FINAL", ccsGet, NULL), $this);
        $this->MONTO_CREDITO = & new clsControl(ccsLabel, "MONTO_CREDITO", "MONTO_CREDITO", ccsFloat, "", CCGetRequestParam("MONTO_CREDITO", ccsGet, NULL), $this);
        $this->PAGO_TOTAL = & new clsControl(ccsLabel, "PAGO_TOTAL", "PAGO_TOTAL", ccsFloat, "", CCGetRequestParam("PAGO_TOTAL", ccsGet, NULL), $this);
        $this->Sorter_CARGA_COD = & new clsSorter($this->ComponentName, "Sorter_CARGA_COD", $FileName, $this);
        $this->Sorter_TRANSFERENCIA_COD = & new clsSorter($this->ComponentName, "Sorter_TRANSFERENCIA_COD", $FileName, $this);
        $this->Sorter_RUT = & new clsSorter($this->ComponentName, "Sorter_RUT", $FileName, $this);
        $this->Sorter_RUTBCO = & new clsSorter($this->ComponentName, "Sorter_RUTBCO", $FileName, $this);
        $this->Sorter_IEST_NOMBRE_IES = & new clsSorter($this->ComponentName, "Sorter_IEST_NOMBRE_IES", $FileName, $this);
        $this->Sorter_IESN_RUT = & new clsSorter($this->ComponentName, "Sorter_IESN_RUT", $FileName, $this);
        $this->Sorter_NOMBRE_BANCO = & new clsSorter($this->ComponentName, "Sorter_NOMBRE_BANCO", $FileName, $this);
        $this->Sorter_BANCN_COD = & new clsSorter($this->ComponentName, "Sorter_BANCN_COD", $FileName, $this);
        $this->Sorter_OPERACION = & new clsSorter($this->ComponentName, "Sorter_OPERACION", $FileName, $this);
        $this->Sorter_LICITACION = & new clsSorter($this->ComponentName, "Sorter_LICITACION", $FileName, $this);
        $this->Sorter_MONTOCREDITO = & new clsSorter($this->ComponentName, "Sorter_MONTOCREDITO", $FileName, $this);
        $this->Sorter_FECHA = & new clsSorter($this->ComponentName, "Sorter_FECHA", $FileName, $this);
        $this->Sorter_ESTADO_DESC = & new clsSorter($this->ComponentName, "Sorter_ESTADO_DESC", $FileName, $this);
        $this->Sorter_ARANCELSOLICITADO = & new clsSorter($this->ComponentName, "Sorter_ARANCELSOLICITADO", $FileName, $this);
        $this->Sorter_CREDITOUF = & new clsSorter($this->ComponentName, "Sorter_CREDITOUF", $FileName, $this);
        $this->Sorter_MONTOSEGUROIES = & new clsSorter($this->ComponentName, "Sorter_MONTOSEGUROIES", $FileName, $this);
        $this->Sorter_MONTOAPAGAR = & new clsSorter($this->ComponentName, "Sorter_MONTOAPAGAR", $FileName, $this);
        $this->Sorter_MONTOOTRACTA = & new clsSorter($this->ComponentName, "Sorter_MONTOOTRACTA", $FileName, $this);
        $this->Sorter_PLAZO = & new clsSorter($this->ComponentName, "Sorter_PLAZO", $FileName, $this);
        $this->Sorter_TASA = & new clsSorter($this->ComponentName, "Sorter_TASA", $FileName, $this);
        $this->Sorter_FECHACURSE = & new clsSorter($this->ComponentName, "Sorter_FECHACURSE", $FileName, $this);
        $this->Sorter_RUTIES = & new clsSorter($this->ComponentName, "Sorter_RUTIES", $FileName, $this);
        $this->Sorter_PAGO_FINAL = & new clsSorter($this->ComponentName, "Sorter_PAGO_FINAL", $FileName, $this);
        $this->Sorter_MONTO_CREDITO = & new clsSorter($this->ComponentName, "Sorter_MONTO_CREDITO", $FileName, $this);
        $this->Sorter_PAGO_TOTAL = & new clsSorter($this->ComponentName, "Sorter_PAGO_TOTAL", $FileName, $this);
        $this->Navigator = & new clsNavigator($this->ComponentName, "Navigator", $FileName, 10, tpCentered, $this);
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

//Show Method @2-3120B1FB
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urls_RUT"] = CCGetFromGet("s_RUT", NULL);
        $this->DataSource->Parameters["urls_LICITACION"] = CCGetFromGet("s_LICITACION", NULL);
        $this->DataSource->Parameters["urls_OPERACION"] = CCGetFromGet("s_OPERACION", NULL);
        $this->DataSource->Parameters["urls_IESN_RUT"] = CCGetFromGet("s_IESN_RUT", NULL);
        $this->DataSource->Parameters["urls_RUTBCO"] = CCGetFromGet("s_RUTBCO", NULL);
        $this->DataSource->Parameters["urls_PAGO_TOTAL"] = CCGetFromGet("s_PAGO_TOTAL", NULL);
        $this->DataSource->Parameters["urls_FECHA"] = CCGetFromGet("s_FECHA", NULL);

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
            $this->ControlsVisible["CARGA_COD"] = $this->CARGA_COD->Visible;
            $this->ControlsVisible["TRANSFERENCIA_COD"] = $this->TRANSFERENCIA_COD->Visible;
            $this->ControlsVisible["RUT"] = $this->RUT->Visible;
            $this->ControlsVisible["RUTBCO"] = $this->RUTBCO->Visible;
            $this->ControlsVisible["IEST_NOMBRE_IES"] = $this->IEST_NOMBRE_IES->Visible;
            $this->ControlsVisible["IESN_RUT"] = $this->IESN_RUT->Visible;
            $this->ControlsVisible["NOMBRE_BANCO"] = $this->NOMBRE_BANCO->Visible;
            $this->ControlsVisible["BANCN_COD"] = $this->BANCN_COD->Visible;
            $this->ControlsVisible["OPERACION"] = $this->OPERACION->Visible;
            $this->ControlsVisible["LICITACION"] = $this->LICITACION->Visible;
            $this->ControlsVisible["MONTOCREDITO"] = $this->MONTOCREDITO->Visible;
            $this->ControlsVisible["FECHA"] = $this->FECHA->Visible;
            $this->ControlsVisible["ESTADO_DESC"] = $this->ESTADO_DESC->Visible;
            $this->ControlsVisible["ARANCELSOLICITADO"] = $this->ARANCELSOLICITADO->Visible;
            $this->ControlsVisible["CREDITOUF"] = $this->CREDITOUF->Visible;
            $this->ControlsVisible["MONTOSEGUROIES"] = $this->MONTOSEGUROIES->Visible;
            $this->ControlsVisible["MONTOAPAGAR"] = $this->MONTOAPAGAR->Visible;
            $this->ControlsVisible["MONTOOTRACTA"] = $this->MONTOOTRACTA->Visible;
            $this->ControlsVisible["PLAZO"] = $this->PLAZO->Visible;
            $this->ControlsVisible["TASA"] = $this->TASA->Visible;
            $this->ControlsVisible["FECHACURSE"] = $this->FECHACURSE->Visible;
            $this->ControlsVisible["RUTIES"] = $this->RUTIES->Visible;
            $this->ControlsVisible["PAGO_FINAL"] = $this->PAGO_FINAL->Visible;
            $this->ControlsVisible["MONTO_CREDITO"] = $this->MONTO_CREDITO->Visible;
            $this->ControlsVisible["PAGO_TOTAL"] = $this->PAGO_TOTAL->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->CARGA_COD->SetValue($this->DataSource->CARGA_COD->GetValue());
                $this->TRANSFERENCIA_COD->SetValue($this->DataSource->TRANSFERENCIA_COD->GetValue());
                $this->RUT->SetValue($this->DataSource->RUT->GetValue());
                $this->RUTBCO->SetValue($this->DataSource->RUTBCO->GetValue());
                $this->IEST_NOMBRE_IES->SetValue($this->DataSource->IEST_NOMBRE_IES->GetValue());
                $this->IESN_RUT->SetValue($this->DataSource->IESN_RUT->GetValue());
                $this->NOMBRE_BANCO->SetValue($this->DataSource->NOMBRE_BANCO->GetValue());
                $this->BANCN_COD->SetValue($this->DataSource->BANCN_COD->GetValue());
                $this->OPERACION->SetValue($this->DataSource->OPERACION->GetValue());
                $this->LICITACION->SetValue($this->DataSource->LICITACION->GetValue());
                $this->MONTOCREDITO->SetValue($this->DataSource->MONTOCREDITO->GetValue());
                $this->FECHA->SetValue($this->DataSource->FECHA->GetValue());
                $this->ESTADO_DESC->SetValue($this->DataSource->ESTADO_DESC->GetValue());
                $this->ARANCELSOLICITADO->SetValue($this->DataSource->ARANCELSOLICITADO->GetValue());
                $this->CREDITOUF->SetValue($this->DataSource->CREDITOUF->GetValue());
                $this->MONTOSEGUROIES->SetValue($this->DataSource->MONTOSEGUROIES->GetValue());
                $this->MONTOAPAGAR->SetValue($this->DataSource->MONTOAPAGAR->GetValue());
                $this->MONTOOTRACTA->SetValue($this->DataSource->MONTOOTRACTA->GetValue());
                $this->PLAZO->SetValue($this->DataSource->PLAZO->GetValue());
                $this->TASA->SetValue($this->DataSource->TASA->GetValue());
                $this->FECHACURSE->SetValue($this->DataSource->FECHACURSE->GetValue());
                $this->RUTIES->SetValue($this->DataSource->RUTIES->GetValue());
                $this->PAGO_FINAL->SetValue($this->DataSource->PAGO_FINAL->GetValue());
                $this->MONTO_CREDITO->SetValue($this->DataSource->MONTO_CREDITO->GetValue());
                $this->PAGO_TOTAL->SetValue($this->DataSource->PAGO_TOTAL->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->CARGA_COD->Show();
                $this->TRANSFERENCIA_COD->Show();
                $this->RUT->Show();
                $this->RUTBCO->Show();
                $this->IEST_NOMBRE_IES->Show();
                $this->IESN_RUT->Show();
                $this->NOMBRE_BANCO->Show();
                $this->BANCN_COD->Show();
                $this->OPERACION->Show();
                $this->LICITACION->Show();
                $this->MONTOCREDITO->Show();
                $this->FECHA->Show();
                $this->ESTADO_DESC->Show();
                $this->ARANCELSOLICITADO->Show();
                $this->CREDITOUF->Show();
                $this->MONTOSEGUROIES->Show();
                $this->MONTOAPAGAR->Show();
                $this->MONTOOTRACTA->Show();
                $this->PLAZO->Show();
                $this->TASA->Show();
                $this->FECHACURSE->Show();
                $this->RUTIES->Show();
                $this->PAGO_FINAL->Show();
                $this->MONTO_CREDITO->Show();
                $this->PAGO_TOTAL->Show();
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
        $this->Sorter_CARGA_COD->Show();
        $this->Sorter_TRANSFERENCIA_COD->Show();
        $this->Sorter_RUT->Show();
        $this->Sorter_RUTBCO->Show();
        $this->Sorter_IEST_NOMBRE_IES->Show();
        $this->Sorter_IESN_RUT->Show();
        $this->Sorter_NOMBRE_BANCO->Show();
        $this->Sorter_BANCN_COD->Show();
        $this->Sorter_OPERACION->Show();
        $this->Sorter_LICITACION->Show();
        $this->Sorter_MONTOCREDITO->Show();
        $this->Sorter_FECHA->Show();
        $this->Sorter_ESTADO_DESC->Show();
        $this->Sorter_ARANCELSOLICITADO->Show();
        $this->Sorter_CREDITOUF->Show();
        $this->Sorter_MONTOSEGUROIES->Show();
        $this->Sorter_MONTOAPAGAR->Show();
        $this->Sorter_MONTOOTRACTA->Show();
        $this->Sorter_PLAZO->Show();
        $this->Sorter_TASA->Show();
        $this->Sorter_FECHACURSE->Show();
        $this->Sorter_RUTIES->Show();
        $this->Sorter_PAGO_FINAL->Show();
        $this->Sorter_MONTO_CREDITO->Show();
        $this->Sorter_PAGO_TOTAL->Show();
        $this->Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @2-8F9E3234
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->CARGA_COD->Errors->ToString());
        $errors = ComposeStrings($errors, $this->TRANSFERENCIA_COD->Errors->ToString());
        $errors = ComposeStrings($errors, $this->RUT->Errors->ToString());
        $errors = ComposeStrings($errors, $this->RUTBCO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IEST_NOMBRE_IES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->IESN_RUT->Errors->ToString());
        $errors = ComposeStrings($errors, $this->NOMBRE_BANCO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->BANCN_COD->Errors->ToString());
        $errors = ComposeStrings($errors, $this->OPERACION->Errors->ToString());
        $errors = ComposeStrings($errors, $this->LICITACION->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTOCREDITO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->FECHA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ESTADO_DESC->Errors->ToString());
        $errors = ComposeStrings($errors, $this->ARANCELSOLICITADO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->CREDITOUF->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTOSEGUROIES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTOAPAGAR->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTOOTRACTA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->PLAZO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->TASA->Errors->ToString());
        $errors = ComposeStrings($errors, $this->FECHACURSE->Errors->ToString());
        $errors = ComposeStrings($errors, $this->RUTIES->Errors->ToString());
        $errors = ComposeStrings($errors, $this->PAGO_FINAL->Errors->ToString());
        $errors = ComposeStrings($errors, $this->MONTO_CREDITO->Errors->ToString());
        $errors = ComposeStrings($errors, $this->PAGO_TOTAL->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End VST_PI_REPORTES Class @2-FCB6E20C

class clsVST_PI_REPORTESDataSource extends clsDBOracle_1 {  //VST_PI_REPORTESDataSource Class @2-C20BD3CB

//DataSource Variables @2-3211D7B5
    var $Parent = "";
    var $CCSEvents = "";
    var $CCSEventResult;
    var $ErrorBlock;
    var $CmdExecution;

    var $CountSQL;
    var $wp;


    // Datasource fields
    var $CARGA_COD;
    var $TRANSFERENCIA_COD;
    var $RUT;
    var $RUTBCO;
    var $IEST_NOMBRE_IES;
    var $IESN_RUT;
    var $NOMBRE_BANCO;
    var $BANCN_COD;
    var $OPERACION;
    var $LICITACION;
    var $MONTOCREDITO;
    var $FECHA;
    var $ESTADO_DESC;
    var $ARANCELSOLICITADO;
    var $CREDITOUF;
    var $MONTOSEGUROIES;
    var $MONTOAPAGAR;
    var $MONTOOTRACTA;
    var $PLAZO;
    var $TASA;
    var $FECHACURSE;
    var $RUTIES;
    var $PAGO_FINAL;
    var $MONTO_CREDITO;
    var $PAGO_TOTAL;
//End DataSource Variables

//DataSourceClass_Initialize Event @2-3123DB4A
    function clsVST_PI_REPORTESDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid VST_PI_REPORTES";
        $this->Initialize();
        $this->CARGA_COD = new clsField("CARGA_COD", ccsFloat, "");
        
        $this->TRANSFERENCIA_COD = new clsField("TRANSFERENCIA_COD", ccsFloat, "");
        
        $this->RUT = new clsField("RUT", ccsFloat, "");
        
        $this->RUTBCO = new clsField("RUTBCO", ccsFloat, "");
        
        $this->IEST_NOMBRE_IES = new clsField("IEST_NOMBRE_IES", ccsText, "");
        
        $this->IESN_RUT = new clsField("IESN_RUT", ccsFloat, "");
        
        $this->NOMBRE_BANCO = new clsField("NOMBRE_BANCO", ccsText, "");
        
        $this->BANCN_COD = new clsField("BANCN_COD", ccsFloat, "");
        
        $this->OPERACION = new clsField("OPERACION", ccsFloat, "");
        
        $this->LICITACION = new clsField("LICITACION", ccsFloat, "");
        
        $this->MONTOCREDITO = new clsField("MONTOCREDITO", ccsFloat, "");
        
        $this->FECHA = new clsField("FECHA", ccsDate, $this->DateFormat);
        
        $this->ESTADO_DESC = new clsField("ESTADO_DESC", ccsText, "");
        
        $this->ARANCELSOLICITADO = new clsField("ARANCELSOLICITADO", ccsFloat, "");
        
        $this->CREDITOUF = new clsField("CREDITOUF", ccsFloat, "");
        
        $this->MONTOSEGUROIES = new clsField("MONTOSEGUROIES", ccsFloat, "");
        
        $this->MONTOAPAGAR = new clsField("MONTOAPAGAR", ccsFloat, "");
        
        $this->MONTOOTRACTA = new clsField("MONTOOTRACTA", ccsFloat, "");
        
        $this->PLAZO = new clsField("PLAZO", ccsFloat, "");
        
        $this->TASA = new clsField("TASA", ccsFloat, "");
        
        $this->FECHACURSE = new clsField("FECHACURSE", ccsDate, $this->DateFormat);
        
        $this->RUTIES = new clsField("RUTIES", ccsFloat, "");
        
        $this->PAGO_FINAL = new clsField("PAGO_FINAL", ccsFloat, "");
        
        $this->MONTO_CREDITO = new clsField("MONTO_CREDITO", ccsFloat, "");
        
        $this->PAGO_TOTAL = new clsField("PAGO_TOTAL", ccsFloat, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @2-84420271
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("Sorter_CARGA_COD" => array("CARGA_COD", ""), 
            "Sorter_TRANSFERENCIA_COD" => array("TRANSFERENCIA_COD", ""), 
            "Sorter_RUT" => array("RUT", ""), 
            "Sorter_RUTBCO" => array("RUTBCO", ""), 
            "Sorter_IEST_NOMBRE_IES" => array("IEST_NOMBRE_IES", ""), 
            "Sorter_IESN_RUT" => array("IESN_RUT", ""), 
            "Sorter_NOMBRE_BANCO" => array("NOMBRE_BANCO", ""), 
            "Sorter_BANCN_COD" => array("BANCN_COD", ""), 
            "Sorter_OPERACION" => array("OPERACION", ""), 
            "Sorter_LICITACION" => array("LICITACION", ""), 
            "Sorter_MONTOCREDITO" => array("MONTOCREDITO", ""), 
            "Sorter_FECHA" => array("FECHA", ""), 
            "Sorter_ESTADO_DESC" => array("ESTADO_DESC", ""), 
            "Sorter_ARANCELSOLICITADO" => array("ARANCELSOLICITADO", ""), 
            "Sorter_CREDITOUF" => array("CREDITOUF", ""), 
            "Sorter_MONTOSEGUROIES" => array("MONTOSEGUROIES", ""), 
            "Sorter_MONTOAPAGAR" => array("MONTOAPAGAR", ""), 
            "Sorter_MONTOOTRACTA" => array("MONTOOTRACTA", ""), 
            "Sorter_PLAZO" => array("PLAZO", ""), 
            "Sorter_TASA" => array("TASA", ""), 
            "Sorter_FECHACURSE" => array("FECHACURSE", ""), 
            "Sorter_RUTIES" => array("RUTIES", ""), 
            "Sorter_PAGO_FINAL" => array("PAGO_FINAL", ""), 
            "Sorter_MONTO_CREDITO" => array("MONTO_CREDITO", ""), 
            "Sorter_PAGO_TOTAL" => array("PAGO_TOTAL", "")));
    }
//End SetOrder Method

//Prepare Method @2-526D2470
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urls_RUT", ccsFloat, "", "", $this->Parameters["urls_RUT"], "", false);
        $this->wp->AddParameter("2", "urls_LICITACION", ccsFloat, "", "", $this->Parameters["urls_LICITACION"], "", false);
        $this->wp->AddParameter("3", "urls_OPERACION", ccsFloat, "", "", $this->Parameters["urls_OPERACION"], "", false);
        $this->wp->AddParameter("4", "urls_IESN_RUT", ccsFloat, "", "", $this->Parameters["urls_IESN_RUT"], "", false);
        $this->wp->AddParameter("5", "urls_RUTBCO", ccsFloat, "", "", $this->Parameters["urls_RUTBCO"], "", false);
        $this->wp->AddParameter("6", "urls_PAGO_TOTAL", ccsFloat, "", "", $this->Parameters["urls_PAGO_TOTAL"], "", false);
        $this->wp->AddParameter("7", "urls_FECHA", ccsDate, $DefaultDateFormat, $this->DateFormat, $this->Parameters["urls_FECHA"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "RUT", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsFloat),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "LICITACION", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsFloat),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "OPERACION", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsFloat),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "IESN_RUT", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsFloat),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "RUTBCO", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsFloat),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "PAGO_TOTAL", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsFloat),false);
        $this->wp->Criterion[7] = $this->wp->Operation(opEqual, "FECHA", $this->wp->GetDBValue("7"), $this->ToSQL($this->wp->GetDBValue("7"), ccsDate),false);
        $this->Where = $this->wp->opAND(
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
             $this->wp->Criterion[7]);
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

//SetValues Method @2-44A0FD2A
    function SetValues()
    {
        $this->CARGA_COD->SetDBValue(trim($this->f("CARGA_COD")));
        $this->TRANSFERENCIA_COD->SetDBValue(trim($this->f("TRANSFERENCIA_COD")));
        $this->RUT->SetDBValue(trim($this->f("RUT")));
        $this->RUTBCO->SetDBValue(trim($this->f("RUTBCO")));
        $this->IEST_NOMBRE_IES->SetDBValue($this->f("IEST_NOMBRE_IES"));
        $this->IESN_RUT->SetDBValue(trim($this->f("IESN_RUT")));
        $this->NOMBRE_BANCO->SetDBValue($this->f("NOMBRE_BANCO"));
        $this->BANCN_COD->SetDBValue(trim($this->f("BANCN_COD")));
        $this->OPERACION->SetDBValue(trim($this->f("OPERACION")));
        $this->LICITACION->SetDBValue(trim($this->f("LICITACION")));
        $this->MONTOCREDITO->SetDBValue(trim($this->f("MONTOCREDITO")));
        $this->FECHA->SetDBValue(trim($this->f("FECHA")));
        $this->ESTADO_DESC->SetDBValue($this->f("ESTADO_DESC"));
        $this->ARANCELSOLICITADO->SetDBValue(trim($this->f("ARANCELSOLICITADO")));
        $this->CREDITOUF->SetDBValue(trim($this->f("CREDITOUF")));
        $this->MONTOSEGUROIES->SetDBValue(trim($this->f("MONTOSEGUROIES")));
        $this->MONTOAPAGAR->SetDBValue(trim($this->f("MONTOAPAGAR")));
        $this->MONTOOTRACTA->SetDBValue(trim($this->f("MONTOOTRACTA")));
        $this->PLAZO->SetDBValue(trim($this->f("PLAZO")));
        $this->TASA->SetDBValue(trim($this->f("TASA")));
        $this->FECHACURSE->SetDBValue(trim($this->f("FECHACURSE")));
        $this->RUTIES->SetDBValue(trim($this->f("RUTIES")));
        $this->PAGO_FINAL->SetDBValue(trim($this->f("PAGO_FINAL")));
        $this->MONTO_CREDITO->SetDBValue(trim($this->f("MONTO_CREDITO")));
        $this->PAGO_TOTAL->SetDBValue(trim($this->f("PAGO_TOTAL")));
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

//Class_Initialize Event @3-26199826
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
            $this->Button_DoSearch = & new clsButton("Button_DoSearch", $Method, $this);
            $this->s_LICITACION = & new clsControl(ccsTextBox, "s_LICITACION", "s_LICITACION", ccsFloat, "", CCGetRequestParam("s_LICITACION", $Method, NULL), $this);
            $this->s_IESN_RUT = & new clsControl(ccsListBox, "s_IESN_RUT", "s_IESN_RUT", ccsFloat, "", CCGetRequestParam("s_IESN_RUT", $Method, NULL), $this);
            $this->s_IESN_RUT->DSType = dsTable;
            $this->s_IESN_RUT->DataSource = new clsDBOracle_1();
            $this->s_IESN_RUT->ds = & $this->s_IESN_RUT->DataSource;
            $this->s_IESN_RUT->DataSource->SQL = "SELECT * \n" .
"FROM VST_OA_IES {SQL_Where} {SQL_OrderBy}";
            list($this->s_IESN_RUT->BoundColumn, $this->s_IESN_RUT->TextColumn, $this->s_IESN_RUT->DBFormat) = array("IESN_RUT", "IEST_NOMBRE_IES", "");
            $this->s_RUTBCO = & new clsControl(ccsListBox, "s_RUTBCO", "s_RUTBCO", ccsFloat, "", CCGetRequestParam("s_RUTBCO", $Method, NULL), $this);
            $this->s_RUTBCO->DSType = dsTable;
            $this->s_RUTBCO->DataSource = new clsDBOracle_1();
            $this->s_RUTBCO->ds = & $this->s_RUTBCO->DataSource;
            $this->s_RUTBCO->DataSource->SQL = "SELECT * \n" .
"FROM VST_INGRESA_BNC_BANCOS {SQL_Where} {SQL_OrderBy}";
            list($this->s_RUTBCO->BoundColumn, $this->s_RUTBCO->TextColumn, $this->s_RUTBCO->DBFormat) = array("RUT_BANCO", "NOMBRE_BANCO", "");
            $this->s_PAGO_TOTAL = & new clsControl(ccsCheckBoxList, "s_PAGO_TOTAL", "s_PAGO_TOTAL", ccsFloat, "", CCGetRequestParam("s_PAGO_TOTAL", $Method, NULL), $this);
            $this->s_PAGO_TOTAL->Multiple = true;
            $this->s_PAGO_TOTAL->DSType = dsSQL;
            $this->s_PAGO_TOTAL->DataSource = new clsDBOracle_1();
            $this->s_PAGO_TOTAL->ds = & $this->s_PAGO_TOTAL->DataSource;
            list($this->s_PAGO_TOTAL->BoundColumn, $this->s_PAGO_TOTAL->TextColumn, $this->s_PAGO_TOTAL->DBFormat) = array("PAGO_TOTAL", "PAGO_DESC", "");
            $this->s_PAGO_TOTAL->DataSource->SQL = "select 0 as pago_total, 'Pagado Parcialmente' as pago_desc from dual\n" .
            "union all\n" .
            "select 1 , 'Totalmente Pagado' from dual";
            $this->s_PAGO_TOTAL->DataSource->Order = "";
            $this->s_PAGO_TOTAL->HTML = true;
            $this->s_FECHA_h = & new clsControl(ccsTextBox, "s_FECHA_h", "s_FECHA_h", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("s_FECHA_h", $Method, NULL), $this);
            $this->DatePicker_s_FECHA = & new clsDatePicker("DatePicker_s_FECHA", "VST_PI_REPORTESSearch", "s_FECHA_h", $this);
            $this->s_RUT = & new clsControl(ccsTextBox, "s_RUT", "s_RUT", ccsFloat, "", CCGetRequestParam("s_RUT", $Method, NULL), $this);
            $this->s_OPERACION = & new clsControl(ccsTextBox, "s_OPERACION", "s_OPERACION", ccsFloat, "", CCGetRequestParam("s_OPERACION", $Method, NULL), $this);
            $this->s_FECHA_d = & new clsControl(ccsTextBox, "s_FECHA_d", "s_FECHA_d", ccsDate, array("dd", "/", "mm", "/", "yyyy"), CCGetRequestParam("s_FECHA_d", $Method, NULL), $this);
            $this->DatePicker_s_FECHA_d1 = & new clsDatePicker("DatePicker_s_FECHA_d1", "VST_PI_REPORTESSearch", "s_FECHA_d", $this);
        }
    }
//End Class_Initialize Event

//Validate Method @3-CE85CD40
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->s_LICITACION->Validate() && $Validation);
        $Validation = ($this->s_IESN_RUT->Validate() && $Validation);
        $Validation = ($this->s_RUTBCO->Validate() && $Validation);
        $Validation = ($this->s_PAGO_TOTAL->Validate() && $Validation);
        $Validation = ($this->s_FECHA_h->Validate() && $Validation);
        $Validation = ($this->s_RUT->Validate() && $Validation);
        $Validation = ($this->s_OPERACION->Validate() && $Validation);
        $Validation = ($this->s_FECHA_d->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->s_LICITACION->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_IESN_RUT->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_RUTBCO->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_PAGO_TOTAL->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_FECHA_h->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_RUT->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_OPERACION->Errors->Count() == 0);
        $Validation =  $Validation && ($this->s_FECHA_d->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @3-D111DA19
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->s_LICITACION->Errors->Count());
        $errors = ($errors || $this->s_IESN_RUT->Errors->Count());
        $errors = ($errors || $this->s_RUTBCO->Errors->Count());
        $errors = ($errors || $this->s_PAGO_TOTAL->Errors->Count());
        $errors = ($errors || $this->s_FECHA_h->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_FECHA->Errors->Count());
        $errors = ($errors || $this->s_RUT->Errors->Count());
        $errors = ($errors || $this->s_OPERACION->Errors->Count());
        $errors = ($errors || $this->s_FECHA_d->Errors->Count());
        $errors = ($errors || $this->DatePicker_s_FECHA_d1->Errors->Count());
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

//Operation Method @3-C2A23D65
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
        $Redirect = "reportes_resp.php";
        if($this->Validate()) {
            if($this->PressedButton == "Button_DoSearch") {
                $Redirect = "reportes_resp.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("Button_DoSearch", "Button_DoSearch_x", "Button_DoSearch_y")));
                if(!CCGetEvent($this->Button_DoSearch->CCSEvents, "OnClick", $this->Button_DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @3-03F66366
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
        $this->s_PAGO_TOTAL->Prepare();

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
            $Error = ComposeStrings($Error, $this->s_PAGO_TOTAL->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_FECHA_h->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_s_FECHA->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_RUT->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_OPERACION->Errors->ToString());
            $Error = ComposeStrings($Error, $this->s_FECHA_d->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_s_FECHA_d1->Errors->ToString());
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
        $this->s_PAGO_TOTAL->Show();
        $this->s_FECHA_h->Show();
        $this->DatePicker_s_FECHA->Show();
        $this->s_RUT->Show();
        $this->s_OPERACION->Show();
        $this->s_FECHA_d->Show();
        $this->DatePicker_s_FECHA_d1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End VST_PI_REPORTESSearch Class @3-FCB6E20C

//Initialize Page @1-1BCD20F5
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
$TemplateFileName = "reportes_resp.html";
$BlockToParse = "main";
$TemplateEncoding = "Windows-1252";
$ContentType = "text/html";
$PathToRoot = "../";
$Charset = $Charset ? $Charset : "windows-1252";
//End Initialize Page

//Include events file @1-44708483
include_once("./reportes_resp_events.php");
//End Include events file

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
