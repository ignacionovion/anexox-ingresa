<?php
//Include Common Files @1-A095D95E
define("RelativePath", "..");
define("PathToCurrentPage", "/mod_pagoies/");
define("FileName", "estados.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");

	$db = new clsDBOracle_1();
	$db->query("update pi_transferencias set estado_cod = 5 where trunc(sysdate) - TRUNC(fecha) > 15 and estado_cod = 2"); 


?>
