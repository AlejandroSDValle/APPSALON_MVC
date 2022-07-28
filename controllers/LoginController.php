<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class loginController{
    public static function login(Router $router){
        $iniciada = $_SESSION['login'] ?? null;
        if($iniciada){
            if($_SESSION['admin']){
                header('Location: /admin');
            }else{
                header('Location: /cita');
            }
        }
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validar(false);

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario){
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        //AUTENTICAR AL USUARIO
                        if(!isset($_SESSION)){
                            session_start();
                        }

                        $_SESSION['id']  = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        if($usuario->admin === '1'){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        }else{
                            header('Location: /cita');
                            
                        }
                    }
                }else{
                    Usuario::setAlerta('error', 'Este usuario no esta registrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

       $router->render('/auth/login', [
        'alertas'=> $alertas
       ]); 
    }

    public static function logout(){
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado == '1'){
                    $usuario->crearToken();
                    $usuario->guardar();

                    ////Enviar email
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Revisa tu email');
                }else{
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/olvide', [
            'alertas'=> $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;
        $token = s($_GET['token']);
        
        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario) || $usuario->token == ''){
            Usuario::setAlerta('error', 'Lo siento no puedes restablecer tu password');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
                d($usuario);
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/recuperar-password', [
            'alertas'=> $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        
        $usuario = new Usuario();
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar();

            //revisar que alerta ste vacio
            if(empty($alertas)){
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    $usuario->hashPassword();
                    $usuario->crearToken();
                    
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarEmail();

                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }else{
                        echo "fallo pa";
                    }
                }
            }
        }

        $router->render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){ $router->render('/auth/mensaje'); }

    public static function confirmar(Router $router){

        $alertas = [];

        $token = s($_GET['token']);
        
        $usuario = Usuario::where('token', $token);

        if(empty($usuario) || $usuario->token == ''){
            Usuario::setAlerta('error', 'Lo siento, No pudimos confirmar tu cuenta');
        }else{
            $usuario->confirmado = "1";
            $usuario->token = '';

            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();
        $router->render('/auth/confirmar-cuenta', [
            'alertas' =>$alertas
        ]);
    }
}