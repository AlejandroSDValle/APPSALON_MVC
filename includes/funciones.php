<?php

function d($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//Verifica si el usuario esta autenticado
function isAuth(): void{
    if(!isset($_SESSION['login'])){
        header('Location: /');
    }
}

function isAdmin(){
    if(!isset($_SESSION['admin'])){
        header('Location: /');
    }
}

function ultimo($actual, $proximo){
    if($actual !== $proximo){
        return true;
    }
    return false;
}

