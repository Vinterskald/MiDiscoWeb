<?php
    // DATOS DE CONEXION
    define('DBSERVER','127.0.0.1');
    define('DBNAME','discoweb');
    define('DBUSER','root');
    define('DBPASSWORD','DoManniskoPesten2_');
    
    //Tipo de acciones:
    define('GESTIONUSUARIOS','1');
    define('GESTIONFICHEROS','2');
    
    // Fichero donde se guardan los datos
    define('FILEUSER','app/dat/usuarios.json');
    define('FILEUSER_CIF', "app/dat/usuarioseguro.json"); 
    // Ruta donde se guardan los archivos de los usuarios
    // Tiene que tener permiso 0777 o permitir a Apache rwx
    define('RUTA_FICHEROS','app/dat/archivosuser/');
    
    // (0-Básico |1-Profesional |2- Premium| 3- Máster)
    const PLANES = ['Básico','Profesional','Premium','Máster'];
    //  Estado: (A-Activo | B-Bloqueado |I-Inactivo )
    const ESTADOS = ['A' => 'Activo','B' =>'Bloqueado', 'I' => 'Inactivo']; 
    
    //Ficheros máximos permitidos para cada plan:
    const LIMITE_FICHEROS = [50, 100, 200, 0];
    
    //Espacio límite permitido para cada plan:
    const LIMITE_ESPACIO = [10000, 20000, 50000, 0];
    
    //Acceso rápido a mensajes de incidencia:
    const TMENSAJES = [
        'USREXIST'     => "El ID del usuario ya existe",
        'USRERROR'     => "El ID de usuario solo puede tener letras y números",
        'PASSDIST'     => "Los valores de la contraseñas no son iguales",
        'USERLENG'     => "El ID del usuario debe tener de 5 a 10 caracteres",
        'PASSEASY'     => "La contraseña no es segura",
        'MAILERROR'    => "El correo electrónico no es correcto",
        'MAILREPE'     => "La dirección de correo ya está repetida",
        'NOVACIO'      => "Rellenar todos los campos",
        'LOGINERROR'   => "Error: usuario y / o contraseña no válidos.",
        'USERNOACTIVO' => "Su usuario esta bloqueado o inactivo",
        'USERSAVE'     => "Nuevo Usuario almacenado.",
        'USERNOSAVE'   => "Error: No se puede añadir el usuario",
        'USERUPDATE'   => "Se han modificado los datos del Usuario",
        'ERRORUPDATE'  => "Error al modificar el usuario",
        'ERRORADDUSER' => "Error: No se puede añadir el usuario",
        'USERREG'      => "Usuario registrado. Introduzca sus datos",
        'USERDEL'      => "Usuario eliminado.",
        'ERRORDEL'     => "Error no se puede eliminar el usuario.",
        'ERRORUSER'    => "Error: no se ha determinado usuario o el usuario no se puede eliminar."
    ];
    
    //Funciones para el checkeo de la contraseña:
    function hayMayusculas($clave){
        if(strtolower($clave) != $clave){
            return true;
        }
        return false;
    }
    
    function hayMinusculas($clave){
        if(strtoupper($clave) != $clave){
            return true;
        }
        return false;
    }
    
    function hayDigito($clave){
        return preg_match("/\d/", $clave);
    }
    
    function hayNoAlfanumeric($clave){
        if(!ctype_alnum($clave)){ //Si todos los caracteres fueran alfanuméricos, no sería correcto.
            return true;
        }
        return false;
    }
?>