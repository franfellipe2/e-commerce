<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2>Registrar-se</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">

                <div class="alert alert-danger">
                    Error!
                </div>

                <form id="register-form-wrap" action="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/login/register" class="register" method="post">
                    <h2>Criar conta</h2>                                        
                    <div class='form-group <?php if( !empty($errorCreate["person_name"]) ){ ?>has-warning<?php } ?>'>
                        <label for="person_name">Nome Completo  <span class="required">*</span></label>
                        <input type="text" class="form-control" id="person_name" name="person_name" placeholder="Digite o nome" <?php if( !empty($create["person_name"]) ){ ?>value="<?php echo htmlspecialchars( $create["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                               <span class="help-block"><?php if( !empty($errorCreate["person_name"]) ){ ?><?php echo htmlspecialchars( $errorCreate["person_name"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                    </div>
                    <div class='form-group <?php if( !empty($errorCreate["user_login"]) ){ ?>has-warning<?php } ?>'>
                        <label for="user_login">Login  <span class="required">*</span></label>
                        <input type="text" class="form-control" id="user_login" name="user_login" placeholder="Digite o login" <?php if( !empty($create["user_login"]) ){ ?>value="<?php echo htmlspecialchars( $create["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                               <span class="help-block"><?php if( !empty($errorCreate["user_login"]) ){ ?><?php echo htmlspecialchars( $errorCreate["user_login"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                    </div>
                    <div class="form-group">
                        <label for="person_mail">E-mail  <span class="required">*</span></label>
                        <input type="email" class="form-control" id="person_mail" name="person_mail" placeholder="Digite o e-mail" <?php if( !empty($create["person_mail"]) ){ ?>value="<?php echo htmlspecialchars( $create["person_mail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?> required="required">
                    </div>
                    <div class="form-group">
                        <label for="person_phone">Telefone</label>
                        <input type="tel" class="form-control" id="person_phone" name="person_phone" placeholder="Digite o telefone" <?php if( !empty($create["person_phone"]) ){ ?>value="<?php echo htmlspecialchars( $create["person_phone"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"<?php } ?>>
                    </div>
                    <div class='form-group <?php if( !empty($errorCreate["user_pass"]) ){ ?>has-warning<?php } ?>'>
                        <label for="user_pass">Senha <span class="required">*</span></label>
                        <input type="password" class="form-control" id="user_pass" name="user_pass" placeholder="Digite a senha">
                        <span class="help-block"><?php if( !empty($errorCreate["user_pass"]) ){ ?><?php echo htmlspecialchars( $errorCreate["user_pass"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                    </div>
                    <div class='form-group <?php if( !empty($errorCreate["user_rpass"]) ){ ?>has-warning<?php } ?>'>
                        <label for="user_rpass">Repita a senha</label>
                        <input type="password" class="form-control" id="user_pass" name="user_rpass" placeholder="Repita a senha">
                        <span class="help-block"><?php if( !empty($errorCreate["user_rpass"]) ){ ?><?php echo htmlspecialchars( $errorCreate["user_rpass"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                    </div>
                    <div class="clear"></div>

                    <p class="form-row">
                        <input type="submit" value="Criar Conta" name="login" class="button">
                    </p>

                    <div class="clear"></div>
                </form>               
            </div>
        </div>
    </div>
</div>