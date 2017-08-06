<?php header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>

        <title><?= $title ?></title>
        <meta name="title" content="<?= $title ?>" />
        <meta name="description" content="<?= $descricao ?>" />
        <meta property="og:title" content="<?= $title ?>" />
        <meta property="og:description" content="<?= $descricao ?>" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts !-->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Graduate" >
        <!-- Fim Fonts !-->

        <!-- CSS !-->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="webfiles/css/bootstrap.min.css">
            <link rel="stylesheet" href="webfiles/css/hover-min.css">
            <link rel="stylesheet" href="webfiles/css/sweetalert.min.css">
            <link rel="stylesheet" href="webfiles/css/main.css">
        <!-- FIM CSS !-->
    </head>
    <body>

        <div class="backgroundImage" style="background: url('webfiles/images/back-entrar-min.jpg') no-repeat center center"></div>

        <section id="login">
            <div class="container">
            <div class="row" style="display : flex;justify-content : center">
                <div class="col-12 col-md-6 col-sm-12">

                    <h3 class="login__logo">Social Work</h3>

                    <p class="login_testo">Cadastra-se Gratuitamente.</p>

                    <div class="login__box">
                        <p class="text-center">Caso possua login <a href="login">Clique Aqui</a></p>
                        <form method="post" id="form-cadastro" class="form-login login__form">
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome" required>
                            </div>
                            <div class="form-group">
                                <label>Usuário</label>
                                <input id="usuario" type="text" name="user" class="form-control" placeholder="Usuário" required>
                                <small>No mínimo 5 e no máximo 20 caracteres.</small>
                            </div>
                            <div class="form-group">
                                <label>Sexo</label>
                                <select id="sexo" name="sexo" class="form-control">
                                    <option value="0">Masculino</option>
                                    <option value="1">Feminino</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input id="email" type="email" name="email" class="form-control" placeholder="example@email.com" required>
                            </div>
                            <div class="form-group">
                                <label>Descrição (Opcional)</label>
                                <textarea rows="5" class="form-control" id="descricao" name="descricao"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Senha</label>
                                <input id="senha" type="password" name="senha" class="form-control" placeholder="Senha" required>
                            </div>
                            <div class="form-group">
                                <label>Repita a Senha</label>
                                <input id="repita-senha" type="password" name="repita-senha" class="form-control" placeholder="Senha" required>
                            </div>
                            <button type="submit" class="btn"><i class="fa fa-plus"></i> Confirmar</button>
                             
                        </form>
                        
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!--- JS !-->
        <script src="webfiles/js/jquery.min.js"></script>
        <script src="webfiles/js/jquery.form.min.js"></script>
        <script src="webfiles/js/tether.min.js"></script>
        <script src="webfiles/js/bootstrap.min.js"></script>
        <script src="webfiles/js/sweetalert.min.js"></script>
        <script src="webfiles/js/main.js"></script>
        <!-- Fim JS !-->

    </body>
</html>
