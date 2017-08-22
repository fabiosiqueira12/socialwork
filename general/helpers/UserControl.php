<?php 

namespace general\helpers;

use general\controllers\UsuarioController;

class UserControl{

    public function comparaSenha($senha_digitada,$senha_guardada){
        if (md5($senha_digitada) == $senha_guardada){
            return true;
        }else{
            return false;
        }
    }

    public function retornaUsuarioLogado(){
        $usuario = null;

        if ($this->estaLogado()){

            $usuarioController = new UsuarioController();

            $usuario = $usuarioController->retornaPorToken($this->getToken());

        }

        return $usuario;
    }

    public function deslogar(){
        unset($_SESSION['usuario']);
    }

    public function estaLogado(){
        if (isset($_SESSION["usuario"])){
            return true;
        }else {
            return false;
        }
    }

    public function createToken(){
        //String com valor possíveis do resultado, os caracteres pode ser adicionado ou retirados conforme sua necessidade
        $basic = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

        $return= "";

        for($count= 0; 20 > $count; $count++){
            //Gera um caracter aleatorio
            $return.= $basic[rand(0, strlen($basic) - 1)];
        }

        return $return;
    }

    
    //Funções Privadas

    private function getToken(){
        return $_SESSION["usuario"];
    }
    
}