<?php include_once '_pages\componentes\header.php'; ?>

<section class="posts">
    <div class="container">
        <div class="row" style="display : flex;justify-content : center;">
            <div class="col-md-10 col-sm-12 col-12">
                <div class="container-branco">
                    <ul class="lista-opcoes">
                        <li>
                            <a href="#" class="btn" data-toggle="modal" data-target="#novo-post">
                                <i class="fa fa-plus"></i>
                                Novo Post
                            </a>
                            <?php include_once '_pages\componentes\modal-novo-post.php'; ?>
                        </li>
                        <li>
                            <a href="<?= $url . "/home"?>" class="btn">
                                <i class="fa fa-comment"></i>
                                Meus Posts
                            </a>
                        </li>
                        <li>
                            <a href="<?= $url . "/home" . "/friends" ?>" class="btn">
                                <i class="fa fa-comments"></i>
                                Posts de Amigos
                            </a>
                        </li>
                    </ul>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <strong>Atenção !!</strong> Será mostrado os últimos 20 Posts.
                    </div>
                    <div class="lista-posts">
                        <?php if (count($posts) > 0) { ?>
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
                        <?php } else { ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Atenção !!</strong> você ainda não tem nenhum post.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once '_pages\componentes\footer.php'; ?>
