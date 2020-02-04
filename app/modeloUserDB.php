<?php
    include_once 'config.php';
    include_once 'cifrador.php';
    
    class ModeloUserDB{
        private static $dbh = null; 
        private static $consulta_user = "SELECT id FROM usuarios WHERE id = ?";
        private static $consulta_email = "SELECT email FROM usuarios WHERE email = ?";
             
        public static function init(){
            if(self::$dbh == null){
                try{
                    // Cambiar  los valores de las constantes en config.php
                    $dsn = "mysql:host=".DBSERVER.";dbname=".DBNAME.";charset=utf8";
                    self::$dbh = new PDO($dsn,DBUSER,DBPASSWORD);
                    // Si se produce un error se genera una excepción;
                    self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }catch(PDOException $e){
                    echo "Error de conexión ".$e->getMessage();
                    exit();
                }   
            } 
        }
        
        // Comprueba usuario y contraseña son correctos (boolean)
        public static function OkUser($user,$clave){    
            $stmt = self::$dbh->prepare(self::$consulta_user);
            $stmt->bindValue(1,$user);
            $stmt->execute();  
            if($stmt->rowCount() > 0 ){
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $fila = $stmt->fetch();
                $clavecifrada = $fila['clave'];
                if(cifrador::verificar($clave, $clavecifrada)){
                    return true;
                }
            } 
            return false;
        }
        
        // Comprueba si ya existe un usuario con ese identificador
        public static function ExisteID(String $user):bool{
            $stmt = self::$dbh->prepare(self::$consulta_user);
            $stmt->bindValue(1, $user);
            $stmt->execute();
            if($stmt->rowCount() > 0){ //Si el conteo de filas es mayor de 0 implica que sí lo ha encontrado.
                return true;
            } 
            return false;
        }
        
        //Comprueba si existe el email en la BD
        public static function ExisteEmail(String $user):bool{
            $stmt = self::$dbh->prepare(self::$consulta_email);
            $stmt->bindValue(1, $user);
            $stmt->execute();
            if($stmt->rowCount() > 0){
                return true;
            }
            return false;
        }
        
        
        /*
         * Chequea si hay error en los datos antes de guardarlos
         */
        public static function ErrorValoresAlta($user,$clave1, $nombre, $email, $plan, $estado){
            if(modeloExisteID($user))                         return TMENSAJES['USREXIST'];
            if(preg_match("/^[a-zA-Z0-9]+$/", $user) == 0)    return TMENSAJES['USRERROR'];
            if($clave1 != $clave2)                            return TMENSAJES['PASSDIST'];
            if(!esClaveSegura($clave1))                       return TMENSAJES['PASSEASY'];
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR']; //Filtro de variables nativo de PHP.
            if(modeloExisteEmail($user))                      return TMENSAJES['MAILREPE'];
            return false;
        }
        
        public static function ErrorValoresModificar($user, $clave1, $nombre, $email, $plan, $estado){
            if($clave1 != $clave2 )                           return TMENSAJES['PASSDIST'];
            if(!modeloEsClaveSegura($clave1))                 return TMENSAJES['PASSEASY'];
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
            //Si se cambia el email
            $emailantiguo = modeloGetEmail($user);
            if($email != $emailantiguo && existeEmail($user)) return TMENSAJES['MAILREPE'];
            return false;
        }
        
        /*
         * Comprueba que la contraseña es segura
         */
        public static function EsClaveSegura(String $clave):bool {
            if(empty($clave)) return false;
            if(strlen($clave) < 8 ) return false;
            if(!hayMayusculas($clave) || !hayMinusculas($clave)) return false;
            if(!hayDigito($clave)) return false;
            if(!hayNoAlfanumerico($clave)) return false;         
            return true;
        }
             
        //Devuelve el plan de usuario (String)
        public static function ObtenerTipo($user):string{
            $query = "SELECT plan FROM usuarios WHERE id = ?";
            $stmt = self::$dbh->prepare($query);
            $stmt->bindValue(1, $user);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if($stmt->execute()){
                while($fila = $stmt->fetch()){
                    $tipo = PLANES[$fila["plan"]];
                    break;
                }
                return $tipo;
            }
            return null;
        }
        
        //Borrar un usuario (boolean)
        public static function UserDel($user):bool{
            $query = "DELETE FROM usuarios WHERE id = ?";
            $stmt = self::$dbh->prepare($query);
            $stmt->bindValue(1, $user);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            if($stmt->execute()){
                return true;
            }
            return false;
        }
        //Añadir un nuevo usuario (boolean)
        public static function UserAdd(string $user, string $pass, string $nom, string $correo, int $plan, string $estado):bool{
            if(self::ExisteID($user)) return false;
            if(self::ExisteEmail($correo)) return false;
            $error = self::ErrorValoresAlta($user, $pass, $nom, $correo, $plan, $estado);
            if($error == false){
                $query = "INSERT INTO usuarios VALUES(?, ?, ?, ?, ?, ?)";
                $stmt = self::$dbh->prepare($query);
                $stmt->bindValue(1, $user);
                $stmt->bindValue(2, cifrador::cifrar($pass));
                $stmt->bindValue(3, $nom);
                $stmt->bindValue(4, $correo);
                $stmt->bindValue(5, $plan);
                $stmt->bindValue(6, $estado);
                if($stmt->execute()){
                    return true;
                }
            }
            return false; 
        }
        
        //Actualizar un nuevo usuario (boolean)
        public static function UserUpdate(string $user, string $clave, string $nombre, string $email, int $plan, string $estado):bool{
            $queryMaster = "UPDATE usuarios SET clave = ?, nombre = ?, email = ?, plan = ?, estado = ? WHERE id = ?";
            $queryUser = "UPDATE usuarios SET clave = ?, nombre = ?, email = ?, plan = ? WHERE id = ?";
            if(PLANES[$plan] == "Máster"){
                $stmt = self::$dbh->prepare($queryMaster);
                $stmt->bindValue(1, cifrador::cifrar($clave));
                $stmt->bindValue(2, $nombre);
                $stmt->bindValue(3, $email);
                $stmt->bindValue(4, $plan);
                $stmt->bindValue(5, $estado);
                $stmt->bindValue(6, $user);
                if($stmt->execute()){
                    return true;
                }
            }else{
                $stmt = self::$dbh->prepare($queryUser);
                $stmt->bindValue(1, cifrador::cifrar($clave));
                $stmt->bindValue(2, $nombre);
                $stmt->bindValue(3, $email);
                $stmt->bindValue(4, $plan);
                $stmt->bindValue(5, $user);
                if($stmt->execute()){
                    return true;
                }
            }
            return false; 
        }
        
        
        // Tabla de todos los usuarios para visualizar
        public static function GetAll():array{
            // Genero los datos para la vista que no muestra la contraseña ni los códigos de estado o plan
            // sino su traducción a texto  PLANES[$fila['plan']],
            $stmt = self::$dbh->query("SELECT * FROM usuarios");
            
            $tUserVista = [];
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while ($fila = $stmt->fetch()){
                $datosuser = [ 
                    $fila['nombre'],
                    $fila['email'], 
                    PLANES[$fila['plan']],
                    ESTADOS[$fila['estado']]
                   ];
                $tUserVista[$fila['id']] = $datosuser;       
            }
            return $tUserVista;
        }
        
        // Datos de un usuario para visualizar
        public static function UserGet($userid):array{
            $stmt = self::$dbh->query("SELECT * FROM usuarios where id = ?");
            $userVista = [];
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            while($fila = $stmt->fetch()){
                $userVista = [$fila["id"], $fila["clave"], $fila["nombre"], $fila["email"], PLANES[$fila["plan"]], ESTADOS[$fila["estado"]]];
            }
            return $userVista;
        }
        
        public static function closeDB(){
            self::$dbh = null;
        }
    }
 ?>