<?php if(!class_exists('Rain\Tpl')){exit;}?>

<div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Minha Conta</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">                
            <div class="col-md-3">
                <?php require $this->checkTemplate("profile-menu");?>

            </div>
            <div class="col-md-9">
                <?php if( $profileMsg != '' ){ ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars( $profileMsg, ENT_COMPAT, 'UTF-8', FALSE ); ?>

                </div>
                <?php } ?>               
                <form method="post" action="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/profile">
                    <div class='form-group <?php if( !empty($error["person_name"]) ){ ?>has-warning<?php } ?>'>
                        <label for="person_name">Nome Completo  <span class="required">*</span></label>
                        <input type="text" class="form-control" id="person_name" name="person_name" placeholder="Digite o nome" <?php if( !empty($user["person_name"]) ){ ?>value="<?php echo htmlspecialchars( $user["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                               <span class="help-block"><?php if( !empty($error["person_name"]) ){ ?><?php echo htmlspecialchars( $error["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                    </div>
                    <div class='form-group <?php if( !empty($error["user_login"]) ){ ?>has-warning<?php } ?>'>
                        <label for="user_login">Login  <span class="required">*</span></label>
                        <input type="text" class="form-control" id="user_login" name="user_login" placeholder="Digite o login" <?php if( !empty($user["user_login"]) ){ ?>value="<?php echo htmlspecialchars( $user["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                               <span class="help-block"><?php if( !empty($error["user_login"]) ){ ?><?php echo htmlspecialchars( $error["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                    </div>
                    <div class="form-group">
                        <label for="person_mail">E-mail  <span class="required">*</span></label>
                        <input type="email" class="form-control" id="person_mail" name="person_mail" placeholder="Digite o e-mail" <?php if( !empty($user["person_mail"]) ){ ?>value="<?php echo htmlspecialchars( $user["person_mail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?> required="required">
                    </div>
                    <div class="form-group">
                        <label for="person_phone">Telefone</label>
                        <input type="tel" class="form-control" id="person_phone" name="person_phone" placeholder="Digite o telefone" <?php if( !empty($user["person_phone"]) ){ ?>value="<?php echo htmlspecialchars( $user["person_phone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>