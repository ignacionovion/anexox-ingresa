<?php
//BindEvents Method @1-7EE0CF6A
function BindEvents()
{
    global $Panel1;
    global $Panel2;
    $Panel1->CCSEvents["BeforeShow"] = "Panel1_BeforeShow";
    $Panel2->CCSEvents["BeforeShow"] = "Panel2_BeforeShow";
}
//End BindEvents Method

//Panel1_BeforeShow @2-AAD8AF72
function Panel1_BeforeShow(& $sender)
{
    $Panel1_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Panel1; //Compatibility
//End Panel1_BeforeShow

//Panel2Panel1YahooTabbedView BeforeShow @7-D20036F0
    $Component->BlockPrefix = "<div id=\"Panel2Panel1\">";
    $Component->BlockSuffix = "</div>";
//End Panel2Panel1YahooTabbedView BeforeShow

//Close Panel1_BeforeShow @2-D21EBA68
    return $Panel1_BeforeShow;
}
//End Close Panel1_BeforeShow

//Panel2_BeforeShow @4-96696C3D
function Panel2_BeforeShow(& $sender)
{
    $Panel2_BeforeShow = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $Panel2; //Compatibility
//End Panel2_BeforeShow

//Panel2YahooTabbedTab BeforeShow @5-8FD0766E
    $Component->BlockPrefix = "<div id=\"Panel2\">";
    $Component->BlockSuffix = "</div>";
//End Panel2YahooTabbedTab BeforeShow

//Close Panel2_BeforeShow @4-AE7F9FB3
    return $Panel2_BeforeShow;
}
//End Close Panel2_BeforeShow


?>
