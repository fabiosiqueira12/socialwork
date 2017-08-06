<?php 

namespace general\helpers;

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

            $usuarioController = new \general\controllers\UsuarioController();

            $usuario = $usuarioController->retornarPorId($this->getId());

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

    
    //Funções Privadas

    private function getId(){
        return $_SESSION["usuario"];
    }
    
}