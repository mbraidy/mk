<?php include realpath(dirname(__DIR__)."/layout/top.php");?>

<div class="container">
    <form id="updateprofile" action="/user/update" method="POST" >

    <h1><?= $username ?>'s Profile:</h1>

    <!-- One "tab" for each step in the form: -->
    <div class="tab">Custemor's name:
      <p><input placeholder="First name *" name="name" value="<?= $profile->name?>" class="required" required></p>
      <p><input placeholder="Last name *" name="surname" value="<?= $profile->surname?>" class="required" required></p>
    </div>

    <div class="tab">Contact Info:
      <p><input placeholder="E-mail *" name="email" value="<?= $profile->email?>" class="required" required></p>
      <p><input placeholder="Phone" name="telephone" value="<?= $profile->telephone?>" class="optional"></p>
    </div>

    <div class="tab">Date of birth:
      <p><input placeholder="yyyy-mm-dd" name="dob" value="<?= $profile->dob?>"
      			required pattern="[0-9]{4}-[0-1][0-9]-[0-3][0-9]
       			max="<?= date('Y-m-d', time()-18*365.2422*24*60*60) ?>"
       			min="<?= date('Y-m-d', time()-100*365.2422*24*60*60) ?>"
      		    type="date" class="required">
      </p>
    </div>

    <div class="tab">Address:
      <p><input placeholder="Address 1 *" name="address1" value="<?= $address['address1']?>" class="required" required></p>
      <p><input placeholder="Address 2" name="address2" value="<?= $address['address2']?>" class="optional" ></p>
      <p><input placeholder="C/O" name="c_o" value="<?= $address['c_o']?>" class="optional"></p>
      <p><input placeholder="Post code*" name="postCode" value="<?= $address['postCode']?>" class="required" required></p>
      <div class="row">
      	<div class="col-xs-5">
        	<label for="countryDD"><b>Country</b></label>
            <select placeholder="Country" name="countryCD" id="countryDD" value="<?= $address['country']?>"  class="optional selectpicker1">
          		<?= $countries ?>
            </select>
        </div>
     	<div class="col-xs-offset-2 col-xs-5 pull-right">
        	<label for="cityDD"><b>City</b></label>
          	<select placeholder="City *" name="cityID" id="cityDD" value="<?= $address['city']?>" class="required selectpicker1" required>
         		<?= $cities ?>
           	</select>
        </div>
      </div>
    </div>
    <div class="tab">Payment method:
    <table class="table table-responsive table-striped table-bordered table-hover">
    	<thead class="thead-dark">
    		<tr>
    			<th rowspan=2>Type</th>
       			<th rowspan=2>Name on card</th>
       			<th rowspan=2>Code</th>
       			<th colspan=2>Expiry Date</th>
    		</tr>
    		<tr>
       			<th>Month></th>
       			<th>Year></th>
    		</tr>
    	</thead>
    	<tbody>
    		<?php foreach ($cards as $card) { ?>
    		      <tr>
                      <td>
                      	<select id="typeID" name="typeID" value="<?= $card['cardType']?>" class="required selectpicker1" title="Choose one card...">
                      		<?= $cardTypes ?>
                      	</select>
                      </td>
                      <td>
                      	<input placeholder="Name on card *" name="nameOnCard" value="<?= $card['nameOnCard']?>" class="required" required>
                      </td>
                      <td>
                      	<input placeholder="Code *" name="code" value="<?= $card['code']?>"
                      		   pattern="[3-6][0-9]{15}"  class="required" required>
                      </td>
                      <td>
                          <select id="lastMonth" name="lastMonth" />
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                          </select>
                      </td>
                      <td>
                          <select id="lastYear" name="lastYear" />
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                          </select>
                     </td>
    		      </tr>
    		<?php } ?>
    	</tbody>
    </table>
    </div>
	<div id="profile_update_controls">
        <div id="navigation">
            <button type="button" id="prevBtn">Previous</button>
            <button type="button" id="nextBtn">Next</button>
        </div>
        <div id="breadcumbs">
          <span class="step">Profile</span>
          <span class="step">Contact</span>
          <span class="step">D.o.B</span>
          <span class="step">Address</span>
          <span class="step">Payment</span>
        </div>
    </div>

    </form>
</div>
<?php include realpath(dirname(__DIR__)."/layout/bottom.php");?>
