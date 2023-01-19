<?php
//Page_BeforeInitialize @1-D7A96A5E
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $proxy; //Compatibility
//End Page_BeforeInitialize

//Custom Code @2-2A29BDB7
// -------------------------
	//Obtener id_log
	$id_log = CCGetParam("il");
	//Obtener si es archivo de error o archivo subido
	$error = CCGetParam("e");
	
	//validar id_log
	if(!is_numeric($id_log))
		{
			exit();
		}


	//obtener desde bd nombre de archivo
	if ($error == 1)
		{
			$nombre_campo = "archivo_errores";
		}	
	else
		{
			$nombre_campo = "archivo_cargado";
		}
	
	$db = new clsDBOracle_1();

	//si el tipo de usuario no es gestion ingresa, filtrar

	$tipo_de_usuario = CCGetGroupID();

			$condicion = " id_log = ".$id_log;
	
	//<< filtrar

	$archivo = CCDLookUp($nombre_campo,"pi_hst_cargas",$condicion,$db);
	
	//abrir archivo
	
	if($archivo)
		{
		$ruta = "../../../gestion2016/app/mod_carga/carga/".$archivo;
		$arch =  file($ruta);
			
		}
	else
		{
			echo "Archivo no encontrado.".$ruta;
		}
	//<< abrir
	//>>archivo abierto
	if($arch)
		{
		//agregar headers

			//>> obtener nombre de archivo  para headers (ruta viene completa)
			$nombre_archivo = explode("/",$archivo);

			foreach($nombre_archivo as $temp_archivo)
				{
					if (strpos($temp_archivo,".") > 0  )
					$nombre_final = $temp_archivo;
				}
			//<<

			
			header ("Content-Disposition:  attachment;filename=".$nombre_final."");
			header ("Content-Type: text/plain");
	
					
			foreach($arch as $linea)
			{
					$linea = str_replace("&iacute;","i",$linea);
					$linea = str_replace("ÿ","i",$linea);
				/*
					$linea = str_replace("&iacute;","i",$linea);
					echo substr($linea,0,-1)."\r\n";
				*/
				if ($error)
					echo  substr($linea,0,-1)."\r\n";
				else
					echo $linea;				

				flush();				
			}

			
			exit();
		
		}
	//<< archivo abierto
	else
	//>> error
		{
			echo "Archivo no encontrado".$ruta;
		}
	//<< error
	exit();
// -------------------------
//End Custom Code

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
