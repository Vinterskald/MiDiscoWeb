<?php
    //Clase usuarios; modelo usando objetos de tipo usuario
    class usuarios{
        //Atributos de usuario:
        private $id;
        private $contra;
        private $nombre;
        private $correo;
        private $plan;
        private $perfil;
        private $estado;
        //------------------------------------------
        //Constructor:
        public function __construct(array $datos){
            $this->id = $datos[0];
            $this->contra = $datos[1];
            $this->nombre = $datos[2];
            $this->correo = $datos[3];
            $this->plan = $datos[4];
            $this->estado = $datos[5];
        }
        
        //-------------------------------------------------------
        //Funciones:
        //Redifino el método __get (método mágico):
        public function __get($atributo){
            if(property_exists($this, $atributo)) {
                return $this->$atributo;
            }
            trigger_error("Atributo no definido ", E_USER_NOTICE);
            return null;
        }
        
        //Redefino el método __set:
        public function __set($atributo, $valor){
            if(property_exists($this, $atributo)) {
                $this->$atributo = $valor;
            }
            trigger_error("Atributo no definido ", E_USER_NOTICE);
        }
        
        //------------------------------------------------------------------------------------------------------------------
    }
    //Clase administrador con sus funciones propias:
    class administrador extends usuarios{
        
    }
?>