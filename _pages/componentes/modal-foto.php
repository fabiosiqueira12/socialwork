<!-- Modal -->
<div class="modal fade modal-novo-post" id="modal-foto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="form-foto" action="<?= $url ?>/fotoperfil" enctype="multipart/form-data" method="post">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Alterar Imagem </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <input type="hidden" name="userid" id="userid" value="<?= $usuario->getId() ?>">
                    <div class="form-group">
                        <label>Selecione Uma Imagem</label>
                        <input type="file" accept=".jpg,.png" required class="form-control-file" name="imagem" id="imagem" aria-describedby="fileHelp">
                        <small>Apenas extensões .png e .jpg, selecione arquivo de no máximo 1mb</small>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn"><i class="fa fa-eraser"></i>Limpar</button>
                <button type="submit" class="btn"><i class="fa fa-save"></i>Salvar</button>
            </div>
        </div>

    </form>
  </div>
</div>