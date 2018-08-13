<?php include realpath(dirname(__DIR__)."/layout/top.php");?>

<div class="container">
  <form action="/user/signup" method="POST" id="signupForm">
  	<div class="row">
  		<div class="col-sm-5">
      	    <label for="username">Username</label>
        	<input type="text" placeholder="user" id="username" name="username" required>
 			<br/><br/>
			<label for="password">Password</label>
			<br/>
		  	<div class="row">
				<div class="col-sm-10">
                     <input type="password" placeholder="Password" required
                  		 id="password" name="password" class="signup"
    					 pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
    					 title="Must contain at least 1 number, 1 uppercase, 1 lowercase letter and at least 8 characters">
				</div>
				<div class="col-sm-2" id="hide-show-passwword"><i class="fa fa-eye-slash fa-2x text-success"></i></div>
            </div>

			<br/>
            <label for="password-repeat"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" required
            	   id="password-repeat" name="password-repeat" class="signup"
            	   pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
            	   title="Must contain at least 1 number, 1 uppercase, 1 lowercase letter and at least 8 characters">
    	</div>
    	<div class="col-sm-offset-1 col-sm-5">
        	<div class="alert alert-success" role="alert" id="message-save" hidden>
                <h4 class="alert-heading">Register a new customer</h4>
                <i class="fa fa-smile-wink fa-3x text-success">
                </i><p>User was registered successfully!</p>
            </div>
        	<div class="alert alert-danger" role="alert" id="message-dupl" hidden>
                 <h4 class="alert-heading">Register a new customer</h4>
                 <i class="fa fa-meh-rolling-eyes fa-3x text-danger"></i>
                 <p>Failed! User is already registered!</p>
           </div>
            <div id="password-strength">
              <h4>Password must contain the following:</h4>
              <p id="letter" class="invalid">At least <b>1 lowercase</b> letter</p>
              <p id="capital" class="invalid">At least <b>1 uppercase</b> letter</p>
              <p id="number" class="invalid">At least <b>1 number</b></p>
              <p id="length" class="invalid">At least <b>8 characters</b></p>
        	</div>
    	</div>
  	</div>
  	<div class="row">
  		<div class="col-sm-2">
            <label for="remember">Remember me</label>
        </div>
        <div class="col-sm-1">
			<input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px">
		</div>
	</div>

    <p>By creating an account you agree to our <a href="/document/join" style="color:dodgerblue">Terms & Privacy</a>.</p>

    <div class="clearfix">
      <button type="button" class="cancelbtn"><a href="/user/login">Cancel</a></button>
      <button type="button" class="signupbtn">Sign Up</button>
    </div>
  </form>
</div>

<?php include realpath(dirname(__DIR__)."/layout/bottom.php");?>
