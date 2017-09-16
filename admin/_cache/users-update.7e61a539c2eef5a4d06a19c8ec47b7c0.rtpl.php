<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Lista de Usuários
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="/admin/users">Usuários</a></li>
            <li class="active"><a href="/admin/users/create">Cadastrar</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Novo Usuário</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/users/<?php echo htmlspecialchars( $data["user_id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" method="post">
                        <div class="box-body">
                            <div class="form-group <?php if( !empty($error["person_name"]) ){ ?>has-warning<?php } ?>">
                                <label for="person_name">Nome</label>
                                <input type="text" class="form-control" id="person_name" name="person_name" placeholder="Digite o nome" <?php if( !empty($data["person_name"]) ){ ?>value="<?php echo htmlspecialchars( $data["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                                <span class="help-block"><?php if( !empty($error["person_name"]) ){ ?><?php echo htmlspecialchars( $error["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                            </div>
                            <div class="form-group <?php if( !empty($error["user_login"]) ){ ?>has-warning<?php } ?>">
                                <label for="user_login">Login</label>
                                <input type="text" class="form-control" id="user_login" name="user_login" placeholder="Digite o login" <?php if( !empty($data["user_login"]) ){ ?>value="<?php echo htmlspecialchars( $data["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                                <span class="help-block"><?php if( !empty($error["user_login"]) ){ ?><?php echo htmlspecialchars( $error["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                            </div>
                            <div class="form-group">
                                <label for="person_phone">Telefone</label>
                                <input type="tel" class="form-control" id="person_phone" name="person_phone" placeholder="Digite o telefone" <?php if( !empty($data["person_phone"]) ){ ?>value="<?php echo htmlspecialchars( $data["person_phone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                            </div>
                            <div class="form-group">
                                <label for="person_mail">E-mail</label>
                                <input type="email" class="form-control" id="person_mail" name="person_mail" placeholder="Digite o e-mail" <?php if( !empty($data["person_mail"]) ){ ?>value="<?php echo htmlspecialchars( $data["person_mail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                            </div>
                            <div class="checkbox">                                
                                <label><input type="radio" name="user_level" value="1" <?php if( !empty($data["user_level"]) && $data["user_level"] == '1' ){ ?>checked<?php }else{ ?>checked<?php } ?>> Cliente</label>
                                <label><input type="radio" name="user_level" value="2" <?php if( !empty($data["user_level"]) && $data["user_level"] == '2' ){ ?>checked<?php } ?>> Editor  </label>
                                <label><input type="radio" name="user_level" value="3" <?php if( !empty($data["user_level"]) && $data["user_level"] == '3' ){ ?>checked<?php } ?>> Administrador  </label>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->