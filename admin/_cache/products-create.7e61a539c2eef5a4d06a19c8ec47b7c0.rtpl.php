<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Lista de Produtos
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/categories">Categorias</a></li>
    <li class="active"><a href="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/categories/create">Cadastrar</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Novo Produto</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" action="<?php echo htmlspecialchars( $ADMIN_URL, ENT_COMPAT, 'UTF-8', FALSE ); ?>/products/create" method="post">
          <div class="box-body">            
            <div class="form-group <?php if( !empty($error["desproduct"]) ){ ?>has-warning<?php } ?>">
              <label for="desproduct">Nome da produto</label>
              <input type="text" class="form-control" id="desproduct" name="desproduct" placeholder="Digite o nome do produto" value='<?php if( !empty($post["desproduct"]) ){ ?><?php echo htmlspecialchars( $post["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
              <?php if( !empty($error["desproduct"]) ){ ?><span class="help-block"><?php echo htmlspecialchars( $error["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span><?php } ?>

            </div>
            <div class="form-group <?php if( !empty($error["vlprice"]) ){ ?>has-warning<?php } ?>">
              <label for="vlprice">Preço</label>
              <input type="number" class="form-control" id="vlprice" name="vlprice" step="0.01" placeholder="0.00" value='<?php if( !empty($post["vlprice"]) ){ ?><?php echo htmlspecialchars( $post["vlprice"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
               <?php if( !empty($error["vlprice"]) ){ ?><span class="help-block"><?php echo htmlspecialchars( $error["vlprice"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span><?php } ?>

            </div>
            <div class="form-group <?php if( !empty($error["vlwidth"]) ){ ?>has-warning<?php } ?>">
              <label for="vlwidth">Largura</label>
              <input type="number" class="form-control" id="vlwidth" name="vlwidth" step="0.01" placeholder="0.00" value='<?php if( !empty($post["vlwidth"]) ){ ?><?php echo htmlspecialchars( $post["vlwidth"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
               <?php if( !empty($error["vlwidth"]) ){ ?><span class="help-block"><?php echo htmlspecialchars( $error["vlwidth"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span><?php } ?>

            </div>
            <div class="form-group <?php if( !empty($error["vlheight"]) ){ ?>has-warning<?php } ?>">
              <label for="vlheight">Altura</label>
              <input type="number" class="form-control" id="vlheight" name="vlheight" step="0.01" placeholder="0.00" value='<?php if( !empty($post["vlheight"]) ){ ?><?php echo htmlspecialchars( $post["vlheight"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
               <?php if( !empty($error["vlheight"]) ){ ?><span class="help-block"><?php echo htmlspecialchars( $error["vlheight"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span><?php } ?>

            </div>
            <div class="form-group <?php if( !empty($error["vllength"]) ){ ?>has-warning<?php } ?>">
              <label for="vllength">Comprimento</label>
              <input type="number" class="form-control" id="vllength" name="vllength" step="0.01" placeholder="0.00" value='<?php if( !empty($post["vllength"]) ){ ?><?php echo htmlspecialchars( $post["vllength"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
               <?php if( !empty($error["vllength"]) ){ ?><span class="help-block"><?php echo htmlspecialchars( $error["vllength"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span><?php } ?>

            </div>
            <div class="form-group <?php if( !empty($error["vlweight"]) ){ ?>has-warning<?php } ?>">
              <label for="vlweight">Peso</label>
              <input type="number" class="form-control" id="vlweight" name="vlweight" step="0.01" placeholder="0.00" value='<?php if( !empty($post["vlweight"]) ){ ?><?php echo htmlspecialchars( $post["vlweight"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>'>
               <?php if( !empty($error["vlweight"]) ){ ?><span class="help-block"><?php echo htmlspecialchars( $error["vlweight"], ENT_COMPAT, 'UTF-8', FALSE ); ?></span><?php } ?>

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