<?php
    include_once 'config.php';
    include_once 'usuarios.php';
    include_once 'app/dat/Encriptador.php';
    // --------------------------------------------------------------
    // Controlador que realiza la gestión de ficheros de un usuario
    // ---------------------------------------------------------------

    //Obtener espacio total por archivos en directorio personal:
    function tam_dir($dir){
        $tam = 0;
        if(is_dir($dir)){
            if($dh = opendir($dir)){
                while(($archivo = readdir($dh)) !== FALSE){
                    if($archivo != "." && $archivo != ".."){
                        if(is_file($dir."/".$archivo)){
                            $tam += filesize($dir."/".$archivo);
                        }
                    }
                }
            }
        }
        closedir($dh);
        return $tam;
    }
    //----------------------------------------------------------    
    function ctlFileVerFicheros($mensaje){
        if(isset($_SESSION["user"])){
            $usuario = unserialize($_SESSION["user"]);
            if(!empty($mensaje)) $msg = $mensaje;
            //Si estás conectado con un usuario sin directorio, se crea de nuevo.
            if(!file_exists(RUTA_FICHEROS.$usuario->getId()."/") && !is_dir(RUTA_FICHEROS.$usuario->getId()."/")){
                mkdir(RUTA_FICHEROS.$usuario->getId()."/", 0667);
            }
            include_once "plantilla/verficheros.php";
            exit();
        }else{
            die("No se puede acceder sin usuario.");
        }
    }
    
    function ctlFileNuevo(){
        $msg = "";
        $max_espacio = 0;
        $max_archivos = 0;
        if(isset($_SESSION["user"])){
            $usuario = unserialize($_SESSION["user"]);
            
            $max_espacio = LIMITE_ESPACIO[array_search($usuario->getPlan(), PLANES)];
            $max_archivos = LIMITE_FICHEROS[array_search($usuario->getPlan(), PLANES)];
            
            $espacio_restante = (int) ($max_espacio - tam_dir(RUTA_FICHEROS.$usuario->getId()."/"));
            
            switch($_FILES["archivo"]["error"]){
                case 0:
                    $dir_archivo = RUTA_FICHEROS.$usuario->getId()."/".basename($_FILES["archivo"]["name"]);
                    if(file_exists($dir_archivo)){
                        $msg = "El archivo ya existe";
                        break;
                    }
                    if($_FILES["archivo"]["size"] > $espacio_restante && $usuario->getPlan() != "Máster"){
                        $msg = "Error: el archivo excede tu límite de espacio en disco.";
                        break;
                    }
                    if(count(preg_grep("/^([^.])/", scandir(RUTA_FICHEROS.$usuario->getId()."/"))) > $max_archivos && $usuario->getlPlan() != "Máster"){
                        $msg = "Error: no puedes subir más archivos.";
                        break;
                    }
                    if(move_uploaded_file($_FILES["archivo"]["tmp_name"], $dir_archivo)){
                        echo "<script language='JavaScript'>";
                        echo "alert('Archivo subido con éxito.');";
                        echo "</script>";
                        $msg = null;
                        break;
                    }else{
                        $msg = "No se ha podido subir el archivo.";
                        break;
                    }
                    
                case 3:
                    $msg = "No se ha podido completar la subida";
                    break;
                    
                case 4:
                    $msg = "No has elegido ningún archivo.";
                    break;
                    
                case 7:
                    $msg = "Error de permisos en el fichero de subida; no se puede subir el archivo.";
                    break;
            }
        }
        ctlFileVerFicheros($msg);
    }
    
    function ctlFileBorrar(){
        if(!isset($_SESSION["user"])){
            exit("No hay usuario activo.");
        }else{
            $usuario = unserialize($_SESSION["user"]);
            $msg = "";
            if(!isset($_GET["archivo"])){
                $msg = "Error de referencia del archivo.";
            }else{
                $encontrado = false;
                foreach(scandir(RUTA_FICHEROS.$usuario->getid()."/") as $fichero){
                    if($fichero == $_GET["archivo"]){
                        $encontrado = true;
                        if(!unlink(RUTA_FICHEROS.$usuario->getid()."/".$fichero)){
                            $msg = "No se ha podido eliminar el fichero seleccionado";
                            break;
                        }else{
                            echo "<script language='JavaScript'>";
                            echo "alert('Fichero eliminado con éxito.');";
                            echo "</script>";
                            $msg = null;
                            break;
                        }
                    }
                }
                if(!$encontrado){
                    $msg = "Error al encontrar el fichero en el directorio";
                }
            }
        }
        ctlFileVerFicheros($msg);
    }
    
    function ctlFileRenombrar(){
        if(!isset($_SESSION["user"])){
            exit("No hay usuario activo.");
        }else{
            $msg = "";
            $usuario = unserialize($_SESSION["user"]);
            if(!isset($_GET["archivo"])){
                $msg = "Error al referenciar al archivo.";
            }else{
                $encontrado = false;
                foreach(scandir(RUTA_FICHEROS.$usuario->getId()."/") as $fichero){
                    if($_GET["archivo"] == $fichero){
                        $encontrado = true;
                        if(!rename(RUTA_FICHEROS.$usuario->getId()."/".$fichero, RUTA_FICHEROS.$usuario->getId()."/".$_GET["nuevo"])){
                            $msg = "No se ha podido renombrar el fichero seleccionado.";
                        }else{
                            echo "<script language='JavaScript'>";
                            echo "alert('Nombre actualizado.');";
                            echo "</script>";
                            $msg = null;
                        }
                    }
                }
                if(!$encontrado){
                    $msg = "No se ha encontrado el fichero.";
                }
            }
        }
        ctlFileVerFicheros($msg);
    }
    
    function ctlFileCompartir(){
        $fichero = $_GET['archivo'];
        $usuario = unserialize($_SESSION['user']);
        $rutaArchivo= RUTA_FICHEROS."/".$usuario->getId()."/".$fichero;
        $rutaencriptada = Encriptador::encripta($rutaArchivo);
        
        // Genero la ruta de descarga
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
            else
                $link = "http";
                $link .= "://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
                $link .="?orden=DescargaDirecta&fdirecto=".urlencode($rutaencriptada);
                echo "<script type='text/javascript'>prompt('Fichero [$fichero]. Enlace de descarga:', '$link');".
                    "document.location.href='index.php?operacion=VerFicheros';</script>";
                
                
    }
        
    function ctlFileDescargar(){
        $fichero = $_GET['archivo'];
        $usuario = unserialize($_SESSION['user']);
        $rutaArchivo= RUTA_FICHEROS."/".$usuario->getId()."/".$fichero;
        procesarDescarga($fichero, $rutaArchivo);
    }
    
    //Si pasas un enlace de descarga no requieres autenticación de usuario:
    function ctlFileDescargaDirecta(){
        if (!empty($_GET['fdirecto'])) {
            $rutaArchivo = Encriptador::desencripta($_GET['fdirecto']);
            $pos = strrpos ( $rutaArchivo , "/");
            $fichero = substr($rutaArchivo,$pos+1);
            procesarDescarga($fichero,$rutaArchivo);
        }
    }
    
    function procesarDescarga($fichero,$rutaArchivo){
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"".$fichero."\"");
        readfile($rutaArchivo);
    }
?>