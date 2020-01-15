<?php
    // ------------------------------------------------
    // Controlador que realiza la gestión de usuarios
    // ------------------------------------------------
    include_once 'config.php';
    include_once 'modeloUser.php';
    
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
                if(modeloOkUser($user,$clave)){
                    $_SESSION['user'] = $user;
                    $_SESSION['tipouser'] = modeloObtenerTipo($user);
                    if($_SESSION['tipouser'] == "Máster"){
                        $_SESSION['modo'] = GESTIONUSUARIOS;
                        header('Location:index.php?orden=VerUsuarios');
                    }else{
                        if($_SESSION["tusuarios"][$_SESSION["user"]][4] == "I" || $_SESSION["tusuarios"][$_SESSION["user"]][4] == "B"){
                            $msg = "Error: el usuario no está activado o está bloqueado.";
                        }else{
                            $_SESSION['modo'] = GESTIONFICHEROS;
                            // Cambio de modo y redireccion a verficheros
                            header("Location: index.php?orden=VerFicheros");
                        }
                    }
                }else{
                    $msg="Error: usuario y / o contraseña no válidos.";
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
                echo "<script language='JavaScript'>";
                echo "alert('No se ha podido registrar el usuario; las contraseñas no coinciden.');";
                echo "</script>";  
                exit();
            }
            if(!modeloUserAdd($_POST["clave"], $_POST["pass"], $_POST["nombre"], $_POST["mail"], $_POST["plan"], "I")){
                echo "<script language='JavaScript'>";
                echo "alert('No se ha podido registrar el usuario; nombre y/o correo ya en uso.');";    
                echo "</script>";                
            }else{
                echo "<script language='JavaScript'>";
                echo "alert('Usuario registrado con éxito.');";
                echo "</script>";  
            }
        }
    }
    
    // Cambia de modo desde la sesión de administración.
    function cambiarModo(){
        if(isset($_SESSION["modo"]) && $_SESSION["tipouser"] == "Máster"){
            if($_SESSION["modo"] == GESTIONUSUARIOS){
                $_SESSION["modo"] = GESTIONFICHEROS;
                header("Location:index.php?orden=VerFicheros");
            }else{
                $_SESSION["modo"] = GESTIONUSUARIOS;
                header("Location:index.php?orden=VerUsuarios");
            }
        }else{
            die("Error al cambiar el tipo de gestión.");
        }
    }
    
    // Cierra la sesión y vuelva los datos
    function ctlUserCerrar(){
        session_destroy();
        modeloUserSave();
        header('Location:index.php');
    }
    
    // Muestro la tabla con los usuario 
    function ctlUserVerUsuarios($mensaje){
        if(isset($mensaje)){
            $msg = $mensaje;
        }
        // Obtengo los datos del modelo
        $usuarios = modeloUserGetAll(); 
        // Invoco la vista 
        include_once 'plantilla/verusuarios.php';   
    }
    
    function ctlUserModificar(){
        $msg = "";
        if(isset($_REQUEST["clave"])){
            if(!modeloUserUpdate($_REQUEST["clave"], $_REQUEST["pass"], $_REQUEST["nombre"], $_REQUEST["mail"], $_REQUEST["plan"], $_REQUEST["estado"])){
                $msg = "Error: el correo especificado ya está en uso o hay un problema con el usuario.";
                if($_SESSION["modo"] == GESTIONUSUARIOS){
                    ctlUserVerUsuarios($msg);
                }else{
                    echo "<script language='javascript'>";
                    echo "alert('Error: no se ha podido actualizar el usuario.');";
                    echo "</script>";
                    ctlFileVerFicheros();
                }
            }else{
                echo "<script language='javascript'>";
                echo "alert('Cambios en usuario añadidos correctamente.');";
                echo "</script>";
                if($_SESSION["modo"] == GESTIONUSUARIOS){
                    ctlUserVerUsuarios(null);
                }else{
                    ctlFileVerFicheros();    
                }
            }
        }else{
            $msg = "Error: no se ha especificado clave de usuario.";
            if($_SESSION["modo"] == GESTIONUSUARIOS){
                ctlUserVerUsuarios($msg);
            }else{
                ctlFileVerFicheros();
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