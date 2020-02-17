<?php
    // ------------------------------------------------
    // Controlador que realiza la gestión de usuarios
    // ------------------------------------------------
    include_once 'config.php';
    //include_once 'modeloUser.php';
    include_once 'modeloUserDB.php';
    require_once 'usuarios.php';
    
    /*
     * Inicio Muestra o procesa el formulario (POST)
     */
    //Si no hay usuario en la sesión, se inicializan las variables 
    function ctlUserInicio($mensaje){
        if(isset($mensaje)){
            $msg = $mensaje;
        }
        $user ="";
        $clave ="";
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_POST['user']) && isset($_POST['clave'])){
                $user =$_POST['user'];
                $clave=$_POST['clave'];
                if(ModeloUserDB::OkUser($user, $clave)){
                    if(ModeloUserDB::UserGet($user) != false){
                        $datos = ModeloUserDB::UserGet($user);
                        $usuario = new usuarios($datos);
                        $tipouser = ModeloUserDB::ObtenerTipo($user);
                        if($tipouser == "Máster"){
                            if($datos[5] != "Activo"){
                                $msg = TMENSAJES["USERNOACTIVO"];
                            }else{
                                $usuario->setPerfil(GESTIONUSUARIOS);
                                $_SESSION["user"] = serialize($usuario);
                                header('Location:index.php?orden=VerUsuarios');
                            }
                        }else{
                            if($datos[5] != "Activo"){
                                $msg = TMENSAJES["USERNOACTIVO"];
                            }else{
                                $usuario->setPerfil(GESTIONFICHEROS);
                                $_SESSION["user"] = serialize($usuario);
                                header("Location: index.php?orden=VerFicheros");
                            }
                        }
                    }else{
                        $msg = "Error al intentar crear el objeto de usuario";
                    }
                }else{
                    $msg = TMENSAJES["LOGINERROR"];
               }  
            }
        } 
        include_once 'plantilla/facceso.php';
    }
    
    //Si el usuario solicita realizar un registro normal:
    function registro(){
        if(isset($_GET["registro"])){
            include_once "app/plantilla/registro.php";
            exit();
        }elseif(isset($_POST["registro"])){
            $resu = ModeloUserDB::UserAdd($_POST["clave"], $_POST["pass"], $_POST["pass2"], $_POST["nombre"], $_POST["mail"], $_POST["plan"], "I");
            if($resu === true){       
                $mens = TMENSAJES["USERREG"];
                echo "<script language='JavaScript'>";
                echo "alert('$mens');";
                echo "</script>";
            }elseif($resu === false){
                return TMENSAJES["USERNOSAVE"];
            }else{
                $msg = TMENSAJES["USERNOSAVE"].": ".$resu;
                return $msg;
            }
        }
        return null;
    }
    
    // Cambia de modo desde la sesión de administración.
    function cambiarModo(){
        if(isset($_SESSION["user"])){
            $usuario = unserialize($_SESSION["user"]);
            if($usuario->getPlan() == "Máster"){
                if($usuario->getPerfil() == GESTIONUSUARIOS){
                    $usuario->setPerfil(GESTIONFICHEROS);
                    $_SESSION["user"] = serialize($usuario);
                    header("Location:index.php?orden=VerFicheros");
                }else{
                    $usuario->setPerfil(GESTIONUSUARIOS);
                    $_SESSION["user"] = serialize($usuario);
                    header("Location:index.php?orden=VerUsuarios");
                }
            }else{
                die("Error: solo un usuario de tipo Máster puede cambiar su perfil de gestión.");
            }
        }else{
            die("Error grave: sin usuario activo.");
        }
    }
    
    // Cierra la sesión y vuelva los datos
    function ctlUserCerrar(){
        session_destroy();
        //modeloUserSave();
        header('Location:index.php');
    }
    
    // Muestro la tabla con los usuario 
    function ctlUserVerUsuarios($mensaje){
        if(!empty($mensaje)){
            $msg = $mensaje;
        }
        // Obtengo los datos del modelo
        $usuarios = ModeloUserDB::GetAll(); 
        // Invoco la vista 
        include_once 'plantilla/verusuarios.php';   
    }
    
    function ctlUserModificar(){
        if(isset($_SESSION["user"])){  
            $usuario = unserialize($_SESSION["user"]);
            $msg = "";
            if(isset($_REQUEST["id"])){ 
                if(empty($_REQUEST["pass"])){
                    $msg = TMENSAJES["ERRORUPDATE"];
                    if($usuario->getPerfil() == GESTIONUSUARIOS){
                        ctlUserVerUsuarios($msg);
                    }else{
                        ctlFileVerFicheros($msg);
                    }
                    exit();
                }else{
                    if($usuario->getPerfil() != "Máster"){
                        if(isset($_REQUEST["pass2"])){
                            if($_REQUEST["pass"] != $_REQUEST["pass2"]){
                                $msg = TMENSAJES["ERRORUPDATE"];
                                if($usuario->getPerfil() == GESTIONUSUARIOS){
                                    ctlUserVerUsuarios($msg);
                                }else{
                                    ctlFileVerFicheros($msg);
                                }
                                exit();
                            }
                        }
                    }
                }
                if(!ModeloUserDB::UserUpdate($_REQUEST["id"], $_REQUEST["pass"], $_REQUEST["nombre"], $_REQUEST["mail"], $_REQUEST["plan"], $_REQUEST["estado"])){
                    $msg = TMENSAJES["ERRORUPDATE"];
                }else{
                    $msg = TMENSAJES["USERUPDATE"];
                    echo "<script language='JavaScript'>alert('$msg');</script>";
                    $msg = null;
                    if($_REQUEST["id"] == $usuario->getId()){
                        $usuario = ModeloUserDB::UserGet($_REQUEST["id"]);
                        $_SESSION["user"] = serialize($usuario);
                    }
                }
            }else{
                $msg = TMENSAJES["ERRORUPDATE"];
            }
            if($usuario->getPerfil() == GESTIONUSUARIOS){
                ctlUserVerUsuarios($msg);
            }else{
                ctlFileVerFicheros($msg);
            }
        }
    }
    
    function ctlUserDetalles(){
        $msg = "";
        if(isset($_GET["id"])){
            if(!ModeloUserDB::UserGet($_GET["id"])){
                $msg = "Error: no se ha encontrado al usuario especificado.";
                ctlUserVerUsuarios($msg);
            }else{
                $vistausuario = ModeloUserDB::UserGet($_GET["id"]);
                include_once "plantilla/verdetalles.php";
            }
        }else{
            $msg = "Error: no se ha especificado clave de usuario.";
            ctlUserVerUsuarios($msg);
        }
    }
    
    function ctlUserAlta(){
        $msg = "";
        if(empty($_REQUEST["estado"])){
            $estado = "I";   
        }else{
            $estado = $_REQUEST["estado"];
        }
        
        if(!ModeloUserDB::UserAdd($_REQUEST["clave"], $_REQUEST["pass"], $_REQUEST["pass2"], $_REQUEST["nombre"], $_REQUEST["mail"], $_REQUEST["plan"], $estado)){
            $msg = TMENSAJES["USERNOSAVE"];
            ctlUserVerUsuarios($msg);
        }else{
            $msg = TMENSAJES["USERSAVE"];
            echo "<script language='JavaScript'>alert('$msg');</script>";
            $msg = null;
            if(!file_exists(RUTA_FICHEROS.$_REQUEST["clave"]."/") && !is_dir(RUTA_FICHEROS.$_REQUEST["clave"])."/"){
                mkdir(RUTA_FICHEROS.$_REQUEST["clave"]."/", 0667);
            }
            ctlUserVerUsuarios($msg);
        }
    }
    
    function ctlUserBorrar(){
        $msg = "";
        if(isset($_GET["id"]) && ($_GET["id"] != unserialize($_SESSION["user"])->getId())){
            if(!ModeloUserDB::UserDel($_GET["id"])){
                $msg = TMENSAJES["ERRORDEL"];
                ctlUserVerUsuarios($msg);
            }else{
                $msg = TMENSAJES["USERDEL"];
                echo "<script language='JavaScript'>alert('$msg');</script>";
                $msg = null;
                if(file_exists(RUTA_FICHEROS.$_GET["id"]."/") && !is_dir(RUTA_FICHEROS.$_GET["id"])."/"){
                    rmdir(RUTA_FICHEROS.$_GET["id"]."/");
                }
                ctlUserVerUsuarios($msg);
            }
        }else{
            $msg = TMENSAJES["ERRORUSER"];
            ctlUserVerUsuarios($msg);
        }
    }
?>