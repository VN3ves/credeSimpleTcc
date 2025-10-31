<?php if (! defined('ABSPATH')) exit;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo SYS_NAME; ?></title>

    <base href="<?php echo HOME_URI; ?>/">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/plugins/sweetalert/css/sweetalert2.min.css">
    <script src="<?php echo HOME_URI; ?>/views/standards/plugins/sweetalert/js/sweetalert2.all.min.js"></script>

    <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/standards/adminlte/css/adminlte.min.css">
    
    <style>
        /* Estilo para o spinner de loading no botão */
        .btn-login.loading {
            position: relative;
            color: transparent !important; /* Esconde o texto */
        }
        .btn-login.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 1.2rem;  /* Tamanho ajustado para o botão */
            height: 1.2rem;
            margin: -0.6rem 0 0 -0.6rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        /* Ajuste para o link 'Esqueci minha senha' ficar alinhado */
        .login-card-body .tab-content {
            margin-bottom: 1rem;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="./"><img src="<?php echo HOME_URI; ?>/views/standards/images/logo-login.png" class="img-responsive" alt="<?php echo SYS_NAME; ?>"></a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Acesse sua conta no <?php echo SYS_NAME; ?></p>

                <?php
                // Bloco de erro e toast da sua view nova
                if (!empty($this->login_error)) {
                    // Usando a classe de alerta padrão do AdminLTE
                    echo '<div class="alert alert-danger text-center">' . $this->login_error . '</div>';
                    unset($this->login_error);
                }

                if (!empty($this->toast_message)) {
                    echo $this->toast_message;
                    unset($this->toast_message);
                }
                ?>

                <p class="text-center">ou faça login com</p>

                <ul class="nav nav-tabs nav-justified mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#email-tab" data-toggle="tab">E-mail</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#usuario-tab" data-toggle="tab">Usuário</a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane active" id="email-tab">
                        <form action="" method="post" onsubmit="handleSubmit(this)">
                            <input type="hidden" name="userdata[tipo]" value="email">
                            
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="E-mail" name="userdata[email]" required autocomplete="username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Senha" name="userdata[senha]" required autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block btn-login">Entrar com E-mail</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="usuario-tab">
                        <form action="" method="post" onsubmit="handleSubmit(this)">
                            <input type="hidden" name="userdata[tipo]" value="usuario">

                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Usuário" name="userdata[usuario]" required autocomplete="username">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-user"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" placeholder="Senha" name="userdata[senha]" required autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block btn-login">Entrar com Usuário</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> <p class="mb-1">
                    <a href="<?php echo HOME_URI; ?>/login/index/esqueci-minha-senha">Esqueci minha senha</a>
                </p>

            </div>
            </div>
        </div>
    <script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="<?php echo HOME_URI; ?>/views/standards/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo HOME_URI; ?>/views/standards/adminlte/js/adminlte.min.js"></script>

    <script>
        // Form submission com estado de loading
        function handleSubmit(form) {
            const submitBtn = form.querySelector('.btn-login');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;

            // Re-abilita o botão depois de 3 segundos em caso de erro de JS ou rede
            // O ideal é que o backend retorne a página, recarregando o form
            setTimeout(() => {
                submitBtn.classList.remove('loading');
                submitBtn.disabled = false;
            }, 3000);

            return true;
        }

    </script>
</body>

</html>