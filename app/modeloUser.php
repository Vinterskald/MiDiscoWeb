<?php 
    /* DATOS DE USUARIO
    • Identificador (5 a 10 caracteres, no debe existir previamente, solo letras y números)
    • Contraseña (8 a 15 caracteres, debe ser segura)
    • Nombre (Nombre y apellidos del usuario)
    • Correo electrónico (Valor válido de dirección correo, no debe existir previamente)
    • Tipo de Plan (0-Básico |1-Profesional |2- Premium| 3- Máster)
    • Estado: (A-Activo | B-Bloqueado |I-Inactivo)
    */
    // Inicializo el modelo 
    // Cargo los datos del fichero a la session
    function modeloUserInit(){   
        /*
        $tusuarios = [ 
             "admin"  => ["12345"      ,"Administrado"   ,"admin@system.com"   ,3,"A"],
             "user01" => ["user01clave","Fernando Pérez" ,"user01@gmailio.com" ,0,"A"],
             "user02" => ["user02clave","Carmen García"  ,"user02@gmailio.com" ,1,"B"],
             "yes33" =>  ["micasa23"   ,"Jesica Rico"    ,"yes33@gmailio.com"  ,2,"I"]
            ];
        */
       
        $datosjson = @file_get_contents(FILEUSER) or die("ERROR al abrir fichero de usuarios");
        $tusuarios = json_decode($datosjson, true);
        $_SESSION['tusuarios'] = $tusuarios;
        
        foreach($tusuarios as $clave => $dato){
            if(!file_exists(RUTA_FICHEROS."$clave")){
                //En Linux habría que añadir los permisos
                if(!mkdir(RUTA_FICHEROS."$clave")){
                    die("Error al crear el directorio de usuario.");
                }             
            }
        }
    }
    
    // Comprueba usuario y contraseña (boolean)
    function modeloOkUser($user, $contra){
        foreach($_SESSION["tusuarios"] as $clave => $dato){
            if($clave == $user){
                if($_SESSION["tusuarios"][$clave][0] == $contra){
                    return true;
                }
            }
        }
        return false;
    }
    
    // Devuelve el plan de usuario (String)
    function modeloObtenerTipo($user){
        //Si el usuario fuera el administrador:
        if($user == "admin"){
            return "Máster";
        }else{
            //Sino, busco al usuario entre la información decodificada del JSON para devolver 
            //el plan correspondiente, que determina los derechos de usuario (sin implementar hasta añadir otros niveles de usuario)
            
        }
    }
    
    // Borrar un usuario (boolean)
    function modeloUserDel($user){
        if($user == $_SESSION["user"]){
            return false;
        }
        
        foreach($_SESSION['tusuarios'] as $clave => $dato){  
            if($clave == $user){
                unset($_SESSION["tusuarios"][$clave]);
                rmdir(RUTA_FICHEROS.$clave);
                modeloUserSave();
                return true;
            }
        }
        
        return false;
    }
    // Añadir un nuevo usuario (boolean)
    function modeloUserAdd($user, $password, $nombre, $correo, $plan, $estado){
        foreach($_SESSION['tusuarios'] as $clave => $dato){
            if($clave == $user){
                return false;
            }
        }
        $_SESSION['tusuarios'][$user] = [$password, $nombre, $correo, $plan, $estado];
        modeloUserSave();
        return true;
    }
    
    //Actualizar un nuevo usuario (boolean)
    function modeloUserUpdate($user, $password, $nombre, $correo, $plan, $estado){
        foreach($_SESSION['tusuarios'] as $clave => $dato){
            if($clave == $user){
                $_SESSION["tusuarios"][$user] = [$password, $nombre, $correo, $plan, $estado];
                modeloUserSave();
                return true;
            }
        }
        return false;
    }
    
    // Tabla de todos los usuarios para visualizar
    function modeloUserGetAll(){
        //Genero lo datos para la vista que no muestra la contraseña ni los códigos de estado o plan
        //sino su traducción a texto
        $tuservista = [];
        foreach($_SESSION['tusuarios'] as $clave => $datosusuario){
            $tuservista[$clave] = [$datosusuario[1],
                                   $datosusuario[2],
                                   PLANES[$datosusuario[3]],
                                   ESTADOS[$datosusuario[4]]
                                   ];
        }
        return $tuservista;
    }
    // Datos de un usuario para visualizar
    function modeloUserGet($user){
          $uservista = [];
          $encontrado = false;
          foreach($_SESSION['tusuarios'] as $clave => $datosusuario){
              if($clave == $user){
                  $uservista = [$datosusuario[1], $datosusuario[2], PLANES[$datosusuario[3]], ESTADOS[$datosusuario[4]]];
                  $encontrado = true;
                  break;
              }
          }
          if($encontrado){
              return $uservista;
          }else{
              return $encontrado;
          }
    }
    
    // Vuelca los datos al fichero
    function modeloUserSave(){
        $datosjon = json_encode($_SESSION['tusuarios']);
        file_put_contents(FILEUSER, $datosjon) or die ("Error al escribir en el fichero.");
        fclose(FILEUSER);
    }
?>