<?php

namespace general\helpers;

class ImageControl
{
    private $tamanhoMaximo = 1000000; //Tamanho máximo do arquivo em bytes
    private $pasta = "fotos/";

    function __construct()
    {
        if (is_dir("fotos")) {
            
        } else {
            mkdir("fotos");
        }
    }
    
    public function verificaSeEhImagem($typeFoto)
    {
        if (preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $typeFoto)) {
            return true;
        } else {
            return false;
        }
        
    }

    public function verificaSeEhMenor($sizeFoto)
    {

        if ($sizeFoto < $this->tamanhoMaximo) {
            return true;
        } else {
            return false;
        }
    }

    public function salvaImagem($imagem)
    {
    
        $extensao = $this->retornaExtensao($imagem["name"]);
        $nomeImagem = $this->geraNomeImagem($extensao);

        $caminhoImagem = $this->pasta . $nomeImagem;

        // Faz o upload da imagem para seu respectivo caminho
        move_uploaded_file($imagem["tmp_name"], $caminhoImagem);

        return $caminhoImagem;
    }


    //Funções privadas

    private function retornaExtensao($imagemNome)
    {

        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $imagemNome, $ext);
        return $ext[1];
    }

    private function geraNomeImagem($extensao)
    {
        return md5(uniqid(time())) . "." . $extensao;
    }
}
