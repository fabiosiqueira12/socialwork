<?php 
    $listaAmigos = $relController->retornaAmigos($usuarioperfil->getId());
 ?>
<div class="modal fade modal-curtidas" id="modal-amigos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Amigos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="lista-curtidas" style="padding : 0px">

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
                                        <h5 style="display : inline-block"><?= $listaAmigos[$i]["usuario"]->getNome() ?></h5>
                                    </div>
                                </a>
                            </div>
                        </li>

                    <?php endfor; ?>
                    
                </ul>
            </div>
        </div>
    </div>
</div>