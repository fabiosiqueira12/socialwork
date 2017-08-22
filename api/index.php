<?php 
header('Content-Type: application/json;charset=utf-8');

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\ServerRequestInterface as Request;
use general\controllers\PostApi;
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
    $id = $request->getAttribute('id');
    $controller = new UsuarioApi();
    $retorno = $controller->desativar($id);
    if ($retorno){
    	return json_encode(["retorno" => "Usuário desativado com sucesso","status" => 1],JSON_UNESCAPED_UNICODE);
    }else {
    	return json_encode(["retorno" => "Usuário não foi encontrado","status" => 0],JSON_UNESCAPED_UNICODE);
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
    $id = $request->getAttribute('id');
    $controller = new PostApi();
    $retorno = $controller->excluir($id);
    if ($retorno){
    	return json_encode(["retorno" => "Post excluido com sucesso","status" => 1],JSON_UNESCAPED_UNICODE);
    }else {
    	return json_encode(["retorno" => "Post não foi encontrado","status" => 0],JSON_UNESCAPED_UNICODE);
    }
});



//Executa Api
$app->run();

?>