<?php header('Content-Type: text/html; charset=utf-8');
    $curtirController = new \general\controllers\CurtidaController();
    $relController = new \general\controllers\RelacionamentoController();
    $quantidade = $relController->retornaQuantidadeDeSolicitacoes($usuario->getId());;
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>

        <!-- Meta Tags !-->
            <title><?= $title ?></title>
            <meta name="title" content="<?= $title ?>" />
            <meta name="description" content="<?= $descricao ?>" />
            <meta property="og:title" content="<?= $title ?>" />
            <meta property="og:description" content="<?= $descricao ?>" />
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Fim Meta Tags !-->

        <!-- Fonts !-->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Graduate" >
        <!-- Fim Fonts !-->

        <!-- CSS !-->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="<?= $url ?>/webfiles/css/bootstrap.min.css">
            <link rel="stylesheet" href="<?= $url ?>/webfiles/css/hover-min.css">
            <link rel="stylesheet" href="<?= $url ?>/webfiles/css/sweetalert.min.css">
            <link rel="stylesheet" href="<?= $url ?>/webfiles/css/main.css">
        <!-- FIM CSS !-->

    </head>
    <body>
        <main>
            <header>
                <div class="header-principal">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <form method="post" action="<?= $url ."/busca" ?>" id="form-busca">
                                    <div class="text-form">
                                        O que você procura ?
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="check-busca" id="check1" value="1" checked="">
                                                Pessoas
                                            </label>
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="check-busca" id="check2" value="2" >
                                                Posts
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group campo">
                                        <input class="form-control" data-search="true" type="text" placeholder="Buscar" name="query" id="query">
                                        <div class="submit">
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <ul class="lista-menu">
                                    <li>
                                        <a href="<?= $url . "/home" ?>">
                                            <i class="fa fa-comment"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $url . "/home" . "/friends" ?>">
                                            <i class="fa fa-comments"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $url . "/amigos" ?>">
                                            <i class="fa fa-user-plus"></i>
                                        </a>
                                        <span><?= $quantidade ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-2">
                                <div class="dropdown">
                                    <a class="dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript::void(0)" >
                                        <?php if ($usuario->getCaminhoImagem() != "") { ?>
                                            <img src="<?= $url . "/" . $usuario->getCaminhoImagem() ?>"/>
                                        <?php }else { ?>
                                            <img src="<?= $url ?>/webfiles/images/perfil.png"/>
                                        <?php } ?>
                                        <span><?= $usuario->getUser() ?></span>
                                    </a>

                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <a class="dropdown-item" href="<?= $url . "/perfil" . "/" . $usuario->getUser() ?>"><i class="fa fa-user"></i>Meu Perfil</a>
                                        <a class="dropdown-item" href="<?= $url . "/amigos" ?>"><i class="fa fa-users"></i>Amigos</a>
                                        <a class="dropdown-item" href="javascript:void(0)" data-id="<?= $usuario->getId() ?>" id="btn-desativar"><i class="fa fa-times"></i>Desativar</a>
                                        <a id="btn-sair" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-sign-out"></i>Sair</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="header-mobile">
                    <div class="pull-left">
                        <ul class="lista-menu">
                                    <li>
                                        <a href="#" data-toggle="modal" data-target="#modal-busca-mobile">
                                            <i class="fa fa-search"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $url . "/home" ?>">
                                            <i class="fa fa-comment"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $url . "/home" . "/friends" ?>">
                                            <i class="fa fa-comments"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= $url . "/amigos" ?>">
                                            <i class="fa fa-user-plus"></i>
                                        </a>
                                        <span><?= $quantidade ?></span>
                                    </li>
                        </ul>
                    </div>
                    <div class="pull-right">
                        <div class="dropdown">
                                <a class="dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" href="javascript::void(0)" >
                                    <?php if ($usuario->getCaminhoImagem() != "") { ?>
                                        <img src="<?= $url . "/" . $usuario->getCaminhoImagem() ?>"/>
                                    <?php }else { ?>
                                        <img src="<?= $url ?>/webfiles/images/perfil.png"/>
                                    <?php } ?>                                </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="<?= $url . "/perfil" . "/" . $usuario->getUser() ?>"><i class="fa fa-user"></i>Meu Perfil</a>
                                <a class="dropdown-item" href="<?= $url . "/amigos" ?>"><i class="fa fa-users"></i>Amigos</a>
                                <a class="dropdown-item" href="javascript:void(0)" data-id="<?= $usuario->getId() ?>" id="btn-desativar"><i class="fa fa-times"></i>Desativar</a>
                                <a id="btn-sair" class="dropdown-item" href="javascript:void(0)"><i class="fa fa-sign-out"></i>Sair</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
