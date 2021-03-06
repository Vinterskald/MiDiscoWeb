<?php
	session_start();
	require_once 'app/usuarios.php';
	include_once 'app/config.php';
	include_once 'app/controlerFile.php';
	include_once 'app/controlerUser.php';
	//include_once 'app/modeloUser.php';
	include_once "app/modeloUserDB.php";
	
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
	    "Cerrar"      => "ctlUserCerrar",
	    "Descargar"   => "ctlFileDescargar"
	];
	
	//--------------------------------------------------------------------
	//Método para comprobar si se quiere registrar un usuario y su gestión.
	$msg = registro();
	//Si no hay (objeto) usuario, a Inicio
	if(!isset($_SESSION["user"])){
	    if(isset($_GET["orden"]) && $_GET["orden"] == "DescargaDirecta"){
	        ctlFileDescargaDirecta();
	    }
	    
		$procRuta = "ctlUserInicio";
	}else{
	    $usuario = unserialize($_SESSION["user"]);
	    if(isset($_REQUEST["cambiar"])){
	        cambiarModo();
	    }else{
	        //var_dump($usuario->getPerfil());
	        if($usuario->getPerfil() == GESTIONUSUARIOS){
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
	        }elseif($usuario->getPerfil() == GESTIONFICHEROS){
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
	
	// Llamo a la función seleccionada
	if($procRuta == "ctlUserVerUsuarios" || $procRuta == "ctlFileVerFicheros"){
	    $procRuta(null);
	}elseif($procRuta == "ctlUserInicio"){
	    $procRuta($msg);
	}else{
	    $procRuta();
	}
?>