<?php include_once '_pages\componentes\header.php'; ?>

<section class="busca-page">
    <div class="container">
        <div class="row" style="display : flex;justify-content : center">
            <div class="col-md-10 col-sm-12 col-12">
                <div class="container-branco">
                   <?php if ($query == "") { ?>
                        <h3 class="titulo-busca">Você precisa digitar algo para a busca</h3> 
                        <div class="alert alert-danger" role="alert">
                                    <strong>Erro !!</strong> Atenção você precisa digitar algo para a busca.
                        </div>
                    <?php }else { ?>
                    
                        <h3 class="titulo-busca">Você buscou por <span class="query"><?= $query ?></span></h3> 
                        <?php if(count($busca) > 0) { ?>
                            <ul class="lista-pessoas">
                                <?php foreach ($busca as $key => $value) : ?>

                                    <li>
                                        <div class="pessoa">
                                            <a href="<?= $url ."/perfil" . "/" . $value->getUser() ?>">
                                                <div class="info">
                                                    <?php if ($value->getCaminhoImagem() == "") { ?>
                                                        <img src="<?= $url ?>/webfiles/images/perfil.png">
                                                    <?php }else { ?>
                                                        <img src="<?= $url . "/" . $value->getCaminhoImagem() ?>">
                                                    <?php } ?>
                                                    <h5><?= $value->getNome() ?></h5>
                                                </div>
                                            </a>
                                        </div>
                                    </li>

                                <?php endforeach; ?>
                            </ul>
                        <?php }else { ?>
                                <div class="alert alert-danger" role="alert">
                                    <strong>Atenção !!</strong> Sua busca não retornou resultados.
                                </div>
                        <?php } ?>
                   <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once '_pages\componentes\footer.php'; ?>