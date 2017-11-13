<?php


header('Content-Type: text/html; charset=utf-8');
//Aqui fica a configuração de todas as rotas da aplicação

$app->get('/', function ($request, $response, $args) {
    $userControl = new \general\helpers\UserControl();
    if ($userControl->estaLogado()) {
         return $response->withRedirect('home');
    } else {
        
        return $this->view->render($response, 'autenticacao/entrar.php',
        ["title" => "Rede Social de teste",
        "descricao" => "Rede Social de teste.",
        ]);
    }
});

$app->get('/login', function ($request, $response, $args) {

        return $this->view->render($response, 'autenticacao/entrar.php',
        ["title" => "Rede Social de teste",
        "descricao" => "Rede Social de teste."
        ]);
});

$app->get('/cadastro', function ($request, $response, $args) {
    
    return $this->view->render($response, 'autenticacao/cadastro.php',
     ["title" => "Rede Social de teste",
                "descricao" => "Rede Social de teste."
     ]);
});

$app->get('/home', function ($request, $response, $args) {
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    $userControl = new \general\helpers\UserControl();
    if ($userControl->estaLogado()) {
        $postController = new \general\controllers\PostController();
         return $this->view->render($response, 'home/index.php',
        ["title" => "Rede Social de teste",
                "descricao" => "Rede Social de teste.",
                "url" => $url,
                "usuario" => $userControl->retornaUsuarioLogado(),
                'posts' => $postController->retornaQuantidadePorUsuario($userControl->retornaUsuarioLogado()->getId())
        ]);
    } else {
        return $response->withRedirect('login');
    }
});

$app->get('/home/friends', function ($request, $response, $args) {
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    $userControl = new \general\helpers\UserControl();
    if ($userControl->estaLogado()) {
        $postController = new \general\controllers\PostController();
         return $this->view->render($response, 'home/index.php',
        ["title" => "Rede Social de teste",
                "descricao" => "Rede Social de teste.",
                "url" => $url,
                "usuario" => $userControl->retornaUsuarioLogado(),
                'posts' => $postController->retornaPostsDeAmigos($userControl->retornaUsuarioLogado()->getId())
        ]);
    } else {
        return $response->withRedirect('login');
    }
});

$app->get('/perfil/{user}', function ($request, $response, $args) {

    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    $userControl = new \general\helpers\UserControl();

    if ($userControl->estaLogado()) {
        $usuarioperfil = null;
        if ($args['user'] == $userControl->retornaUsuarioLogado()->getUser()) {
            $usuarioperfil =  $userControl->retornaUsuarioLogado();
        } else {
            $usuarioController = new \general\controllers\UsuarioController();
            $usuarioperfil = $usuarioController->retornaUsuarioPorLogin($args['user']);
        }
        $postController = new \general\controllers\PostController();
        $posts = $postController->retornaQuantidadePorUsuario($usuarioperfil->getId());
        $quantidadeDePosts = $postController->retornaQuantidadeDePosts($usuarioperfil->getId());

        $localController = new \general\controllers\LocalizacaoController();
        $local = $localController->retornaPorUsuario($usuarioperfil->getId());
        $end = $local->logradouro . ", " . $local->bairro . ", " . $local->cidade . " - ".$local->uf . ", ".$local->cep;

        return $this->view->render($response, 'perfil/meu-perfil.php',
        ["title" => "Rede Social de teste",
                "descricao" => "Rede Social de teste.",
                "url" => $url,
                "usuario" => $userControl->retornaUsuarioLogado(),
                "usuarioperfil" => $usuarioperfil,
                "posts" => $posts,
                "quantidadeposts" => $quantidadeDePosts,
                "end" => $end
        ]);
    } else {
        return $response->withRedirect('login');
    }
});

$app->get('/amigos', function ($request, $response, $args) {
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
    $userControl = new \general\helpers\UserControl();

    if ($userControl->estaLogado()) {
        return $this->view->render($response, 'perfil/amigos.php',
        ["title" => "Rede Social de teste",
        "descricao" => "Rede Social de teste.",
        "url" => $url,
        "usuario" => $userControl->retornaUsuarioLogado()
        ]);
    }else{
        return $response->withRedirect('login');
    }
});

