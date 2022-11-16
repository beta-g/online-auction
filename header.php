
<?php
  
  session_start();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap and FontAwesome CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Custom CSS file -->
  <link rel="stylesheet" href="css/custom.css">
  
  <title>KELAL Auction</title>
</head>


<body>
<nav class="navbar navbar-expand-lg navbar-light color: white; mx-2">
<nav class="navbar navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
   
   KELAL Auction House
  </a>
</nav>
<!-- Navbars -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
<?php
  // Displays either login or logout on the right, depending on user's
  // current status (session).
  if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    echo '<a class="nav-link" href="logout.php">Logout</a>';
  }
  else {
    echo '<button type="button" class="btn nav-link" data-toggle="modal" data-target="#loginModal">Login/Register</button>';
  }
?>
 </li>
  </ul>
</nav>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <ul class="navbar-nav align-right">
	<li class="nav-item mx-1">
  <li class="nav-item mx-1">
      <a class="nav-link" href="browse.php">Home</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="About.html">About</a>
    </li>
  <li class="nav-item mx-1">
      <a class="nav-link" href="contact.php">Contact Us</a>
    </li>
    

    </li>
<?php
  if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'buyer') {
  echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mybids.php">My Bids</a>
    </li>
	<li class="nav-item mx-1">
      <a class="nav-link" href="recommendations.php">Recommended</a>
    </li>
  <li class="nav-item mx-1">
      <a class="nav-link" href="mywatchlist.php">My Watchlist</a>
    </li>
    <li class="nav-item mx-1">
      <a class="nav-link" href="profile.php">My profile</a>
    </li>');
  }
  if (isset($_SESSION['account_type']) && $_SESSION['account_type'] == 'seller') {
  echo('
	<li class="nav-item mx-1">
      <a class="nav-link" href="mylistings.php">My Post</a>
    </li>
	<li class="nav-item ml-3">
      <a class="nav-link btn border-light" href="create_auction.php">+ Create auction</a>
      </li>
      <li class="nav-item mx-1">
      <a class="nav-link" href="profile.php">My Profile</a>
    </li>');
  }
?>
  </ul>
</nav>

<!-- Login modal -->
<div class="modal fade" id="loginModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Login</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <form method="post" action="login_result.php">
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" name="email" id="email" name="email" placeholder="Email">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" name="password" placeholder="Password">
          </div>
          <button type="submit" name ="Login" class="btn btn-primary form-control">Login</button>
        </form>
        <div class="text-center">or <a href="register.php">create an account</a></div>
      </div>

    </div>
  </div>
</div> <!-- End modal -->
