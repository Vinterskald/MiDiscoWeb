<?php
    class Encriptador{
        // Clave para encriptar 
        static private $clave  = 'El módulo de Desarrollo Web en entorno servidor es lo más';
        //Metodo de encriptación
        static private $metodo = 'aes-256-cbc';
        // Vector de inicialización cambiarlo por otro con el valor que se obtiene de getIV
        static private $ivcod = "1CpHOm+2qHjdFvNV4VJuvg==";
        
        public static function encripta($texto){
            $iv =  base64_decode (self::$ivcod);
            return openssl_encrypt($texto,self::$metodo,self::$clave,false,$iv);
        }
    
    
        public static function desencripta($texto){
            $iv =  base64_decode (self::$ivcod);
            return openssl_decrypt($texto,self::$metodo,self::$clave,false,$iv);
        }
        
        // Genera un nuevo vector de inicialización.
        public static function getIV(){
            return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$metodo)));
        }
    }
?>