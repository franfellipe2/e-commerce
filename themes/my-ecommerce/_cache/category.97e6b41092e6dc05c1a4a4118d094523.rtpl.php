<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="product-bit-title text-center">
                    <h2><?php echo htmlspecialchars( $cat["descategory"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="single-product-area">
    <div class="zigzag-bottom"></div>
    <div class="container">
        <div class="row">
            
            <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>

            <div class="col-md-3 col-sm-6">
                <div class="single-shop-product">
                    <div class="product-upper">
                        <img src="<?php echo htmlspecialchars( $value1["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="$value.desproduct">
                    </div>
                    <h2><a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/product/<?php echo htmlspecialchars( $value1["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></h2>
                    <div class="product-carousel-price">
                        <ins>R$<?php echo formatPrice($value1["vlprice"]); ?></ins>
                    </div>  
                    
                    <div class="product-option-shop">
                        <a class="add_to_cart_button" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/carrinho/add/<?php echo htmlspecialchars( $value1["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">Comprar</a>
                    </div>                       
                </div>
            </div>
            <?php } ?>

            
        </div>
        <?php if( $totalPage > 1 ){ ?>

        <div class="row">
            <div class="col-md-12">
                <div class="product-pagination text-center">
                    <nav>
                        <ul class="pagination">
                        <li>
                            <?php if( $currentpage > 1 ){ ?>

                            <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/categoria/<?php echo htmlspecialchars( $cat["idcategory"], ENT_COMPAT, 'UTF-8', FALSE ); ?>?page=<?php echo htmlspecialchars( $currentpage-1, ENT_COMPAT, 'UTF-8', FALSE ); ?>" aria-label="Previous">
                            <?php } ?>

                            <span aria-hidden="true">«</span>
                            </a>
                        </li>
                        <?php $counter1=-1;  if( isset($links) && ( is_array($links) || $links instanceof Traversable ) && sizeof($links) ) foreach( $links as $key1 => $value1 ){ $counter1++; ?>

                        <li class="<?php echo htmlspecialchars( $value1["active"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><a href='<?php echo htmlspecialchars( $value1["link"], ENT_COMPAT, 'UTF-8', FALSE ); ?>'><?php echo htmlspecialchars( $value1["page"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
                        <?php } ?>

                        <?php if( $currentpage < $totalPage ){ ?>

                        <li>
                            <a href="<?php echo htmlspecialchars( $HOME, ENT_COMPAT, 'UTF-8', FALSE ); ?>/categoria/<?php echo htmlspecialchars( $cat["idcategory"], ENT_COMPAT, 'UTF-8', FALSE ); ?>?page=<?php echo htmlspecialchars( $currentpage+1, ENT_COMPAT, 'UTF-8', FALSE ); ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                            </a>
                        </li>
                        <?php } ?>

                        </ul>
                    </nav>                        
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>