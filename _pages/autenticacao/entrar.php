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
                <div class="col-12 col-md-4 col-sm-12">

                    <h3 class="login__logo">Social Work</h3>

                    <div class="login__box">
                        <p class="text-center">
                            <a href="#" data-toggle="modal" data-target="#modal-ativar">
                                <i class="fa fa-check"></i>
                                Ativar Conta
                            </a>
                        </p>
                        <form method="post" id="form-login" class="form-login login__form">
                            <div class="form-group">
                                <input id="user" type="text" name="user" class="form-control" placeholder="Usuário ou E-mail"
                                        required>
                            </div>
                            <div class="form-group">
                                <input id="senha" type="password" name="senha" class="form-control" placeholder="Senha" required>
                            </div>
                            <button type="submit" class="btn"><i class="fa fa-sign-in"></i> Entrar</button>
                                <a href="cadastro" class="btn">
                                <i class="fa fa-user-plus"></i>Cadastro
                                </a>
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

        <?php include_once '_pages\componentes\modal-ativar.php'; ?>

    </body>
</html>
