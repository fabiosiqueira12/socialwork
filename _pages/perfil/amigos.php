<?php include_once '_pages\componentes\header.php';

  $quantAmigos = $relController->retornaQuantidadeDeAmigos($usuario->getId());
  $solicitacoes = $relController->retornaSolicitacoes($usuario->getId());
  $listaAmigos = $relController->retornaAmigos($usuario->getId());
 ?>

<section class="lista-amigos">

    <div class="container">
        <div class="row" style="display : flex;justify-content : center">
            <div class="col-12">
                <div class="container-branco">

                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#amigos" role="tab" data-toggle="tab">Lista de Amigos <span><?= $quantAmigos ?></span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#solicitacoes" role="tab" data-toggle="tab">Lista de Solicitações <span><?= $quantidade ?></span></a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active show" id="amigos">
                            <?php if ($quantAmigos > 0) { ?>
                                <ul class="lista-pessoas">
                                    <?php for ($i=0; $i < count($listaAmigos); $i++) :
                                        $urlperfil = $url . "/perfil" . "/" . $listaAmigos[$i]["usuario"]->getUser();
                                     ?>

                                        <li>
                                            <div class="pessoa">
                                                <a href="<?= $urlperfil ?>">
                                                    <div class="info">
                                                        <?php if ($listaAmigos[$i]["usuario"]->getCaminhoImagem() != "") { ?>
                                                            <img src="<?= $url. "/" . $listaAmigos[$i]["usuario"]->getCaminhoImagem() ?>">
                                                        <?php }else { ?>
                                                            <img src="<?= $url. "/webfiles/images/perfil.png" ?>">                                                            
                                                        <?php } ?>
                                                        <h5><?= $listaAmigos[$i]["usuario"]->getNome() ?></h5>
                                                    </div>
                                                </a>
                                            </div>
                                        </li>

                                    <?php endfor; ?>
                                </ul>
                            <?php }else {  ?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>Desculpe !!</strong> Você ainda não tem amigos .
                                </div>
                            <?php } ?>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="solicitacoes">
                            <?php if ($quantidade > 0) { ?>
                                <ul class="lista-pessoas">
                                    <?php for ($i=0; $i < $quantidade; $i++) :
                                         $urlperfil = $url . "/perfil" . "/" . $solicitacoes[$i]["usuario"]->getUser();
                                     ?>
                                        <li>
                                            <div class="pessoa">
                                                <a href="<?= $urlperfil ?>">
                                                    <div class="info">
                                                        <?php if ($solicitacoes[$i]["usuario"]->getCaminhoImagem() != "") { ?>
                                                            <img src="<?= $url . "/" . $solicitacoes[$i]["usuario"]->getCaminhoImagem() ?>">
                                                        <?php }else { ?>
                                                            <img src="<?= $url ?>/webfiles/images/perfil.png">
                                                        <?php } ?>
                                                        <h5><?= $solicitacoes[$i]["usuario"]->getNome() ?></h5>
                                                    </div>
                                                </a>
                                                <div class="data">
                                                   <strong>Data de solicitação : </strong><?= $solicitacoes[$i]["data"] ?> às <?= $solicitacoes[$i]["hora"] ?>
                                                </div>
                                                <div class="botoes">
                                                    <a href="javascript:void(0)" class="btn btn-success btn-aceitar">
                                                        <span data-aceitar="<?= $solicitacoes[$i]["id"] ?>"></span>
                                                        <i class="fa fa-check" style="margin-right : 6px"></i>
                                                        Aceitar
                                                    </a>
                                                    <a href="javascript:void(0)" class="btn btn-danger btn-recusar">
                                                        <span data-recusar="<?= $solicitacoes[$i]["id"] ?>"></span>
                                                        <i class="fa fa-times" style="margin-right : 6px"></i>
                                                        Recusar
                                                    </a>
                                                </div>
                                            </div>
                                        </li>

                                    <?php endfor; ?>
                                </ul>
                            <?php }else { ?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>Desculpe !!</strong> Você não tem nenhuma solicitação no momento .
                                </div>
                            <?php } ?>
                        </div>  
                    </div>

                </div>
            </div>
            
        </div>
    </div>

</section>


<?php include_once '_pages\componentes\footer.php'; ?>