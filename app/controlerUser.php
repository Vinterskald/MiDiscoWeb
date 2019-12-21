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
                      // Usuario normal;
                      // PRIMERA VERSIÓN SOLO USUARIOS ADMISTRADORES
                      $msg="Error: Acceso solo permitido a usuarios Administradores.";
                      // $_SESSION['modo'] = GESTIONFICHEROS;
                      // Cambio de modo y redireccion a verficheros
                    }
                }else{
                    $msg="Error: usuario y / o contraseña no válidos.";
               }  
            }
        }
        
        include_once 'plantilla/facceso.php';
    }
    
    // Cierra la sesión y vuelva los datos
    function ctlUserCerrar(){
        session_destroy();
        modeloUserSave();
        header('Location:index.php');
    }
    
    // Muestro la tabla con los usuario 
    function ctlUserVerUsuarios(){
        // Obtengo los datos del modelo
        $usuarios = modeloUserGetAll(); 
        // Invoco la vista 
        include_once 'plantilla/verusuarios.php';   
    }
    
    function ctlUserModificar(){
        $msg = "";
        if(isset($_REQUEST["clave"])){
            if(!modeloUserUpdate($_REQUEST["clave"], $_REQUEST["pass"], $_REQUEST["nombre"], $_REQUEST["mail"], $_REQUEST["plan"], $_REQUEST["estado"])){
                $msg = "Error: no se ha encontrado al usuario especificado.";
            }else{
                echo "<script language='javascript'>";
                echo "alert('Cambios en usuario añadidos correctamente.');";
                echo "</script>";
            }
            ctlUserVerUsuarios();
        }else{
            $msg = "Error: no se ha especificado clave de usuario.";
            ctlUserVerUsuarios();
        }
    }
    
    function ctlUserDetalles(){
        if(isset($_GET["id"])){
            if(!modeloUserGet($_GET["id"])){
                $msg = "Error: no se ha encontrado al usuario especificado.";
                ctlUserVerUsuarios();
            }else{
                $vistausuario = modeloUserGet($_GET["id"]);
                include_once "plantilla/verdetalles.php";
            }
        }else{
            $msg = "Error: no se ha especificado clave de usuario.";
            ctlUserVerUsuarios();
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
            $msg = "Error: el usuario ya existe.";
            echo "O_O";
        }else{
            echo "<script language='javascript'>";
            echo "alert('Nuevo usuario añadido correctamente.');";
            echo "</script>";
        }
        ctlUserVerUsuarios();
    }
    
    function ctlUserBorrar(){
        if(isset($_GET["id"])){
            if(!modeloUserDel($_GET["id"])){
                $msg = "Error: no se ha encontrado el usuario a eliminar.";
            }else{
                echo "<script language='javascript'>";
                echo "alert('Usuario eliminado correctamente.');";
                echo "</script>";
            }
            ctlUserVerUsuarios();
        }else{
            $msg = "Error: no se ha especificado clave de usuario.";
            ctlUserVerUsuarios();
        }
    }
?>