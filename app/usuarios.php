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
        //Getter y setter manuales:
        
        //Getters:
        public function getId(){
            return $this->id;
        }
        public function getContra(){
            return $this->contra;
        }
        public function getNombre(){
            return $this->nombre;
        }
        public function getCorreo(){
            return $this->correo;
        }
        public function getPlan(){
            return $this->plan;
        }
        public function getPerfil(){
            return $this->perfil;
        }
        public function getEstado(){
            return $this->estado;
        }
        //Setters:
        public function setContra($contra){
            $this->contra = $contra;
        }
        public function setNombre($nombre){
            $this->nombre = $nombre;
        }
        public function setCorreo($correo){
            $this->correo = $correo;
        }
        public function setPlan($plan){
            $this->plan = $plan;
        }
        public function setPerfil($perfil){
            $this->perfil = $perfil;
        }
        public function setEstado($estado){
            $this->estado = $estado;
        }
        //------------------------------------------------------------------------------------------------------------------
    }
?>