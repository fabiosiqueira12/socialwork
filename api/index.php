<?php 
header('Content-Type: application/json;charset=utf-8');

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
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
    $controller = new UsuarioApi();
    $retorno = $controller->retornaTodos();
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $controller = new UsuarioApi();
    $retorno = $controller->retornarPorId($id);
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

$app->post('/users/ativar/{id}', function (Request $request, Response $response) {
    if ($request->isPost()){
        $id = $request->getAttribute('id');
        $controller = new UsuarioApi();
        $retorno = $controller->ativar($id);
        if ($retorno){
            return json_encode(["retorno" => "Usuário ativado com sucesso","status" => 1],JSON_UNESCAPED_UNICODE);
        }else {
            return json_encode(["retorno" => "Usuário já está ativado ou não foi encontrado","status" => 0],JSON_UNESCAPED_UNICODE);
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


//Rotas para Post

$app->get('/posts', function (Request $request, Response $response) {
    $controller = new PostApi();
    $retorno = $controller->retornaTodos();
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $controller = new PostApi();
    $retorno = $controller->retornaPorId($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/user/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $controller = new PostApi();
    $retorno = $controller->retornaQuantidadePorUsuario($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/posts/friends/user/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $controller = new PostApi();
    $retorno = $controller->retornaPostsDeAmigos($id);
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
    $id = $request->getAttribute('id');
    $controller = new CurtidaApi();
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
    $id = $request->getAttribute('id');
    $controller = new RelacionamentoApi();
    $retorno = $controller->retornaAmigos($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

$app->get('/users/{id}/resposts', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $controller = new RelacionamentoApi();
    $retorno = $controller->retornaSolicitacoes($id);
    return json_encode(["retorno" => $retorno,"status" => 1],JSON_UNESCAPED_UNICODE);
});

//Rotas para busca


//Executa Api
$app->run();

?>