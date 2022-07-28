<?php

namespace Model;

class Usuario extends ActiveRecord{
    //Base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email','password', 'telefono', 'admin', 'confirmado' , 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    public function validar($login = true){
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'El password es Obligatorio';
        }

        if($login){
            if (!$this->nombre) {
                self::$alertas['error'][] = 'El nombre del Cliente es Obligatorio';
            }
    
            if (!$this->apellido) {
                self::$alertas['error'][] = 'El apellido del Cliente es Obligatorio';
            }
    
             else if (strlen($this->password) < 6) {
                self::$alertas['error'][] = 'El password debe tener almenos 6 caracteres';
            }
    
            if (!$this->telefono) {
                self::$alertas['error'][] = 'El telefono es Obligatorio';
            }
        }

        return self::$alertas;
    }

    public function validarEmail($login = true){
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es Obligatorio';
        }

        return self::$alertas;
    }

    public function validarPassword($login = true){
        if (!$this->password) {
            self::$alertas['error'][] = 'El Campo es obligatorio';
        }else if (strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe tener almenos 6 caracteres';
        }

        return self::$alertas;
    }

    public function existeUsuario(){
        $query = "SELECT * FROM " .self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return $resultado;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){
        $resultado = password_verify($password, $this->password);

        if(!$this->confirmado || $this->confirmado === '0'){
            self::$alertas['error'][] = 'Password Incorrecto o no has confirmado tu cuenta';
        }else{
            return true;
        }
    }
}