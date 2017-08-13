<!-- Modal -->
<div class="modal fade modal-novo-post" id="editar-perfil" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-editar-perfil" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Editar Perfil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id-user" name="id-user" value="<?= $usuario->getId() ?>">
                    <div class="form-group">
                        <label>Nome</label>
                        <input class="form-control" value="<?= $usuario->getNome() ?>" name="nome" id="nome" required>
                    </div>
                    <div class="form-group">
                        <label>Sexo</label>
                        <select id="sexo" name="sexo" class="form-control">
                            <option <?= $usuario->getSexo() == 0 ? "selected" : "" ?> value="0">Masculino</option>
                            <option <?= $usuario->getSexo() == 1 ? "selected" : "" ?> value="1">Feminino</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tipo de Usuário</label>
                        <select id="tipo-usuario" name="tipo-usuario" class="form-control">
                            <option <?= $usuario->getTipo() == 1 ? "selected" : "" ?> value="1">Público</option>
                            <option <?= $usuario->getTipo() == 2 ? "selected" : "" ?> value="2">Privado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input id="email" value="<?= $usuario->getEmail() ?>" type="email" name="email" class="form-control" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label>Descrição (Opcional)</label>
                        <textarea rows="5" class="form-control" id="descricao" name="descricao"><?= $usuario->getDescricao() ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Senha</label>
                        <input id="senha" type="password" name="senha" class="form-control" placeholder="Não alterar para manter">
                    </div>
                    <div class="form-group">
                        <label>Repita a Senha</label>
                        <input id="repita-senha" type="password" name="repita-senha" class="form-control" placeholder="Repita a senha">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn"><i class="fa fa-save"></i>Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>