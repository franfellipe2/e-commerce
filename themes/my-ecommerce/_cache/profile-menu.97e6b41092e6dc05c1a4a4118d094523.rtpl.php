<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="list-group" id="menu">
    <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/profile" class="list-group-item list-group-item-action">Editar Dados</a>
    <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/profile/change-password" class="list-group-item list-group-item-action">Alterar Senha</a>
    <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/profile-orders" class="list-group-item list-group-item-action">Meus Pedidos</a>
    <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/login/logout" class="list-group-item list-group-item-action">Sair</a>
</div>