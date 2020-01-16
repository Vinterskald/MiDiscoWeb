<?php
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
    function ctlFileVerFicheros(){
        include_once "plantilla/verficheros.php";
        exit();
    }
    
    function ctlFileNuevo(){
        $max_espacio = 0;
        $max_archivos = 0;
        foreach($_SESSION['tusuarios'] as $clave => $dato){
            if($clave == $_SESSION["user"]){
                $max_espacio = LIMITE_ESPACIO[$dato[3]];
                $max_archivos = LIMITE_FICHEROS[$dato[3]];
            }
        }
        $espacio_restante = (int) ($max_espacio - tam_dir(RUTA_FICHEROS.$_SESSION["user"]."/"));
        
        echo $_FILES["archivo"]["error"];
        
         
        switch($_FILES["archivo"]["error"]){
            case 0:
                $dir_archivo = RUTA_FICHEROS.$_SESSION["user"]."/".basename($_FILES["archivo"]["name"]);
                if(file_exists($dir_archivo)){
                    echo "<script language='JavaScript'>";
                    echo "alert('El archivo ya existe.');";
                    echo "</script>";
                    break;
                }
                if($_FILES["archivo"]["size"] > $espacio_restante){
                    echo "<script language='JavaScript'>";
                    echo "alert('Error: el archivo excede tu límite de espacio en disco.');";
                    echo "</script>";
                    break;
                }
                if(count(preg_grep("/^([^.])/", scandir(RUTA_FICHEROS.$_SESSION["user"]."/"))) > $max_archivos){
                    echo "<script language='JavaScript'>";
                    echo "alert('Error: no puedes subir más archivos.');";
                    echo "</script>";
                    break;
                }
                if(move_uploaded_file($_FILES["archivo"]["tmp_name"], $dir_archivo)){
                    echo "<script language='JavaScript'>";
                    echo "alert('Archivo subido con éxito.');";
                    echo "</script>";
                    break;
                }else{
                    echo "<script language='JavaScript'>";
                    echo "alert('Error: no se ha podido subir el archivo.');";
                    echo "</script>";
                    break;
                }
                
            case 3:
                echo "<script language='JavaScript'>";
                echo "alert('Error: no se ha podido completar la subida.');";
                echo "</script>"; 
                break;
                
            case 4:
                echo "<script language='JavaScript'>";
                echo "alert('No has elegido ningún archivo a subir.');";
                echo "</script>"; 
                break;
             
            case 7:
                echo "<script language='JavaScript'>";
                echo "alert('Error de permisos en el directorio de subida; no se puede subir el archivo.');";
                echo "</script>"; 
                break;
                
            default:
                echo "<script language='JavaScript'>";
                echo "alert('No se ha podido subir el archivo.');";
                echo "</script>"; 
                break;
        }
        ctlFileVerFicheros();
    }
    
    function ctlFileBorrar(){
        
    }
    
    function ctlFileRenombrar(){
        
    }
    
    function ctlFileCompartir(){
        
    }
?>