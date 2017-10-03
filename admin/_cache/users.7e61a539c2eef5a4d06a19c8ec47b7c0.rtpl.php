<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Lista de Usuários
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/admin"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/admin/users">Usuários</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header">
                        <a href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/users/create" class="btn btn-success">Cadastrar Usuário</a>
                        <div class="box-tools">
                            <form action="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/users/" method="get">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="search" class="form-control pull-right" placeholder="Buscar" value='<?php if( !empty($search) ){ ?><?php echo htmlspecialchars( $search, ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Login</th>
                                    <th style="width: 60px">Nível</th>
                                    <th style="width: 140px">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter1=-1;  if( isset($users) && ( is_array($users) || $users instanceof Traversable ) && sizeof($users) ) foreach( $users as $key1 => $value1 ){ $counter1++; ?>

                                <tr <?php if( $value1["user_id"] == $session["user_id"] ){ ?>style="border-left:4px solid #d73925;background:#fff2f1"<?php } ?>>
                                    <td><?php echo htmlspecialchars( $value1["user_id"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["person_mail"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                                    <td><?php echo htmlspecialchars( $value1["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td/>
                                    <td>
                                        <?php if( $value1["user_level"] == 3 ){ ?>Admin<?php } ?>

                                        <?php if( $value1["user_level"] == 2 ){ ?>Editor<?php } ?>

                                        <?php if( $value1["user_level"] == 1 ){ ?>Cliente<?php } ?>

                                    </td>
                                    <td>
                                        <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/admin/users/<?php echo htmlspecialchars( $value1["user_id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                                        <?php if( $value1["user_id"] != $session["user_id"] ){ ?>

                                        <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/admin/users/<?php echo htmlspecialchars( $value1["user_id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete" onclick="return confirm('Deseja realmente excluir este registro?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Excluir</a>
                                        <?php } ?>

                                    </td>
                                </tr>
                                <?php } ?>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?php if( $currentpage > 1 ){ ?>

                            <li><a href="<?php echo htmlspecialchars( $firstLink, ENT_COMPAT, 'UTF-8', FALSE ); ?>">Primeiro</a></li>
                            <?php } ?>

                            <?php $counter1=-1;  if( isset($pages) && ( is_array($pages) || $pages instanceof Traversable ) && sizeof($pages) ) foreach( $pages as $key1 => $value1 ){ $counter1++; ?>                
                            <li class="<?php echo htmlspecialchars( $value1["active"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><a href="<?php echo htmlspecialchars( $value1["href"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["text"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
                            <?php } ?>

                            <?php if( $currentpage < $totalpages ){ ?>

                            <li><a href="<?php echo htmlspecialchars( $endLink, ENT_COMPAT, 'UTF-8', FALSE ); ?>">Último</a></li>
                            <?php } ?>

                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->