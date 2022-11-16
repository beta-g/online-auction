<?php include_once("header.php")?>
<?php require("utilities.php")?>
<?php include 'opendb.php'?>


<?php

if (isset($_SESSION['user_id']))
{
$user_id = $_SESSION['user_id'];


if (isset($_POST["submit"])) {
    $full_name = mysqli_real_escape_string($connection, $_POST["full_name"]);
    $add= mysqli_real_escape_string($connection, $_POST["add"]);
    $phone= mysqli_real_escape_string($connection, $_POST["phone"]);
    $password = mysqli_real_escape_string($connection, md5($_POST["password"]));
    $cpassword = mysqli_real_escape_string($connection, md5($_POST["cpassword"]));
    
    if ($password === $cpassword) {
        
    
            $sql = "UPDATE users SET   Phone_Num='$phone',Address='$add', Full_name='$full_name', password='$password' WHERE id='{$_SESSION["user_id"]}'";
            $result = mysqli_query($connection, $sql);
            if ($result) {
                echo "<script>alert('Profile Updated successfully.');</script>";
               
            } else {
                echo "<script>alert('Profile can not Updated.');</script>";
                echo  $connection->error;
            }
        
    } else {
        echo "<script>alert('Password not matched. Please try again.');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<style>
    
    .container {
    width: 600px;
    min-height: 500px;
    background: #FFF;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0,0,0,.3);
    padding: 40px 30px;
}
input {
    width: 100%;
    height: 100%;
    border: 2px solid #e7e7e7;
    padding: 15px 20px;
    font-size: 1rem;
    border-radius: 30px;
    background: transparent;
    outline: none;
    transition: .3s;}
    .btn{
        display: block;
    width: 100%;
    padding: 15px 20px;
    text-align: center;
    border: none;
    background: #a29bfe;
    outline: none;
    border-radius: 30px;
    font-size: 1.2rem;
    color: #FFF;
    cursor: pointer;
    transition: .3s;
    }
    
    </style>


<body class="profile-page">
    <div class="wrapper">
  
        <form action="" method="post" enctype="multipart/form-data">
            <?php

            $sql = "SELECT * FROM users WHERE id='{$_SESSION["user_id"]}'";
            $result = mysqli_query($connection, $sql);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
              <div class="container">
              <p class="login-text" style="font-size: 2rem; font-weight: 800;">Profile</p>
                    <div class="input-group">
                    <label>Name</label>
                        <input type="text" id="full_name" name="full_name" placeholder="Full Name" value="<?php 
                        
                        echo $row['Full_name']; ?>" required>
                    </div>
                    <br>
                    <div class="input-group">
                    <label>Email</label>
                        <input type="email" id="email" name="email" placeholder="Email Address" value="<?php echo $row['email']; ?>" disabled required>
                    </div>
                    <br>
                    <div class="input-group">
                    <label>Address</label>
                        <input type="text" id="add" name="add" placeholder="Address" value="<?php echo $row['Address']; ?>" required>
                    </div>
                    <br>
                    <div class="input-group">
                    <label>Phone Number</label>
                        <input type="text" id="phone" name="phone" placeholder="Phone number" value="<?php echo $row['Phone_Num']; ?>" required>
                    </div>
                    <br>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $row['password']; ?>" required>
                    </div>
                      <br>
                    <div class="input-group">
                        <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password" value="<?php echo $row['password']; ?>" required>
                    </div>
                    <br>
                    <div  class="input-group">
                        
                <button type="submit" name="submit" class="btn">Update Profile</button>
            </div>
                </div>
            <?php
                }
            }

            ?>
            
        </form>
    </div>
</body>

</html>
<?php
}
?>