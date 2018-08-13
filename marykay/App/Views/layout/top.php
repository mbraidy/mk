<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mary Kay</title>
	<link rel="manifest" href="manifest.json">
    <meta name="description" content="Test site for Mary Kay.">
    <meta name="theme-color" content="#317EFB"/>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="MK">
    <meta name="apple-mobile-web-app-title" content="MK">
    <meta name="msapplication-starturl" content="/">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel='stylesheet' href="/assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link rel='stylesheet' href="/assets/css/style.css"/>
</head>
<body>
    <div class="header">
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		        <a href="/"><img src='/assets/images/favicon.ico' style="width:45px" title="Mary Kay" alt="Mary Kay"></a>
		    </div>

		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <form class="navbar-form navbar-left">
		        <div class="form-group ">
		          <input type="text" class="form-control" placeholder="Search">
		        </div>
		      </form>
		      <ul class="nav navbar-nav navbar-right">
    	          <?php if (!$logged || $_SESSION['ROLE']!=="superman") { ?>
   	    			<?php if (empty($_SESSION['WISH'])) { ?>
   	    				<li id="wish-list" title="Add some items to your wishlist first">
    	    				<i class="fa fa-heart fa-2x shopping-empty" data-items="0"></i>
    	    			</li>
    	    		<?php } else { ?>
    	    			<li id="wish-list" title="Go to wish list">
    	    				<i class="fa fa-heart fa-2x shopping" data-items="<?=count($_SESSION['WISH'])?>"></i>
    	    			</li>
    	    		<?php }
    	    		      if (empty($_SESSION['CART'])) { ?>
   	    				<li id="shopping-cart" title="Your shopping cart is empty">
    	    				<i class="fa fa-shopping-basket fa-2x shopping-empty" data-items="0"></i>
    	    			</li>
    	    		<?php } else { ?>
    	    			<li id="shopping-cart" title="Go to shopping cart.">
    	    				<i class="fa fa-shopping-basket fa-2x shopping" data-items="<?=count($_SESSION['CART'])?>"></i>
    	    			</li>
    	    		<?php } ?>
    		      <?php } ?>
		        <li class="dropdown">
		          <a href="#" class="dropdown-toggle"
		          	 data-toggle="dropdown" role="button"
		          	 aria-haspopup="true" aria-expanded="false">
    		          <b><?= ($logged)?$_SESSION['NAME']:"Guest"?></b>
		          	 <span class="caret"></span></a>
		          <ul class="dropdown-menu">
		          <?php if ($logged && $_SESSION['ROLE']==="customer") { ?>
                	            <li><a href="/user/profile">Profile</a></li>
                	            <li><a href="/purchase/wish">Wishlist</a></li>
                	            <li><a href="/purchase/cart">Shopping cart</a></li>
                	            <li><a href="#">Previous orders</a></li>
                	            <li role="separator" class="divider"></li>
                	            <li><a href="/user/logout">Log out</a></li>
    		      <?php } elseif ($logged && $_SESSION['ROLE']==="superman") { ?>
                	            <li><a href="/item/update">Items</a></li>
                	            <li><a href="/item/orders">Orders</a></li>
                	            <li><a href="/user/list">Customers</a></li>
                	            <li role="separator" class="divider"></li>
                	            <li><a href="/user/logout">Log out</a></li>
    		      <?php } else { ?>
    		          			<li><a href="/user/login">Log in</a></li>
    		              		<li role="separator" class="divider"></li>
    		              		<li><a href="/user/signup">Sign up</a></li>
    		              		<li role="separator" class="divider"></li>
    		              		<li><a href="/user/logout">Continue as guest</a></li>
    		      <?php } ?>
		          </ul>
		        </li>
		      </ul>
		    </div>
		  </div>
		</nav>
    </div>