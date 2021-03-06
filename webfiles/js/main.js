$(document).ready(function () {
    var split = window.location.pathname.split('/');
    var URL_BASE = window.location.protocol + "//" + window.location.hostname + "/" + split[1] + "/";

    $("#form-login").on("submit", function (event) {

        event.preventDefault();

        var data = {
            login: $("#user").val(),
            senha: $("#senha").val()
        };

        $.ajax({
            type: "POST",
            url: "logar",
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.type == 0) {
                    swal("Erro !!", response.message, "error");
                } else {
                    $("#form-login").trigger("reset");
                    swal({
                            title: "Sucesso !!",
                            text: response.message,
                            type: "success",
                            closeOnConfirm: true
                        },
                        function () {
                            window.location.href = response.redirect;
                        });
                }
            },
            error: function (response) {
                swal("Erro !!", response.message, "error");
                console.log(response.message);
            }
        }).always(function () {

        });
    });

    $("#form-cadastro").on("submit", function (event) {

        event.preventDefault();

        var data = {
            nome: $("#nome").val(),
            user: $("#usuario").val(),
            email: $("#email").val(),
            sexo: $("#sexo").val(),
            descricao: $("#descricao").val(),
            senha: $("#senha").val(),
            repitasenha: $("#repita-senha").val()
        };

        $.ajax({
            type: "POST",
            url: "cadastrar",
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.type == 0) {
                    swal("Erro !!", response.message, "error");
                } else {
                    $("#form-cadastro").trigger("reset");
                    swal({
                            title: "Sucesso !!",
                            text: response.message,
                            type: "success",
                            closeOnConfirm: true
                        },
                        function () {
                            window.location.href = response.redirect;
                        });
                }
            },
            error: function (response) {
                swal("Erro !!", response.message, "error");
                console.log(response.mensage);
            }
        }).always(function () {

        });
    });

    $("#btn-sair").click(function () {
        $.ajax({
            type: "POST",
            url: URL_BASE + "deslogar",
            data: "",
            dataType: 'json',
            success: function (response) {
                window.location.href = response.message;
            },
            error: function (response) {}
        }).always(function () {

        });
    });

    $("#btn-desativar").click(function () {

        console.log($("#btn-desativar").data("id"));

        swal({
                title: "Você tem certeza ?",
                text: "Será necessário ativar a conta novamente !!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#45BF55",
                confirmButtonText: "Sim, desativar !",
                cancelButtonText: "Cancelar",
                closeOnConfirm: true
            },
            function () {
                var data = {
                    iduser: $("#btn-desativar").data("id")
                }
                $.ajax({
                    type: "POST",
                    url: URL_BASE + "desativar",
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        window.location.href = response.message;
                    },
                    error: function (response) {}
                }).always(function () {

                });
            });

    });

    $("#form-ativar-usuario").on("submit", function (event) {

        event.preventDefault();

        var data = {
            email: $("#email").val(),
            senha: $("#pass-senha").val()
        };

        $.ajax({
            type: "POST",
            url: "ativar",
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.type == 0) {
                    swal("Erro !!", response.message, "error");
                } else {
                    $("#form-ativar-usuario").trigger("reset");
                    $("#modal-ativar").modal("hide");
                    swal({
                            title: "Sucesso !!",
                            text: response.message,
                            type: "success",
                            closeOnConfirm: true
                        },
                        function () {

                        });
                }
            },
            error: function (response) {
                swal("Erro !!", response.message, "error");
                console.log(response.message);
            }
        }).always(function () {

        });
    });

    $("#form-editar-perfil").on("submit", function (event) {

        event.preventDefault();

        var data = {
            iduser: $("#id-user").val(),
            nome: $("#nome").val(),
            email: $("#email").val(),
            sexo: $("#sexo").val(),
            descricao: $("#descricao").val(),
            senha: $("#senha").val(),
            tipousuario: $("#tipo-usuario").val(),
            repitasenha: $("#repita-senha").val()
        };

        $.ajax({
            type: "POST",
            url: URL_BASE + "editarusuario",
            data: data,
            dataType: 'json',
            success: function (response) {
                if (response.type == 0) {
                    swal("Erro !!", response.message, "error");
                } else {
                    $("#editar-perfil").modal('hide');
                    swal({
                            title: "Sucesso !!",
                            text: response.message,
                            type: "success",
                            closeOnConfirm: true
                        },
                        function () {
                            window.location.href = response.redirect;
                        });
                }
            },
            error: function (response) {
                swal("Erro !!", response.message, "error");
                console.log(response.mensage);
            }
        }).always(function () {

        });
    });

    $("#form-foto").ajaxForm({

        beforeSubmit: function (options) {

        },

        success: function (response) {
            console.log(response);
            $("#form-foto").trigger("reset");
            $(".modal").modal("hide");
            swal({
                    title: "Sucesso !!",
                    text: response,
                    type: "success",
                    closeOnConfirm: true
                },
                function () {
                    window.location.reload();
                });

        },

        error: function (response) {
            console.log("entrou aqui");
            swal("Erro !", response.message, "error");

        }

    });

    $("#form-novo-post").ajaxForm({

        beforeSubmit: function (options) {

        },

        success: function (response) {
            console.log(response);
            $("#form-novo-post").trigger("reset");
            $(".modal").modal("hide");
            swal({
                    title: "Sucesso !!",
                    text: response,
                    type: "success",
                    closeOnConfirm: true
                },
                function () {
                    window.location.reload();
                });

        },

        error: function (response) {
            console.log("entrou aqui");
            swal("Erro !", response, "error");
        }

    });

    $(".form-editar-post").ajaxForm({

        beforeSubmit: function (options) {

        },

        success: function (response) {
            console.log(response);
            $(".form-editar-post").trigger("reset");
            $(".modal").modal("hide");
            swal({
                    title: "Sucesso !!",
                    text: response,
                    type: "success",
                    closeOnConfirm: true
                },
                function () {
                    window.location.reload();
                });

        },

        error: function (response) {
            console.log("entrou aqui");
            swal("Erro !", response, "error");
        }

    });

    $(".btn-excluir").click(function () {

        console.log($(this).find("span").data("id"));
        var id = $(this).find("span").data("id");

        swal({
                title: "Você tem certeza ?",
                text: "Você não poderá recupar o post !!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#45BF55",
                confirmButtonText: "Sim, excluir !",
                cancelButtonText: "Cancelar",
                closeOnConfirm: true
            },
            function () {
                var data = {
                    idpostexlcuir: id
                }
                $.ajax({
                    type: "POST",
                    url: URL_BASE + "excluirpost",
                    data: data,
                    dataType: 'json',
                    success: function (response) {
                        swal({
                                title: "Sucesso !!",
                                text: response,
                                type: "success",
                                closeOnConfirm: true
                            },
                            function () {
                                window.location.reload();
                            });
                    },
                    error: function (response) {}
                }).always(function () {

                });
            });

    });

    // browser window scroll (in pixels) after which the "back to top" link is shown
    var offset = 300,
        //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
        offset_opacity = 1200,
        //duration of the top scrolling animation (in ms)
        scroll_top_duration = 700,
        //grab the "back to top" link
        $back_to_top = $('.cd-top');
    //hide or show the "back to top" link
    $(window).scroll(function () {
        ($(this).scrollTop() > offset) ? $back_to_top.addClass('cd-is-visible'): $back_to_top.removeClass('cd-is-visible cd-fade-out');
        if ($(this).scrollTop() > offset_opacity) {

        }
    });
    //smooth scroll to top
    $back_to_top.on('click', function (event) {
        event.preventDefault();
        $('body,html').animate({
            scrollTop: 0,
        }, scroll_top_duration);
    });

    $(".btn-solicita").click(function () {
        var iduserprinc = $(this).data("logado");
        var idusersecun = $(this).data("user");
        var data = {
            iduserprinc: iduserprinc,
            idusersecun: idusersecun
        }
        $.ajax({
            type: "POST",
            url: URL_BASE + "solicitaramizade",
            data: data,
            dataType: 'json',
            success: function (response) {
                window.location.reload();
            },
            error: function (response) {}
        }).always(function () {

        });
    });

    $(".btn-desfazer").click(function () {
        var iduserprinc = $(this).data("logado");
        var idusersecun = $(this).data("user");

        swal({
            title: "Você tem certeza ?",
            text: "Você terá que solicitar amizade novamente !!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#45BF55",
            confirmButtonText: "Sim, desfazer !",
            cancelButtonText: "Cancelar",
            closeOnConfirm: true
        }, function () {
            var data = {
                iduserprinc: iduserprinc,
                idusersecun: idusersecun
            }
            $.ajax({
                type: "POST",
                url: URL_BASE + "desfazeramizade",
                data: data,
                dataType: 'json',
                success: function (response) {
                    swal({
                            title: "Sucesso !!",
                            text: response.message,
                            type: "success",
                            closeOnConfirm: true
                        },
                        function () {
                            window.location.reload();
                        });
                },
                error: function () {
                    console.log(response.message);
                }
            }).always(function () {

            });
        });


    });

    $(".btn-aceitar").click(function () {
        var data_aceitar = $(this).find("span").data("aceitar");
        var data = {
            data_aceitar: data_aceitar,
        }
        $.ajax({
            type: "POST",
            url: URL_BASE + "aceitarsolicitacao",
            data: data,
            dataType: 'json',
            success: function (response) {

            },
            error: function (response) {}
        }).always(function () {
            window.location.reload();
        });

    });

    $(".btn-recusar").click(function () {
        var data_recusar = $(this).find("span").data("recusar");
        var data = {
            data_recusar: data_recusar,
        }
        $.ajax({
            type: "POST",
            url: URL_BASE + "recusarsolicitacao",
            data: data,
            dataType: 'json',
            success: function (response) {

            },
            error: function (response) {}
        }).always(function () {
            window.location.reload();
        });
    });

    $(".btn-curtir").click(function () {
        var id_post = $(this).find("span").data("post");
        var data = {
            id_post: id_post,
        }
        $.ajax({
            type: "POST",
            url: URL_BASE + "curtir",
            data: data,
            dataType: 'json',
            success: function (response) {

            },
            error: function (response) {}
        }).always(function () {
            window.location.reload();
        });

    });

    $(".btn-descurtir").click(function () {
        var id_post = $(this).find("span").data("post");
        var data = {
            id_post: id_post,
        }
        $.ajax({
            type: "POST",
            url: URL_BASE + "descurtir",
            data: data,
            dataType: 'json',
            success: function (response) {

            },
            error: function (response) {}
        }).always(function () {
            window.location.reload();
        });

    });

    $(".mask-cep").mask("99.999-999");

    $("#cep").blur(function () {
        var cep = $(this).val().replace(/\D/g, '');
        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if (validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#rua").val(" Carregando ");
                $("#bairro").val(" Carregando ");
                $("#cidade").val(" Carregando ");
                $("#uf").val(" Carregando ");

                //Consulta o webservice viacep.com.br/
                $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#rua").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#cidade").val(dados.localidade);
                        $("#uf").val(dados.uf);

                        $.getJSON("http://maps.googleapis.com/maps/api/geocode/json?address=" + $("#cep").val() + "&sensor=false", function (retorno) {
                            $("#latitude").val(retorno.results[0].geometry.location.lat);
                            $("#longitude").val(retorno.results[0].geometry.location.lng);
                        });

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulario_cep();
                        swal(
                            'Oops...',
                            'O CEP não foi encontrando !',
                            'error'
                        );
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulario_cep();
                swal(
                    'Oops...',
                    'Formato de CEP válido !',
                    'error'
                );
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulario_cep();
        }

    });

    $("#botao-local").click(function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
            swal(
                'Oops...',
                'O seu navegador não suporta a geolocalização !',
                'error'
            );
        }
    });

    $("#form-adicionar-locazicao").on("submit", function (event) {
        event.preventDefault();

        var dados = {
            cep: $("#cep").val(),
            uf: $("#uf").val(),
            numero: $("#numero").val(),
            logradouro: $("#rua").val(),
            complemento: $("#complemento").val(),
            bairro: $("#bairro").val(),
            cidade: $("#cidade").val(),
            latitude: $("#latitude").val(),
            longitude: $("#longitude").val(),
            id_usuario: $("#id-user").val()
        }

        $.ajax({
            type: "POST",
            url: URL_BASE + "novalocalizacao",
            data: dados,
            dataType: 'json',
            success: function (response) {
                swal({
                        title: "Sucesso !!",
                        text: response.message,
                        type: "success",
                        closeOnConfirm: true
                    },
                    function () {
                        window.location.reload();
                    });
            },
            error: function () {
                console.log(response.message);
            }
        }).always(function () {

        });

    });

    $('.venobox').venobox({
        spinner: "cube-grid"
    });

});

