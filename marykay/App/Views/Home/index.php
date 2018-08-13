<?php include realpath(dirname(__DIR__)."/layout/top.php");?>

<article>
<br style="clear: both" />
<div class="container cart">
<?php
    foreach ($items as $item) { ?>
	    <div class="thumbnail">
	    	<?php if (empty($_SESSION['ROLE']) || $_SESSION['ROLE']!=="superman") { ?>
    	    	<span class="send-to-wish" id="wish-<?=$item['id']?>" title="Add to wish list">
    	    		<?php if (empty($_SESSION['WISH']) || !in_array($item['id'], $_SESSION['WISH'])) { ?>
    	    				<i class="fa fa-heart-o fa-2x topleft"></i>
    	    		<?php } else { ?>
        	    			<i class="fa fa-heart fa-2x topleft text-success"></i>
        			<?php } ?>
    	    	</span>
    	    	<span class="send-to-cart" id="cart-<?=$item['id']?>" title="Add to shopping cart">
    	    		<?php if (empty($_SESSION['CART']) || !in_array($item['id'], $_SESSION['CART'])) { ?>
    	    				<i class="fa fa-cart-plus fa-2x topright pull-right"></i>
    	    		<?php } else { ?>
        	    			<i class="fa fa-shopping-cart fa-2x topright pull-right text-success"></i>
        			<?php } ?>
    	    	</span>
	      <?php } ?>
	      <img src=<?= $item['picture']?> alt="Cosmetic" class="img-responsive" alt="<?= $item['alt']?>"
	      	   title="<?= $item['title']?>">
	      <div class="caption">
	        <h3><?= $item['name']?></h3>
	        <p class="description"><?= $item['description']?></p>
	        <div class="clearfix">
	        	<div class="price pull-right"><?= $item['price']?> <?= $item['currency']?></div>
			</div>
	      </div>
	    </div>
<?php } ?>
</div>

</article>

<?php include realpath(dirname(__DIR__)."/layout/bottom.php");?>
