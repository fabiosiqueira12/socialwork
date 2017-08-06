<!-- Modal -->
<div class="modal fade modal-novo-post" id="novo-post" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-novo-post" action="<?= $url ?>/novopost" enctype="multipart/form-data" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Novo Post</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    
                    <input type="hidden" value="<?= $usuario->getId() ?>" name="userid" id="userid">
                    <div class="form-group">
                        <label>Título do Post</label>
                        <input class="form-control" name="titulo" id="titulo" required>
                    </div>
                    <div class="form-group">
                        <label>Texto do Post</label>
                        <textarea rows="4" class="form-control" id="texto" name="texto" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Imagem (Opcional)</label>
                        <input type="file" accept=".jpg,.png" class="form-control-file" name="imagem-post" id="imagem-post" aria-describedby="fileHelp">
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