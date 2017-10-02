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
                <div class="cart-collaterals">
                    <h2>Alterar Senha</h2>
                </div>

                <?php if( $changePassSuccess != '' ){ ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars( $changePassSuccess, ENT_COMPAT, 'UTF-8', FALSE ); ?>

                </div>
                <?php } ?>


                <form action="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/profile/change-password" method="post">
                    <div class="box-body">
                        <div class='form-group <?php if( !empty($error["current_pass"]) ){ ?>has-warning<?php } ?>'>
                            <label for="current_pass">Senha Atual</label>
                            <input type="password" class="form-control" id="current_pass" name="current_pass">
                            <span class="help-block"><?php if( !empty($error["current_pass"]) ){ ?><?php echo htmlspecialchars( $error["current_pass"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                        </div>
                        <hr>                    
                        <div class='form-group <?php if( !empty($error["new_pass"]) ){ ?>has-warning<?php } ?>'>
                            <label for="new_pass">Nova Senha</label>
                            <input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="Digite a senha">
                            <span class="help-block"><?php if( !empty($error["new_pass"]) ){ ?><?php echo htmlspecialchars( $error["new_pass"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                        </div>
                        <div class='form-group <?php if( !empty($error["new_pass_confirm"]) ){ ?>has-warning<?php } ?>'>
                            <label for="new_pass_confirm">Confirme a nova senha!</label>
                            <input type="password" class="form-control" id="new_pass" name="new_pass_confirm" placeholder="Repita a senha">
                            <span class="help-block"><?php if( !empty($error["new_pass_confirm"]) ){ ?><?php echo htmlspecialchars( $error["new_pass_confirm"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>