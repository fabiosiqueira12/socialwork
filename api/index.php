<?php 
header('Content-Type: application/json;charset=utf-8');

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use general\controllers\BuscaApi;
use general\controllers\CurtidaApi;
use general\controllers\PostApi;
use general\controllers\RelacionamentoApi;
use general\controllers\UsuarioApi;


require __DIR__ . '/vendor/autoload.php';

$app = new \Slim\App;

$app->get('/', function (Request $request, Response $response) {
    return "Bem Vindo a api do social work";
});

//Rotas para Usuários

$app->get('/users', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";
    $controller = new UsuarioApi( $baseUrl );
    $retorno = $controller->retornaTodos();
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";
    $controller = new UsuarioApi($baseUrl);
    $retorno = $controller->retornarPorId($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}/myfriend/{id2}', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";
    $id = $request->getAttribute('id');
    $idFriend = $request->getAttribute('id2');
    $controller = new UsuarioApi($baseUrl);
    $retorno = $controller->retornaUsuarioPorLogado($id,$idFriend);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->delete('/users/{id}', function (Request $request, Response $response) {
    if ($request->isDelete()){
        $id = $request->getAttribute('id');
        $controller = new UsuarioApi();
        $retorno = $controller->desativar($id);
        if ($retorno){
           return json_encode(["retorno" => "Usuário desativado com sucesso","status" => 1],JSON_UNESCAPED_UNICODE);
       }else {
           return json_encode(["retorno" => "Usuário já está desativado ou não foi encontrado","status" => 0],JSON_UNESCAPED_UNICODE);
       }
   }
});

$app->post('/users', function (Request $request, Response $response) {
    if ($request->isPost()){
        $dados = $request->getParsedBody();
        $controller = new UsuarioApi();
        $retorno = $controller->novo($dados);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->post('/users/ativar', function (Request $request, Response $response) {
    if ($request->isPost()){
        $dados = $request->getParsedBody();
        $login = isset($dados["login"]) ? $dados["login"] : null;
        if (empty($login) || $login == null){
            $valido["mensagem"] = "Digite seu usuário";
            $valido["status"] = 0;
        }else{
            $controller = new UsuarioApi();
            $usuario = $controller->retornaUsuarioDesativado($login);
            if ($usuario != null){
                $retorno = $controller->ativar($usuario->id);
                if ($retorno){
                    $valido["mensagem"] = "Usuário Ativado";
                    $valido["status"] = 1;
                }else {
                    $valido["mensagem"] = "Usuário já está ativado";
                    $valido["status"] = 0;
                }
            }else{
                $valido["mensagem"] = "Usuário não encontrado";
                $valido["status"] = 0;
            }
        }
        
        return json_encode(["retorno" => $valido["mensagem"],"status" => $valido["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->delete('/users/desativar/{id}', function (Request $request, Response $response) {
    if ($request->isDelete()){
        $id = $request->getAttribute('id');
        $controller = new UsuarioApi();
        $retorno = $controller->desativar($id);
        if ($retorno){
            return json_encode(["retorno" => "Usuário desativado com sucesso","status" => 1],JSON_UNESCAPED_UNICODE);
        }else {
            return json_encode(["retorno" => "Usuário já está desativado","status" => 0],JSON_UNESCAPED_UNICODE);
        }
    }
});

$app->put('/users/{id}', function (Request $request, Response $response) {
    if ($request->isPut()){
        $id = $request->getAttribute('id');
        $dados = $request->getParsedBody();
        $controller = new UsuarioApi();
        $retorno = $controller->editar($id,$dados);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->post('/users/login', function (Request $request, Response $response) {
    if ($request->isPost()){
        $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/"; 
        $dados = $request->getParsedBody();
        $content = isset($dados["content"]) ? $dados["content"] : null;
        $senha = isset($dados["senha"]) ? $dados["senha"] : null;
        if ($content != null && $content != ""){

            if ($senha != null && !empty($senha)){
                $controller = new UsuarioApi($baseUrl);
                if (preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/", $content)) {
                    $usuario = $controller->retornaUsuarioPorEmail($content);
                } else {
                    $usuario = $controller->retornaUsuarioPorLogin($content);
                }

                if ($usuario != null){
                    if (md5($senha) == $usuario->senha){
                        unset($usuario->senha);
                        $retorno["mensagem"] = $usuario;
                        $retorno["status"] = 1;
                    }else{
                        $retorno["mensagem"] = "Senha Incorreta";
                        $retorno["status"] = 0;
                    }
                }else {
                    $retorno["mensagem"] = "Login ou E-mail não encontrado";
                    $retorno["status"] = 0;
                }

            }else{
                $retorno["mensagem"] = "Digite sua senha";
                $retorno["status"] = 0;
            }

        }else{
            $retorno["mensagem"] = "Digite seu login ou E-mail";
            $retorno["status"] = 0;
        } 
        
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);

    }
});

//Rotas para Post

$app->get('/posts', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $controller = new PostApi($baseUrl);
    $retorno = $controller->retornaTodos();
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/{id}', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $id = $request->getAttribute('id');
    $controller = new PostApi($baseUrl);
    $retorno = $controller->retornaPorId($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/user/{paran}', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $idOrToken = $request->getAttribute('paran');
    $controller = new PostApi($baseUrl);
    $retorno = $controller->retornaQuantidadePorUsuario($idOrToken);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/friends/user/{paran}', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $idOrToken = $request->getAttribute('paran');
    $controller = new PostApi($baseUrl);
    $retorno = $controller->retornaPostsDeAmigos($idOrToken);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/user/{paran}/friend/{paran2}', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $idOrToken = $request->getAttribute('paran');
    $idFriend = $request->getAttribute('paran2');
    $controller = new PostApi($baseUrl);
    $retorno = $controller->retornaPostsDeAmigo($idOrToken,$idFriend);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->delete('/posts/{id}', function (Request $request, Response $response) {
    if ($request->isDelete()){
        $id = $request->getAttribute('id');
        $controller = new PostApi();
        $retorno = $controller->excluir($id);
        if ($retorno){
         return json_encode(["retorno" => "Post excluido com sucesso","status" => 1],JSON_UNESCAPED_UNICODE);
     }else {
         return json_encode(["retorno" => "Post não foi encontrado","status" => 0],JSON_UNESCAPED_UNICODE);
     }
 }
});

$app->post('/posts', function (Request $request, Response $response) {
    if ($request->isPost()){
        $dados = $request->getParsedBody();
        $controller = new PostApi();
        $retorno = $controller->novo($dados);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->put('/posts/{id}', function (Request $request, Response $response) {
    if ($request->isPut()){
        $id = $request->getAttribute('id');
        $dados = $request->getParsedBody();
        $controller = new PostApi();
        $retorno = $controller->editar($id,$dados);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

//Rotas para curtida

$app->post('/users/{id}/like/{idpost}', function (Request $request, Response $response) {
    if ($request->isPost()){
        $idUser = $request->getAttribute('id');
        $idPost = $request->getAttribute('idpost');
        $controller = new CurtidaApi();
        $retorno = $controller->fazCurtida($idPost,$idUser);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->delete('/users/{id}/like/{idpost}', function (Request $request, Response $response) {
    if ($request->isDelete()){
        $idUser = $request->getAttribute('id');
        $idPost = $request->getAttribute('idpost');
        $controller = new CurtidaApi();
        $retorno = $controller->desfazerCurtida($idPost,$idUser);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->get('/posts/{id}/likes', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";        
    $id = $request->getAttribute('id');
    $controller = new CurtidaApi($baseUrl);
    $retorno = $controller->retornaCurtidas($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

//Rotas para relacionamento

$app->post('/users/{idprinc}/add/{idsegui}', function (Request $request, Response $response) {
    if ($request->isPost()){
        $idPrinc = $request->getAttribute('idprinc');
        $idSegui = $request->getAttribute('idsegui');
        $controller = new RelacionamentoApi();
        $retorno = $controller->solicitaAmizade($idPrinc,$idSegui);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->delete('/users/{idprinc}/remove/{idsegui}', function (Request $request, Response $response) {
    if ($request->isDelete()){
        $idPrinc = $request->getAttribute('idprinc');
        $idSegui = $request->getAttribute('idsegui');
        $controller = new RelacionamentoApi();
        $retorno = $controller->desfazerAmizade($idPrinc,$idSegui);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->post('/users/respost/{id}', function (Request $request, Response $response) {
    if ($request->isPost()){
        $id = $request->getAttribute('id');
        $controller = new RelacionamentoApi();
        $retorno = $controller->aceitarSolicitacao($id);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->delete('/users/respost/{id}', function (Request $request, Response $response) {
    if ($request->isDelete()){
        $id = $request->getAttribute('id');
        $controller = new RelacionamentoApi();
        $retorno = $controller->recusarSolicitacao($id);
        return json_encode(["retorno" => $retorno["mensagem"],"status" => $retorno["status"]],JSON_UNESCAPED_UNICODE);
    }
});

$app->get('/users/{id}/friends', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $id = $request->getAttribute('id');
    $controller = new RelacionamentoApi($baseUrl);
    $retorno = $controller->retornaAmigos($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}/resposts', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $id = $request->getAttribute('id');
    $controller = new RelacionamentoApi($baseUrl);
    $retorno = $controller->retornaSolicitacoes($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

//Rotas para busca

$app->get('/search/{type}/{query}', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";        
    $type = $request->getAttribute('type');
    $query = $request->getAttribute('query');
    $controller = new BuscaApi($baseUrl);
    $status = 1;
    if ($type == 1){
        $retorno = $controller->retornaPessoas($query);
    }else if ($type == 2){
        $retorno = $controller->retornaPosts($query);
    }else{
        $retorno = "Tipo incorreto";
        $status = 0;
    }
    return json_encode(["retorno" => $retorno,"status" => $status],JSON_UNESCAPED_UNICODE);
});

//Rotas Helpers

$app->get('/users/{id}/friends/quant', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $id = $request->getAttribute('id');
    $controller = new RelacionamentoApi($baseUrl);
    $retorno = $controller->retornaQuantAmigos($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}/likes/quant', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $id = $request->getAttribute('id');
    $controller = new CurtidaApi($baseUrl);
    $retorno = $controller->retornaQuantLikes($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}/posts/quant', function (Request $request, Response $response) {
    $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";    
    $id = $request->getAttribute('id');
    $controller = new PostApi($baseUrl);
    $retorno = $controller->retornaQuantPosts($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->post('/upload/image', function (Request $request, Response $response) {
    if ($request->isPost()){
        $baseUrl = $request->getUri()->getScheme() . "://" . $request->getUri()->getHost() . "/socialwork/";
        $targetPath = "fotos/";
        $targetPath = $targetPath . basename( $_FILES['file']['name']);

        if (move_uploaded_file($_FILES['file']['tmp_name'], "../" . $targetPath)) {
            return json_encode(["retorno" => $targetPath,"status" => 1],JSON_UNESCAPED_UNICODE);
        } else {
           return json_encode(["retorno" => "","status" => 0],JSON_UNESCAPED_UNICODE);
        }

    }
});


//Executa Api
$app->run();

?>