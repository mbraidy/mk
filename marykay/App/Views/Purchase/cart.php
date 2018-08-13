<?php include realpath(dirname(__DIR__)."/layout/top.php");?>

<div class="container">
	<div class="alert alert-danger" role="alert" id="error-explanation" hidden>
         <h4 class="alert-heading">Updating</h4>
         <i class="fa fa-meh-rolling-eyes fa-3x text-danger"></i>
         <p>Failed! Item not saved!</p>
         <p id="error-explanation"></p>
    </div>
    <h1 id="shopping_main_header">Shopping Cart</h1>
    <div id="shopping_cart">
    	<div id="customer_part">
            <div class="profile personal">
        	  <h3>Profile</h3>
              <p><b>Name: </b><?= $profile->name?> <?= $profile->surname?></p>
              <p><b>Date of birth: </b><?= $profile->dob?></p>
              <p><b>E-mail: </b><?= $profile->email?></p>
              <p><b>Telephone: </b><?= $profile->telephone?></p>
              <br/>
         	  <h3>Shipment address</h3>
              <p><b>Address 1: </b><?= $address['address1']?></p>
              <p><b>Address 2: </b><?= $address['address2']?></p>
              <p><b>C/O: </b><?= $address['c_o']?></p>
              <p><b>Zip/Post Code: </b><?= $address['postCode']?></p>
              <p><b>City: </b><?= $address['city']?></p>
              <p><b>Country: </b><?= $address['country']?></p>
              <br/>
              <h3>Payment method</h3>
               	<div class="card_code"><?= App\Models\Card::encodeCard($cards[0]['code'])?></div>
               	<br/>
               	<div class="row">
               		<div class="col-xs-offset-4 col-xs-6 validity_header">
               			<span>Month / Year</span>
               		</div>
           		</div>
               	<div class="row">
               		<div class="col-xs-offset-2 col-xs-2 validity_header">
               			<span>Valid</span>
               			<br/>
               			<span>Thru</span>
               		</div>
               		<div class="col-xs-6 validity_date">
               			<span><?="{$cards[0]['lastMonth']} /  {$cards[0]['lastYear']}"?></span>
               		</div>
           		</div>
                <br/>
                <div class="card_owner"><?= $cards[0]['nameOnCard']?></div>
                &emsp;&emsp;
                <div class="card_type"><?= $cards[0]['cardType']?></div>
            </div>
        </div>
    	<div id="items_part">
    		<h3>Items in your shopping cart</h3>
    		<form action="/purchase/execute" method="post">
        		<div class="itmes_in_cart">
    	        	<?php
                        foreach ($items as $key=>$item) { ?>
                    	    <div class="ordered_item">
                    	    	<input type="hidden" name="items[<?=$key?>]" value="<?=$item['id']?>">7>
                    	    	<div class="item_image">
                               	    <img src=<?= $item['picture']?> alt="Cosmetic" class="img_icon" alt="<?= $item['alt']?>"
                        	      	   	  height="100px" title="<?= $item['title']?>">
                    	    	</div>
                    	    	<div class="item_info">
                        	        <div class='item_name'>
                        	        	<?= $item['name']?>
                           	    		<span class="toggle_to_wish pull-right" id="wish__<?=$item['id']?>" title="Send to wish list instead">
                            	    		<i class="fa fa-heart-o"></i>
                            	    	</span>
                            	    	&emsp;&emsp;
                            	    	<span class="delete_from_cart pull-right" id="delete__<?=$item['id']?>" title="Delete from shopping cart">
                            	    		<i class="fa fa-trash-o" id="delete__<?= $item['id']?>"></i>
                            	    	</span>
                        			</div>
                            	    <div class="purchasing_price_details">
                            	    	<table class="table table-responsive table-bordered table-condensed">
                            	    		<thead>
                            	    			<tr>
                            	    				<td class="col-xs-2"><span class="purchase_header">Price(<?= $item['currency']?>)</span></td>
                            	    				<td class="col-xs-2"><span class="purchase_header">Quantity</span></td>
                            	    				<td class="col-xs-3"><span class="purchase_header">Total(<?= $item['currency']?>)</span></td>
                            	    			</tr>
                            	    		</thead>
                            	    		<tbody>
                             	    			<tr>
                            	    				<td><input type='text' id="unit__<?=$item['id']?>" class="unit_price"  disabled value="<?= money_format('%.2n',$item['price'])?>" ></td>
                            	    				<td>
                            	    					<input type='number' min=1 max="<?=$item['available']?>"
                            	    						   id="qtty__<?=$item['id']?>" class="qtty_price" name="count[<?=$item['id']?>]"
                            	    						   value="1" data-max="<?=$item['available']?>">
                        	    					</td>
                            	    				<td><input type='text' id="total__<?=$item['id']?>" class="total_price" disabled value="<?= money_format('%.2n',$item['price'])?>"></td>
                            	    			</tr>
                            	    		</tbody>
                            	    	</table>
                            	    </div>
                   				</div>
                    	    </div>
                    <?php } ?>
        		</div>
    	    	<div id="paying_totals">
    	    		<h3>Total to pay</h3>
    	    		<div id="purchase_payments"'>
    	    			<table>
    	    				<tbody>
    	    					<tr><th>Items totals(<?= $item['currency']?>):</th><td><input type='text' disabled id='items_totals_field'/></td></tr>
    	    					<tr>
    	    						<th>Shipment(<?= $item['currency']?>):</th>
    	    						<td>
    	    							<input type='text' readonly id='items_shipment_field' value="49.00" name="shipment"/>
    	    						</td>
	    						</tr>
    	    					<tr>
    	    						<th>To pay(<?= $item['currency']?>):</th>
    	    						<td><input type='text' readonly id='grand_totals_field' name="total"/></td>
	    						</tr>
    	    				</tbody>
    	    			</table>
    	    		</div>
    	    	</div>
    	    	<div id="paying_part">
    	    		<h3>Confirmation and payment</h3>
    				<p id="condition_text">I have read and I agree to <a href="/document/pay">the terms</a> the terms of the company.</p>
        			<div class="row">
            			<label class="switch col-xs-offset-2 col-xs-2" id="accept_conditions">
                         	<input type="checkbox"/>
                         	<span class="slider round"></span>
                        </label>
                        <div class="col-xs-offset-4 col-xs-4">
                            <button type="submit" class="btn btn-warning btn-sm" id="pay_confirm" disabled
                            		title="You have to agree on the condition in order to continue.">Confirm</button>
                        </div>
            		</div>
            	</div>
        	</form>
	  	</div>
	</div>
</div>
<?php include realpath(dirname(__DIR__)."/layout/bottom.php");?>
