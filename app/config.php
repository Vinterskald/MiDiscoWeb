<?php
    define ('GESTIONUSUARIOS','1');
    define ('GESTIONFICHEROS','2');
    
    // Fichero donde se guardan los datos
    define('FILEUSER','app/dat/usuarios.json');
    define('FILEUSER_CIF', "app/dat/usuarioseguro.json"); 
    // Ruta donde se guardan los archivos de los usuarios
    // Tiene que tener permiso 0777 o permitir a Apache rwx
    define('RUTA_FICHEROS','app/dat/archivosuser/');
    
    // (0-Básico |1-Profesional |2- Premium| 3- Máster)
    const  PLANES = ['Básico','Profesional','Premium','Máster'];
    //  Estado: (A-Activo | B-Bloqueado |I-Inactivo )
    const  ESTADOS = ['A' => 'Activo','B' =>'Bloqueado', 'I' => 'Inactivo']; 
    
    // Definir otras constantes
    
    //Ficheros máximos permitidos para cada plan:
    const LIMITE_FICHEROS = [50, 100, 200, 0];
    
    //Espacio límite permitido para cada plan:
    const LIMITE_ESPACIO = [10000, 20000, 50000, 0];
    
?>