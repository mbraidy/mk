<?php include realpath(dirname(__DIR__)."/layout/top.php");?>

<div class="container" id="user_profile">

    <h1><?= $username?>'s Profile: <a href="/user/update"><i id="edit-user" class="fa fa-edit fa-1x"></i></a></h1>
    <div class="profile personal">
	  <h3>Profile</h3>
      <p><b>First name: </b><?= $profile->name?></p>
      <p><b>Family name: </b><?= $profile->surname?></p>
      <p><b>Date of birth: </b><?= $profile->dob?></p>
	  <h3>Contact</h3>
      <p><b>E-mail: </b><?= $profile->email?></p>
      <p><b>Telephone: </b><?= $profile->telephone?></p>
    </div>
    <div class="address personal">
	  <h3>Address</h3>
      <p><b>Address 1: </b><?= $address['address1']?></p>
      <p><b>Address 2: </b><?= $address['address2']?></p>
      <p><b>C/O: </b><?= $address['c_o']?></p>
      <p><b>Zip/Post Code: </b><?= $address['postCode']?></p>
      <p><b>City: </b><?= $address['city']?></p>
      <p><b>Country: </b><?= $address['country']?></p>
    </div>
    <div class="clearfloat"></div>
    <div class="payment">
	  <h3>Payment method</h3>
    	<table class="table table-responsive table-striped table-bordered table-hover">
	  		<thead>
	  			<tr>
	  				<th>Card</th>
	  				<th>Name on Card</th>
	  				<th>Code</th>
	  				<th>Expiry Date</th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  			<?php foreach ($cards as $card) { ?>
	  			      <tr>
	  			      	<td><?= $card['cardType']?></td>
	  			      	<td><?= $card['nameOnCard']?></td>
	  			      	<td><?= $card['code']?></td>
	  			      	<td><?= "{$card['lastMonth']} / {$card['lastYear']}"?></td>
	  			      </tr>
	  			<?php } ?>
	  		</tbody>
	  </table>
    </div>
</div>
<?php include realpath(dirname(__DIR__)."/layout/bottom.php");?>
