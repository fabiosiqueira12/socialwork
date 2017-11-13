<?php include_once '_pages\componentes\header.php';
    $quantAmigos = $relController->retornaQuantidadeDeAmigos($usuarioperfil->getId());
 ?> 
<section class="sessao-perfil">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="row panel">
                    <div class="col-md-4 bg_blur ">
                        <?php if ($usuarioperfil->getId() == $usuario->getId()){ ?>
                            <a href="#" data-toggle="modal" data-target="#editar-perfil" class="follow_btn">Editar Perfil</a>
                            <a href="#" data-toggle="modal" data-target="#novo-post" class="follow_btn new-post">Novo Post</a>
                        <?php }else { ?>
                            <?php if ($relController->fezSolicitacao($usuario->getId(),$usuarioperfil->getId())) { ?>
                                <a href="javascript:void(0)" class="follow_btn">Solicitado</a>
                            <?php }else { ?>
                                <?php if ($relController->ehAmigo($usuario->getId(),$usuarioperfil->getId())) { ?>
                                    <a href="javascript:void(0)" class="follow_btn btn-desfazer" data-user="<?= $usuarioperfil->getId() ?>" data-logado="<?= $usuario->getId() ?>">Desfazer Amizade</a>
                                <?php }else { ?>
                                    <a href="javascript:void(0)" class="follow_btn btn-solicita" data-user="<?= $usuarioperfil->getId() ?>" data-logado="<?= $usuario->getId() ?>">Solicitar Amizade</a>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-8  col-xs-12">
                        <?php if ($usuarioperfil->getCaminhoImagem() != "") { ?>
                            <img src="<?= $url . "/" . $usuarioperfil->getCaminhoImagem() ?>" class="img-thumbnail picture hidden-xs" />
                        <?php }else { ?>
                            <img src="<?= $url ?>/webfiles/images/perfil.png" class="img-thumbnail picture hidden-xs" />
                        <?php } ?>
                        <?php if ($usuarioperfil->getId() == $usuario->getId()) { ?>
                        <a href="#" id="btn-muda-foto" data-toggle="modal" data-target="#modal-foto" class="btn">
                            <i class="fa fa-picture-o"></i>
                            Alterar Imagem
                        </a>
                        <?php } ?>
                        <div class="header">
                                <h2><?= $usuarioperfil->getNome() ?></h2>
                                <h5><?= $usuarioperfil->getEmail() ?></h5>
                                <h6><?= $usuarioperfil->getSexoDesc()  ?></h6>
                                <span><?= $usuarioperfil->getDescricao() ?></span>
                        </div>
                    </div>
                </div>   
                
                <div class="row nav">    
                    <div class="col-md-4"></div>
                    <div class="col-md-8 col-xs-12" style="margin: 0px;padding: 0px;">
                        <div class="col-md-4 col-xs-4 well"><i class="fa fa-comments fa-lg"></i><?= $quantidadeposts ?></div>
                        <div class="col-md-4 col-xs-4 well" data-toggle="modal" data-target="#modal-amigos"><i class="fa fa-users fa-lg"></i> <?= $quantAmigos ?></div>
                        <div class="col-md-4 col-xs-4 well"><i class="fa fa-thumbs-up fa-lg"></i> <?= $curtirController->numCurtidasPorUsuario($usuarioperfil->getId()) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="botao-local">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div style="margin-top : 30px;margin-bottom : 30px;text-align : center">
                    <a title="<?= $end ?>" style="font-weight : bold;text-transform : uppercase; color : #000;border : 1px solid #000" href="https://www.google.com/maps/embed/v1/place?key=AIzaSyANgAmr0NYico5FpX16SQuE2_RXfy0daqA&q=<?= $end ?>" class="btn venobox" data-vbtype="iframe">
                        <i class="fa fa-map-marker"></i>  Ver Localização
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="posts">
    <div class="container">
        <div class="row" style="display : flex;justify-content : center">
            <div class="col-md-10 com-sm-12 col-12">
                <div class="container-branco">

                    <?php if ($usuarioperfil->getId() == $usuario->getId()) { ?>

                            <?php if (count($posts) == 0) { ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Atenção !!</strong> Não Possui posts ainda.
                                </div>
                            <?php }else { ?>
                                <div class="lista-posts">
                                    <?php foreach ($posts as $key => $value) : ?>
                                        <div class="post">
                                            <?php if ($usuario->getId() == $value->getUsuario()->getId()) { ?>
                                                <div class="modal fade modal-novo-post" id="editar-post<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form class="form-editar-post" action="<?= $url ?>/editarpost" method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Post</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="usuid" id="usuid" value=<?= $value->getId() ?>>
                                                                    <input type="hidden" name="caminho-imagem" id="caminho-imagem" value="<?= $value->getCaminhoImagem() ?>" >
                                                                    <div class="form-group">
                                                                        <label>Título do Post</label>
                                                                        <input class="form-control" value="<?= $value->getTitulo() ?>" name="titulo-editar" id="titulo-editar" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Texto do Post</label>
                                                                        <textarea rows="4" class="form-control" name="texto-editar" id="texto-editar" required><?= $value->getTexto() ?></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Imagem (Não selecionar para manter atual)</label>
                                                                        <input type="file" accept=".jpg,.png" class="form-control-file" name="imagem-editar" id="imagem-editar" aria-describedby="fileHelp">
                                                                        <small>Apenas extensões .png e .jpg, selecione arquivo de no máximo 1mb</small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn" type="reset"><i class="fa fa-eraser"></i>Limpar</button>
                                                                    <button type="submit" class="btn"><i class="fa fa-save"></i>Confirmar</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="post-header">
                                                <div class="sobre-usuario">
                                                    <a href="<?= $url . "/perfil" . "/" . $value->getUsuario()->getUser() ?>">
                                                        <?php if ($value->getUsuario()->getCaminhoImagem() != null) { ?>
                                                            <img src="<?= $url . "/" . $value->getUsuario()->getCaminhoImagem() ?>">
                                                        <?php }else { ?>
                                                            <img src="<?= $url . "/webfiles/images/perfil.png" ?>">
                                                        <?php } ?>
                                                        <span><?= $value->getUsuario()->getNome() ?></span>
                                                    </a>
                                                </div>
                                                <div class="opcoes-post">
                                                    <?php if ($curtirController->jaCurtiu($value->getId(),$usuario->getId())) { ?>
                                                        <a href="javascript:void(0)" class="btn-descurtir">
                                                            <span data-post="<?= $value->getId() ?>"></span>
                                                            <i class="fa fa-thumbs-up"></i>Descurtir
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0)" class="btn-curtir">
                                                            <span data-post="<?= $value->getId() ?>"></span>
                                                            <i class="fa fa-thumbs-up"></i>Curtir
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($value->getUsuario()->getId() == $usuario->getId()) { ?>
                                                        <a href="#" data-toggle="modal" data-target="#editar-post<?= $key ?>">
                                                            <i class="fa fa-pencil-square"></i>Editar
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn-excluir" >
                                                            <span data-id="<?= $value->getId() ?>"></span>
                                                            <i class="fa fa-trash"></i>Excluir
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="post-info">
                                                <ul class='lista-info'>
                                                    <li>
                                                        <a href="#" data-toggle="modal" data-target="#modal-curtidas<?= $key ?>">
                                                            <i class="fa fa-thumbs-up"></i>
                                                            Número de curtidas
                                                            <span><?= $curtirController->retornaQuantidadeCurtidas($value->getId()) ?></span>
                                                        </a>
                                                        <div class="modal fade modal-curtidas" id="modal-curtidas<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLongTitle">Curtidas</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <?php $listaCurtidas = $curtirController->retornaCurtidas($value->getId()) ?>
                                                                            <?php if (count($listaCurtidas) > 0) { ?>
                                                                                <ul class="lista-curtidas" style="padding : 0px">
                                                                                    <?php for($i = 0;$i < count($listaCurtidas);$i++) :
                                                                                        $urlperfil = $url . "/perfil" . "/" . $listaCurtidas[$i]["usuario"]->getUser();
                                                                                    ?>
                                                                                        <li>
                                                                                            <a href="<?= $urlperfil ?>">
                                                                                                <?php if ($listaCurtidas[$i]["usuario"]->getCaminhoImagem() != "") { ?>
                                                                                                    <img src="<?= $url . "/" . $listaCurtidas[$i]["usuario"]->getCaminhoImagem() ?>">
                                                                                                <?php }else { ?>
                                                                                                    <img src="<?= $url ?>/webfiles/images/perfil.png">
                                                                                                <?php } ?>
                                                                                                <label><?= $listaCurtidas[$i]["usuario"]->getNome() ?></label>
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php endfor; ?>
                                                                                </ul>
                                                                            <?php }else { ?>
                                                                                <div class="alert alert-danger" role="alert">
                                                                                    <strong>Desculpe !!</strong> Seu post ainda não possui curtidas.
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                            <div class="post-body">
                                                <div class="imagem">
                                                    <?php if ($value->getCaminhoImagem() != null) { ?>
                                                       <a class="venobox" href="<?= $url . "/" . $value->getCaminhoImagem() ?>">                         
                                                            <img src="<?= $url . "/" . $value->getCaminhoImagem() ?>">
                                                       </a>
                                                    <?php }else { ?>
                                                        <img src="<?= $url . "/webfiles/images/back-post.png" ?>">
                                                    <?php } ?>
                                                </div>
                                                <div class="data-postagem">
                                                  <i class="fa fa-calendar"></i> Postado em <?= $value->dataFormatada(); ?> às <?= $value->horaPublicacao() ?>
                                                </div>
                                                <div class="titulo">
                                                    <?= $value->getTitulo() ?>
                                                </div>
                                                <div class="texto">
                                                    <?= $value->getTexto() ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php } ?>
                    
                    <?php }else { ?>
                        
                        <?php if ($usuarioperfil->getTipo() == 2 && $relController->naoEhAmigo($usuario->getId(),$usuarioperfil->getId())){ ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Atenção !!</strong> Você precisa ser amigo para visualizar os posts.
                            </div>
                        <?php }else { ?>
                            <?php if (count($posts) == 0) { ?>
                                <div class="alert alert-warning" role="alert">
                                    <strong>Atenção !!</strong> Não Possui posts ainda.
                                </div>
                            <?php }else { ?>
                                <div class="lista-posts">
                                    <?php foreach ($posts as $key => $value) : ?>
                                        <div class="post">
                                            <?php if ($usuario->getId() == $value->getUsuario()->getId()) { ?>
                                                <div class="modal fade modal-novo-post" id="editar-post<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <form class="form-editar-post" action="<?= $url ?>/editarpost" method="post">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Post</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="usuid" id="usuid" value=<?= $value->getId() ?>>
                                                                    <input type="hidden" name="caminho-imagem" id="caminho-imagem" value="<?= $value->getCaminhoImagem() ?>" >
                                                                    <div class="form-group">
                                                                        <label>Título do Post</label>
                                                                        <input class="form-control" value="<?= $value->getTitulo() ?>" name="titulo-editar" id="titulo-editar" required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Texto do Post</label>
                                                                        <textarea rows="4" class="form-control" name="texto-editar" id="texto-editar" required><?= $value->getTexto() ?></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Imagem (Não selecionar para manter atual)</label>
                                                                        <input type="file" accept=".jpg,.png" class="form-control-file" name="imagem-editar" id="imagem-editar" aria-describedby="fileHelp">
                                                                        <small>Apenas extensões .png e .jpg, selecione arquivo de no máximo 1mb</small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn" type="reset"><i class="fa fa-eraser"></i>Limpar</button>
                                                                    <button type="submit" class="btn"><i class="fa fa-save"></i>Confirmar</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <div class="post-header">
                                                <div class="sobre-usuario">
                                                    <a href="<?= $url . "/perfil" . "/" . $value->getUsuario()->getUser() ?>">
                                                        <?php if ($value->getUsuario()->getCaminhoImagem() != null) { ?>
                                                            <img src="<?= $url . "/" . $value->getUsuario()->getCaminhoImagem() ?>">
                                                        <?php }else { ?>
                                                            <img src="<?= $url . "/webfiles/images/perfil.png" ?>">
                                                        <?php } ?>
                                                        <span><?= $value->getUsuario()->getNome() ?></span>
                                                    </a>
                                                </div>
                                                <div class="opcoes-post">
                                                    <?php if ($curtirController->jaCurtiu($value->getId(),$usuario->getId())) { ?>
                                                        <a href="javascript:void(0)" class="btn-descurtir">
                                                            <span data-post="<?= $value->getId() ?>"></span>
                                                            <i class="fa fa-thumbs-up"></i>Descurtir
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="javascript:void(0)" class="btn-curtir">
                                                            <span data-post="<?= $value->getId() ?>"></span>
                                                            <i class="fa fa-thumbs-up"></i>Curtir
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($value->getUsuario()->getId() == $usuario->getId()) { ?>
                                                        <a href="#" data-toggle="modal" data-target="#editar-post<?= $key ?>">
                                                            <i class="fa fa-pencil-square"></i>Editar
                                                        </a>
                                                        <a href="javascript:void(0)" class="btn-excluir" >
                                                            <span data-id="<?= $value->getId() ?>"></span>
                                                            <i class="fa fa-trash"></i>Excluir
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="post-info">
                                                <ul class='lista-info'>
                                                    <li>
                                                        <a href="#" data-toggle="modal" data-target="#modal-curtidas<?= $key ?>">
                                                            <i class="fa fa-thumbs-up"></i>
                                                            Número de curtidas
                                                            <span><?= $curtirController->retornaQuantidadeCurtidas($value->getId()) ?></span>
                                                        </a>
                                                        <div class="modal fade modal-curtidas" id="modal-curtidas<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title" id="exampleModalLongTitle">Curtidas</h5>
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                    <span aria-hidden="true">&times;</span>
                                                                                </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <?php $listaCurtidas = $curtirController->retornaCurtidas($value->getId()) ?>
                                                                            <?php if (count($listaCurtidas) > 0) { ?>
                                                                                <ul class="lista-curtidas" style="padding : 0px">
                                                                                    <?php for($i = 0;$i < count($listaCurtidas);$i++) :
                                                                                        $urlperfil = $url . "/perfil" . "/" . $listaCurtidas[$i]["usuario"]->getUser();
                                                                                    ?>
                                                                                        <li>
                                                                                            <a href="<?= $urlperfil ?>">
                                                                                                <?php if ($listaCurtidas[$i]["usuario"]->getCaminhoImagem() != "") { ?>
                                                                                                    <img src="<?= $url . "/" . $listaCurtidas[$i]["usuario"]->getCaminhoImagem() ?>">
                                                                                                <?php }else { ?>
                                                                                                    <img src="<?= $url ?>/webfiles/images/perfil.png">
                                                                                                <?php } ?>
                                                                                                <label><?= $listaCurtidas[$i]["usuario"]->getNome() ?></label>
                                                                                            </a>
                                                                                        </li>
                                                                                    <?php endfor; ?>
                                                                                </ul>
                                                                            <?php }else { ?>
                                                                                <div class="alert alert-danger" role="alert">
                                                                                    <strong>Desculpe !!</strong> Seu post ainda não possui curtidas.
                                                                                </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                            <div class="post-body">
                                                <div class="imagem">
                                                    <?php if ($value->getCaminhoImagem() != null) { ?>
                                                       <a class="venobox" href="<?= $url . "/" . $value->getCaminhoImagem() ?>">                         
                                                          <img src="<?= $url . "/" . $value->getCaminhoImagem() ?>">
                                                       </a>
                                                    <?php }else { ?>
                                                        <img src="<?= $url . "/webfiles/images/back-post.png" ?>">
                                                    <?php } ?>
                                                </div>
                                                <div class="data-postagem">
                                                    <i class="fa fa-calendar"></i> Postado em <?= $value->dataFormatada(); ?> às <?= $value->horaPublicacao() ?>
                                                </div>
                                                <div class="titulo">
                                                    <?= $value->getTitulo() ?>
                                                </div>
                                                <div class="texto">
                                                    <?= $value->getTexto() ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php } ?>
                        <?php } ?>

                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modais !-->

<?php include_once '_pages\componentes\modal-amigos.php'; ?>

<?php if ($usuarioperfil->getId() == $usuario->getId()){ ?>

    <?php include_once '_pages\componentes\modal-foto.php'; ?>
    <?php include_once '_pages\componentes\modal-editar-perfil.php'; ?>
    <?php include_once '_pages\componentes\modal-novo-post.php'; ?>

<?php } ?>

<?php include_once '_pages\componentes\footer.php'; ?>