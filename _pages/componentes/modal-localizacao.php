<!-- Modal -->
<div class="modal fade modal-novo-post" id="adicionar-locazicao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-adicionar-locazicao" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Localização</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" style="margin-bottom: 20px">
                        <button type="button" class="btn" id="botao-local">
                            <i class="fa fa-thumb-tack"></i>
                            Minha Localização
                        </button>
                    </div>
                    <input type="hidden" id="latitude" name="latitude" value="">
                    <input type="hidden" id="longitude" name="longitude" value="">
                    <input type="hidden" id="id-user" name="id-user" value="<?= $usuario->getId() ?>">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label>CEP</label>
                            <input id="cep" name="cep" type="text" class="form-control mask-cep" />
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                            <label>UF</label>
                            <input type="text" id="uf" name="uf" class="form-control" />
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12 form-group">
                            <label>Número</label>
                            <input type="text" id="numero" name="numero" class="form-control" />
                        </div>                         
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Rua</label>
                            <input id="rua" name="rua" type="text" class="form-control" />
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 form-group">
                            <label>Complemento</label>
                            <input id="complemento" name="complemento" type="text" class="form-control" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label>Bairro</label>
                            <input type="text" id="bairro" name="bairro" class="form-control" />
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12 form-group">
                            <label>Cidade</label>
                            <input type="text" id="cidade" name="cidade" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn"><i class="fa fa-trash"></i>Limpar  </button>
                    <button type="submit" class="btn"><i class="fa fa-save"></i>Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>