function limpa_formulario_cep() {
    $("#rua").val("");
    $("#bairro").val("");
    $("#cidade").val("");
    $("#uf").val("");
}

function showPosition(position) {
    lat = position.coords.latitude;
    lon = position.coords.longitude;
    $("#latitude").val(lat);
    $("#longitude").val(lon);
    $.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + lon + "&sensor=true", function (dados) {
        $("#uf").val(dados.results[0].address_components[5].short_name);
        $("#numero").val(dados.results[0].address_components[0].short_name);
        $("#rua").val(dados.results[0].address_components[1].short_name);
        $("#bairro").val(dados.results[0].address_components[2].short_name);
        $("#cidade").val(dados.results[0].address_components[3].short_name);
    });
}

function showError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            swal(
                'Oops...',
                "Usuário rejeitou a solicitação de Geolocalização.",
                'error'
            );
            break;
        case error.POSITION_UNAVAILABLE:
            swal(
                'Oops...',
                "Localização indisponível.",
                'error'
            );
            break;
        case error.TIMEOUT:
            swal(
                'Oops...',
                "O tempo da requisição expirou.",
                'error'
            );
            break;
        case error.UNKNOWN_ERROR:
            swal(
                'Oops...',
                "Algum erro desconhecido aconteceu.",
                'error'
            );
            break;
    }
}