$app->post('/busca', function ($request, $response, $args) {
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
    $userControl = new \general\helpers\UserControl();

    if ($userControl->estaLogado()) {  
       
        $tipoBusca = $_POST["check-busca"];
        $query = $_POST["query"];
        $buscaControl = new \general\controllers\BuscaController();
        if (intval($tipoBusca) == 1){
            if ($query == "") {
                $busca = [];
            }else {
                $busca = $buscaControl->retornaPessoas($query);
            }
            return $this->view->render($response, 'busca/pessoas.php',
            ["title" => "Rede Social de teste",
                "descricao" => "Rede Social de teste.",
                "url" => $url,
                "usuario" => $userControl->retornaUsuarioLogado(),
                'busca' => $busca,
                "query" => $query
            ]);

        }else {
            
            if ($query == ""){
                $busca = [];
            }else{
                $busca = $buscaControl->retornaPosts($query);
            }
            
            return $this->view->render($response, 'busca/posts.php',
            ["title" => "Rede Social de teste",
                "descricao" => "Rede Social de teste.",
                "url" => $url,
                "usuario" => $userControl->retornaUsuarioLogado(),
                'busca' => $busca,
                "query" => $query
            ]);

        }
 

    }else{
        return $response->withRedirect('login');
    }    

});

//Rotas de Formulário

$app->post("/logar", function ($request) {
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
    
    $loginOrEmail = $_POST["login"];
    $senha = $_POST["senha"];
    $userController = new \general\controllers\UsuarioController();
    $usuario = null;
    if (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $loginOrEmail)) {
        $usuario = $userController->retornaUsuarioPorLogin($loginOrEmail);
    } else {
        $usuario = $userController->retornaUsuarioPorEmail($loginOrEmail);
    }

    if ($usuario != null) {
        $userControl = new \general\helpers\UserControl();
        if ($userControl->comparaSenha($senha, $usuario->getSenha())) {
            $_SESSION["usuario"] = $usuario->getToken();
            echo json_encode(["message" => "Login efetuado com sucesso",
            "type" => 1,
            "redirect" => $url ."/home"]);
        } else {
            echo json_encode(["message" => "Senha Incorreta","type" => 0]);
        }
    } else {
         echo json_encode(["message" => "Login ou email inválido","type" => 0]);
    }
});

$app->post("/deslogar", function ($request) {

    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
    $userControl = new \general\helpers\UserControl();
    $userControl->deslogar();

    echo json_encode(["message" => $url,"type" => 1]);
});

$app->post("/desativar", function ($request) {

    $id_user = $_POST["iduser"];
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
    $usuarioController = new \general\controllers\UsuarioController();
    $usuarioController->desativar($id_user);

    echo json_encode(["message" => $url . "/login","type" => 1]);
});

$app->post("/ativar", function ($request) {

    $usuarioController = new \general\controllers\UsuarioController();
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    
    
    if (empty($email)) {
            echo json_encode(["message" => "Digite algo para usuário","type" => 0]);
    } else {
        $usuario = $usuarioController->retornaUsuarioDesativado($email);

        if ($usuario != null) {
            $userControl = new \general\helpers\UserControl();
        
            if ($userControl->comparaSenha($senha, $usuario->getSenha())) {
                $usuarioController->ativar($usuario->getId());
                echo json_encode(["message" => "Usuário ativado","type" => 1]);
            } else {
                echo json_encode(["message" => "Senha Incorreta","type" => 0]);
            }
        } else {
            echo json_encode(["message" => "Usuário não encontrado","user" => $senha,"teste" => $email,"type" => 0]);
        }
    }
});

