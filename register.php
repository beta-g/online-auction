
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="css/login.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<title>Register Form</title>
</head>
<body>
	<div class="container">
		<form   class="login-email" method="POST"  enctype="multipart/form-data" action="process_registration.php">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Register</p>
			<label  class="">Registering As</label>
							
								<div class="">
									<br>
									<input type="radio" name="accountType"value="buyer" />Buyer
									<input type="radio" name="accountType" value="seller" />Seller
								</div>
								<br>
							
			<div class="input-group">
				<input type="text" placeholder="Username" name="name" required>
			</div>
			<div class="input-group">
				<input type="text" placeholder="Address" name="Address" required>
			</div>
			
			<div class="input-group">
				<input type="text" placeholder="phone number" name="phone_num"  required>
			</div>
			<div class="input-group">
				<input type="email" placeholder="Email" name="email" required>
			</div>
			<div class="input-group">
				<input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
				<input type="password" placeholder="Confirm Password" name="passwordrepeat" required>
			</div>
			<div class="input-group">
				<button  name="register" class="btn">Register</button>
			</div>
		</form>
		<div class="text-center">Already have an account? <a href="browse.php" data-toggle="modal" data-target="#loginModal">Login</a>

</div>
	</div>
</body>
</html>