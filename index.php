<?php
	session_start();
	include_once 'app/config.php';
	include_once 'app/controlerFile.php';
	include_once 'app/controlerUser.php';
	//include_once 'app/modeloUser.php';
	include_once "app/modeloUserDB.php";
	include_once 'app/usuarios.php';

	//Inicializo el modelo (actualizado a clase de acceso a BD)
	//modeloUserInit();
	ModeloUserDB::init();
	
	//Relación entre peticiones y función que la va a tratar
	
	//Enrutamiento para el modo "Gestión de usuarios" (solo admin)
	$rutasUser = [
		"Inicio"      => "ctlUserInicio",
		"Alta"        => "ctlUserAlta",
		"Detalles"    => "ctlUserDetalles",
		"Modificar"   => "ctlUserModificar",
		"Borrar"      => "ctlUserBorrar",
		"Cerrar"      => "ctlUserCerrar",
		"VerUsuarios" => "ctlUserVerUsuarios"
	];
	
	//Enrutamiento para ficheros (admin y otros usuarios)
	$rutasFicheros = [
	    "VerFicheros" => "ctlFileVerFicheros",
	    "Nuevo"       => "ctlFileNuevo",
	    "Borrar"      => "ctlFileBorrar",
	    "Renombrar"   => "ctlFileRenombrar",
	    "Compartir"   => "ctlFileCompartir",
	    "Cerrar"      => "ctlUserCerrar"/*,
	    "Descargar"   => "ctlFileDescargar"*/
	];
	
	//--------------------------------------------------------------------
	//Método para comprobar si se quiere registrar un usuario y su gestión.
	registro();
	
	//Si no hay usuario a Inicio
	if(!isset($usuario)){
		$procRuta = "ctlUserInicio";
	}else{
	    if(isset($_REQUEST["cambiar"])){
	        cambiarModo();
	    }else{
	        if($_SESSION['modo'] == GESTIONUSUARIOS){
	            if(isset($_GET['orden'])){
	                //La orden tiene una funcion asociada
	                if(isset($rutasUser[$_GET['orden']])){
	                    $procRuta = $rutasUser[$_GET['orden']];
	                }else{
	                    //Error no existe función para la ruta
	                    header('Status: 404 Not Found');
	                    echo '<html><body><h1>Error 404: No existe la ruta <i>' . $_GET['orden'] . '</p></body></html>';
	                    exit;
	                }
	            }else{
	                if(isset($_GET["darAlta"])){
	                    include_once "app/plantilla/altausuarios.php";
	                    exit();
	                }elseif(isset($_GET["modificar"])){
	                    include_once "app/plantilla/modificaruser.php";
	                    exit();
	                }else{
	                    $procRuta = "ctlUserVerUsuarios";
	                }
	            }
	        }elseif($_SESSION['modo'] == GESTIONFICHEROS){
	            if(isset($_GET["modificar"])){
	                include_once "app/plantilla/modificaruser.php";
	                exit();
	            }
	            if(isset($_GET['orden'])){
	                //La orden tiene una función asociada
	                if(isset($rutasFicheros[$_GET['orden']])){
	                    $procRuta =  $rutasFicheros[$_GET['orden']];
	                }elseif($_GET["orden"] == "Modificar"){
	                    $procRuta = "ctlUserModificar";
	                }else{
	                    //Error no existe función para la ruta
	                    header('Status: 404 Not Found');
	                    echo '<html><body><h1>Error 404: No existe la ruta <i>'.$_GET['orden'] .'</p></body></html>';
	                    exit;
	                }
	            }elseif(isset($_FILES["archivo"])){
	                $procRuta = "ctlFileNuevo";
	            }else{
	                if(isset($_GET["subir"])){
	                    include_once 'app/plantilla/subirarchivo.php';
	                    exit();
	                }else{
	                    $procRuta = "ctlFileVerFicheros";
	                } 
	            }
	        }else{
	            $procRuta = "ctlUserInicio";
	        }
	    }
	}
	
	if (isset($_GET['msg'])){
	    $msg = $_GET['msg'];
	}
	
	// Llamo a la función seleccionada
	if($procRuta == "ctlUserVerUsuarios"){
	    $procRuta(null);
	}else{
	   $procRuta();
	}
?>