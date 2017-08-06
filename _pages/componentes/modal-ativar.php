<!-- Modal -->
<div class="modal fade modal-novo-post" id="modal-ativar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-ativar-usuario" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Ativar Conta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input type="password" class="form-control" name="pass-senha" id="pass-senha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" type="reset"><i class="fa fa-eraser"></i>Limpar</button>
                    <button type="submit" class="btn"><i class="fa fa-check"></i>Ativar</button>
                </div>
            </div>
        </form>
    </div>
</div>