$app->post("/cadastrar", function ($request) {

    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
    $nome = $_POST["nome"];
    $user = $_POST["user"];
    $sexo = $_POST["sexo"];
    $descricao = $_POST["descricao"];
    $email = $_POST["email"];
    $senha = $_POST["senha"];
    $repitaSenha = $_POST["repitasenha"];

    if (empty($nome)) {
        echo json_encode([message => "Digite seu nome","type" => 0]);
    } else {
        if (empty($user)) {
            echo json_encode("Digite Seu usuário");
        } else {
            if (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $email)) {
                echo json_encode([message => "Digite um email válido","type" => 0]);
            } else {
                if (strcmp($senha, $repitaSenha) == 0) {
                    $usuario = new general\models\Usuario();
                    $usuario->setNome($nome);
                    $usuario->setUser($user);
                    $usuario->setEmail($email);
                    $usuario->setSexo($sexo);
                    $usuario->setDescricao($descricao);
                    $usuario->setSenha(md5($senha));

                    $controllerUser = new general\controllers\UsuarioController();
                    $controllerUser->novo($usuario);

                    echo json_encode(["message" => "Usuário cadastrado com sucesso,você já pode logar na rede social",
                    "type" => 1,
                    "redirect" => $url], JSON_UNESCAPED_UNICODE);
                } else {
                    echo json_encode(["message" => "As senhas não são iguais","type" => 0]);
                }
            }
        }
    }
});

$app->post("/editarusuario", function ($request) {
    
    $urlLocal = explode('/',$_SERVER["REQUEST_URI"]);
    $url = $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["HTTP_HOST"] . "/" . $urlLocal[1];
    
     $id_user = $_POST["iduser"];
     $nome = $_POST["nome"];
     $sexo = $_POST["sexo"];
     $descricao = $_POST["descricao"];
     $email = $_POST["email"];
     $tipoUsuario = $_POST["tipousuario"];
     $senha = $_POST["senha"];
     $repitaSenha = $_POST["repitasenha"];

    if (empty($id_user)) {
        echo json_encode([message => "Erro, tente novamente mais tarde","type" => 0]);
    } else {
        if (empty($nome)) {
            echo json_encode([message => "Digite seu nome","type" => 0]);
        } else {
            if (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $email)) {
                echo json_encode([message => "Digite um email válido","type" => 0]);
            } else {
                $usuarioController = new \general\controllers\UsuarioController();
                $usuarioEditar = $usuarioController->retornarPorId($id_user);
                $usuarioEditar->setNome($nome);
                $usuarioEditar->setSexo($sexo);
                $usuarioEditar->setEmail($email);
                $usuarioEditar->setDescricao($descricao);
                $usuarioEditar->setTipo($tipoUsuario);

                if (empty($senha)) {
                    $usuarioController->editar($usuarioEditar);

                    echo json_encode(["message" => "Usuário editado com sucesso",
                    "type" => "1",
                    "redirect" => $url . "/perfil" . "/" . $usuarioEditar->getUser()]);
                } else {
                    if (strcmp($senha, $repitaSenha) == 0) {
                        $usuarioEditar->setSenha(md5($senha));
                        $usuarioController->editar($usuarioEditar);
                        echo json_encode(["message" => "Usuário editado com sucesso",
                        "type" => "1",
                        "redirect" => $url . "/perfil" . "/" . $usuarioEditar->getUser()]);
                    } else {
                        echo json_encode(["message" => "As senhas não são iguais","type" => 0]);
                    }
                }
            }
        }
    }
});

