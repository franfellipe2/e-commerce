<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>AdminLTE 2 | Log in</title>    
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/dist/css/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/dist/css/ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/dist/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/plugins/iCheck/square/blue.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>"><b>Admin</b>LTE</a>                
            </div>
            <?php if( $error != '' ){ ?>

            <div class="alert alert-warning alert-dismissible"><?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?></div>
            <?php } ?>

            <!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg">Faça login para iniciar sua seção</p>

                <form action="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/login/" method="post">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Usuario" name="user">
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Password" name="pass">
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox"> Permancer Conectado
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">Logar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center">
                    <p>- OU -</p>
                    <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Loge-se usando 
                        Facebook</a>
                    <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Loge-se usando 
                        Google+</a>
                </div>
                <!-- /.social-auth-links -->

                <a href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/forgot">Esqueci Minha Senha</a><br>
                <a href="register.html" class="text-center">Cadastrar novo usuário</a>

            </div>
            <!-- /.login-box-body -->
        </div>
        <!-- /.login-box -->

        <!-- jQuery 2.2.3 -->
        <script src="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <!-- Bootstrap 3.3.6 -->
        <script src="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/plugins/iCheck/icheck.min.js"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>
