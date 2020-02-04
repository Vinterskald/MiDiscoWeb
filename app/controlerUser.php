<?php
    // ------------------------------------------------
    // Controlador que realiza la gestión de usuarios
    // ------------------------------------------------
    include_once 'config.php';
    //include_once 'modeloUser.php';
    include_once 'modeloUserDB.php';
    include_once 'usuarios.php';
    
    /*
     * Inicio Muestra o procesa el formulario (POST)
     */
    //Si no hay usuario en la sesión, se inicializan las variables 
    function ctlUserInicio(){
        $msg = "";
        $user ="";
        $clave ="";
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            if(isset($_POST['user']) && isset($_POST['clave'])){
                $user =$_POST['user'];
                $clave=$_POST['clave'];
                if(ModeloUserDB::OkUser($user, $clave)){
                    if(ModeloUserDB::UserGet($user) != null){
                        $datos = ModeloUserDB::UserGet($user);
                        $_SESSION["user"] = new usuarios($datos);
                        $tipouser = ModeloUserDB::ObtenerTipo($user);
                        if($tipouser == "Máster"){
                            if($datos[5] != "A"){
                                $msg = TMENSAJES["USERNOACTIVO"];
                            }else{
                                $_SESSION["user"]->perfil = GESTIONUSUARIOS;
                                header('Location:index.php?orden=VerUsuarios');
                            }
                        }else{
                            if($datos[5] != "A"){
                                $msg = TMENSAJES["USERNOACTIVO"];
                            }else{
                                $_SESSION["user"]->perfil = GESTIONFICHEROS;
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
            if($_POST["pass"] != $_POST["pass2"]){
                $msg = TMENSAJES["PASSDIST"];
            }else{
                if(!ModeloUserDB::UserAdd($_POST["clave"], $_POST["pass"], $_POST["nombre"], $_POST["mail"], $_POST["plan"], "I")){
                    $msg = TMENSAJES["USERNOSAVE"];
                }else{
                    $msg = TMENSAJES["USERREG"];
                }
            }   
        }
    }
    
    // Cambia de modo desde la sesión de administración.
    function cambiarModo(){
        if(isset($_SESSION["user"])){
            $usuario = $_SESSION["user"];
            if(ESTADOS[$usuario->estado] == "Máster"){
                if($usuario->perfil == GESTIONUSUARIOS){
                    $usuario->perfil = GESTIONFICHEROS;
                    header("Location:index.php?orden=VerFicheros");
                }else{
                    $usuario->perfil = GESTIONUSUARIOS;
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
        if(isset($mensaje)){
            $msg = $mensaje;
        }
        // Obtengo los datos del modelo
        $usuarios = ModeloUserDB::GetAll(); 
        // Invoco la vista 
        include_once 'plantilla/verusuarios.php';   
    }
    
    function ctlUserModificar(){
        if(isset($_SESSION["usuario"])){  
            $usuario = $_SESSION["usuario"];
            $msg = "";
            if(isset($_REQUEST["clave"])){
                if(!ModeloUserDB::UserUpdate($_REQUEST["id"], $_REQUEST["pass"], $_REQUEST["nombre"], $_REQUEST["mail"], $_REQUEST["plan"], $_REQUEST["estado"])){
                    $msg = TMENSAJES["ERRORUPDATE"];
                    if($usuario->perfil == GESTIONUSUARIOS){
                        ctlUserVerUsuarios($msg);
                    }else{
                        ctlFileVerFicheros($msg);
                    }
                }else{
                    $msg = TMENSAJES["USERUPDATE"];
                    if($usuario->perfil == GESTIONUSUARIOS){
                        ctlUserVerUsuarios($msg);
                    }else{
                        ctlFileVerFicheros($msg);    
                    }
                }
            }else{
                $msg = TMENSAJES["ERRORUPDATE"];
                if($usuario->perfil == GESTIONUSUARIOS){
                    ctlUserVerUsuarios($msg);
                }else{
                    ctlFileVerFicheros($msg);
                }
            }
        }
    }
    
    function ctlUserDetalles(){
        $msg = "";
        if(isset($_GET["id"])){
            if(!modeloUserGet($_GET["id"])){
                $msg = "Error: no se ha encontrado al usuario especificado.";
                ctlUserVerUsuarios($msg);
            }else{
                $vistausuario = modeloUserGet($_GET["id"]);
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
        
        if(!modeloUserAdd($_REQUEST["clave"], $_REQUEST["pass"], $_REQUEST["nombre"], $_REQUEST["mail"], $_REQUEST["plan"], $estado)){
            $msg = "Error: el usuario o el correo ya existen.";
            ctlUserVerUsuarios($msg);
        }else{
            echo "<script language='javascript'>";
            echo "alert('Nuevo usuario añadido correctamente.');";
            echo "</script>";
            ctlUserVerUsuarios(null);
        }
    }
    
    function ctlUserBorrar(){
        $msg = "";
        if(isset($_GET["id"])){
            if(!modeloUserDel($_GET["id"])){
                $msg = "Error: no se ha encontrado el usuario a eliminar.";
                ctlUserVerUsuarios($msg);
            }else{
                echo "<script language='javascript'>";
                echo "alert('Usuario eliminado correctamente.');";
                echo "</script>";
                ctlUserVerUsuarios(null);
            }
        }else{
            $msg = "Error: no se ha especificado clave de usuario.";
            ctlUserVerUsuarios($msg);
        }
    }
?>