$app->post("/fotoperfil", function ($request) {

    $foto = $_FILES["imagem"];
    $userid = $_POST["userid"];

    if (!empty($foto["name"])) {
        $imagemControl = new \general\helpers\ImageControl();
        $userController = new \general\controllers\UsuarioController();

        if ($imagemControl->verificaSeEhImagem($foto["type"])) {
            if ($imagemControl->verificaSeEhMenor($foto["size"])) {
                $caminhoImagem = $imagemControl->salvaImagem($foto);
                $userController->salvaImagePerfil($caminhoImagem, $userid);
                echo json_encode("Imagem foi atualizada", JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode("O tamanho é máximo é de 1mb", JSON_UNESCAPED_UNICODE);
            }
        } else {
            echo json_encode("Arquivo incorreto.", JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode("Selecione uma imagem", JSON_UNESCAPED_UNICODE);
    }
});


$app->post("/novopost", function ($request) {

    $userid = $_POST["userid"];
    $titulo = $_POST["titulo"];
    $texto = $_POST["texto"];
    $foto = isset($_FILES["imagem-post"]) ? $_FILES["imagem-post"] : null;

    if (empty($userid)) {
         echo json_encode("Erro ao postar.", JSON_UNESCAPED_UNICODE);
    } else {
        if (empty($titulo)) {
            echo json_encode("Digite algo no título.", JSON_UNESCAPED_UNICODE);
        } else {
            if (empty($texto)) {
                 echo json_encode("Digite algo para o texto.", JSON_UNESCAPED_UNICODE);
            } else {
                $usuarioController = new \general\controllers\UsuarioController();
                $postController = new \general\controllers\PostController();
                $usuario = $usuarioController->retornarPorId($userid);
                $post = new \general\models\Post();
                $post->setTitulo($titulo);
                $post->setTexto($texto);
                $post->setUsuario($usuario);
                
                if ($foto == null) {
                    $post->setCaminhoImagem("");
                    $postController->novo($post);
                    echo json_encode("Post foi postado com sucesso", JSON_UNESCAPED_UNICODE);
                } else {
                    $imagemControl = new \general\helpers\ImageControl();
                    if ($imagemControl->verificaSeEhImagem($foto["type"])) {
                        if ($imagemControl->verificaSeEhMenor($foto["size"])) {
                            $caminhoImagem = $imagemControl->salvaImagem($foto);

                            $post->setCaminhoImagem($caminhoImagem);
                            $postController->novo($post);
                            echo json_encode("Post foi postado com sucesso", JSON_UNESCAPED_UNICODE);
                        } else {
                            echo json_encode("O tamanho é máximo é de 1mb", JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                            echo json_encode("Arquivo incorreto.", JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }
    }
});

$app->post("/novalocalizacao", function ($request) {
    
        $userid = $_POST["id_usuario"];

        $localizacaoController = new \general\controllers\LocalizacaoController();

        $localizacaoController->salvar($_POST);
        echo json_encode("Localização salva com sucesso", JSON_UNESCAPED_UNICODE);
        
});

$app->post("/editarpost", function ($request) {

    $postId = $_POST["usuid"];
    $titulo = $_POST["titulo-editar"];
    $texto = $_POST["texto-editar"];
    $caminhoImg = $_POST["caminho-imagem"];
    $foto = isset($_FILES["imagem-editar"]) ? $_FILES["imagem-editar"] : null;
    

    if (empty($postId)) {
        echo json_encode("Erro ao editar.", JSON_UNESCAPED_UNICODE);
    } else {
        if (empty($titulo)) {
            echo json_encode("Digite algo para o título.", JSON_UNESCAPED_UNICODE);
        } else {
            if (empty($texto)) {
                echo json_encode("Digite algo para o texto.", JSON_UNESCAPED_UNICODE);
            } else {
                $postController = new \general\controllers\PostController();
                $post = new \general\models\Post();
                $post->setId($postId);
                $post->setTitulo($titulo);
                $post->setTexto($texto);
                
                if ($foto == null) {
                    $post->setCaminhoImagem($caminhoImg);
                    $postController->editar($post);
                    echo json_encode("Post foi editado com sucesso", JSON_UNESCAPED_UNICODE);
                } else {
                    $imagemControl = new \general\helpers\ImageControl();
                    if ($imagemControl->verificaSeEhImagem($foto["type"])) {
                        if ($imagemControl->verificaSeEhMenor($foto["size"])) {
                            $caminhoImagem = $imagemControl->salvaImagem($foto);

                            $post->setCaminhoImagem($caminhoImagem);
                            $postController->editar($post);
                            echo json_encode("Post foi editado com sucesso", JSON_UNESCAPED_UNICODE);
                        } else {
                            echo json_encode("O tamanho é máximo é de 1mb", JSON_UNESCAPED_UNICODE);
                        }
                    } else {
                            echo json_encode("Arquivo incorreto.", JSON_UNESCAPED_UNICODE);
                    }
                }
            }
        }
    }
});

$app->post("/excluirpost", function ($request) {

    $idPost = isset($_POST["idpostexlcuir"]) ? $_POST["idpostexlcuir"] : null;

    if ($idPost != null) {
        $postController = new \general\controllers\PostController();

        $postController->excluir($idPost);

        echo json_encode("Excluido com sucesso", JSON_UNESCAPED_UNICODE);
    } else {
         echo json_encode("Erro ao excluir", JSON_UNESCAPED_UNICODE);
    }

});

$app->post("/solicitaramizade",function ($request){

    $idUserPrinc = isset($_POST["iduserprinc"]) ? $_POST["iduserprinc"] : null ;
    $idUserSecun = isset($_POST["idusersecun"]) ? $_POST["idusersecun"] : null ;

    if ($idUserPrinc != null){

        if ($idUserSecun != null){

            $relController = new \general\controllers\RelacionamentoController();
            $relController->solicitaAmizade($idUserPrinc,$idUserSecun);
            echo json_encode(["message" => "Solicitação funcionou"], JSON_UNESCAPED_UNICODE);


        }else{
            echo json_encode(["message" => "Erro ao solicitar,tente novamente mais tarde"], JSON_UNESCAPED_UNICODE);
        }

    }else {
         echo json_encode(["message" => "Erro ao solicitar,tente novamente mais tarde"], JSON_UNESCAPED_UNICODE);
    }

});

$app->post("/aceitarsolicitacao", function ($request){

    $idSolic = isset($_POST["data_aceitar"]) ? $_POST["data_aceitar"] : null;

    if ($idSolic != null){

        $relacionamento = new \general\controllers\RelacionamentoController();
        $relacionamento->aceitarSolicitacao($idSolic);
        echo "Funcionou";

    }

});

$app->post("/recusarsolicitacao", function ($request){

    $idSolic = isset($_POST["data_recusar"]) ? $_POST["data_recusar"] : null;

    if ($idSolic != null){

        $relacionamento = new \general\controllers\RelacionamentoController();
        $relacionamento->recusarSolicitacao($idSolic);
        
    }

});

$app->post("/desfazeramizade",function ($request){
    
    $idUserPrinc = isset($_POST["iduserprinc"]) ? $_POST["iduserprinc"] : null ;
    $idUserSecun = isset($_POST["idusersecun"]) ? $_POST["idusersecun"] : null ;

    if ($idUserPrinc != null){

        if ($idUserSecun != null){

            $relController = new \general\controllers\RelacionamentoController();
            $relController->desfazeramizade($idUserPrinc,$idUserSecun);
            echo json_encode(["message" => "Vocês não são mais amigos"], JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(["message" => "Erro ao desfazer,tente novamente mais tarde"], JSON_UNESCAPED_UNICODE);
        }

    }else {
         echo json_encode(["message" => "Erro ao desfazer,tente novamente mais tarde"], JSON_UNESCAPED_UNICODE);
    }

});

$app->post("/curtir",function ($request){
    $idPost = isset($_POST["id_post"]) ? $_POST["id_post"] : null;

    if ($idPost != null){
        $userControl = new \general\helpers\UserControl();
        $curtirController = new \general\controllers\CurtidaController();
        $curtirController->fazCurtida($idPost,$userControl->retornaUsuarioLogado()->getId());
        echo "Funcionou";
    }else {
        echo "ERRO";
    }

});

$app->post("/descurtir",function ($request){

    $idPost = isset($_POST["id_post"]) ? $_POST["id_post"] : null;

    if ($idPost != null){
        $userControl = new \general\helpers\UserControl();
        $curtirController = new \general\controllers\CurtidaController();
        $curtirController->desfazerCurtida($idPost,$userControl->retornaUsuarioLogado()->getId());
        echo "Funcionou";
    }else {
        echo "ERRO";
    }

});