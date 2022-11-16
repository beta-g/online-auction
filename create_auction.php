<?php include_once("header.php")?>
<?php include 'opendb.php'?>

<?php
//I (Jacob) already uncommented out this section. check original if questions arise.  
  if (!isset($_SESSION['account_type']) || $_SESSION['account_type'] != 'seller') {
    header('Location: browse.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <title>pls</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <script src="bootstrap/js/bootstrap.min.js"></script>

  <style>



.container { width: 600px;
    min-height: 500px;
    background: #FFF;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0,0,0,.3);
    padding: 40px 30px;
   
  
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
   }



</style>


<script>

function ValidateBidForm()
{

    
    var name        =BidForm.name;
    var price       =BidForm.price;
    var reserve_price=BidForm.reserve_price;
    var description =BidForm.description;
    var edate       =BidForm.edate;


    if (name.value == "")
    {
        window.alert("Please Enter Product Name.");
        name.focus();
        return false;
    }
    
   

if(document.getElementById("catagory").value == "Select Catagory")
   {
      alert("Please select Catagory"); // prompt user
      document.getElementById("catagory").focus(); //set focus back to control
      return false;
   }
   

    if (price.value == "")
    {
        window.alert("Need Product Base Price.");
        price.focus();
        return false;
    }


    if (description.value=="")
    {
        window.alert("Please Give Product Description");
        description.focus();
        return false;
    }

    
    

    if (edate.value=="")
    {
        window.alert("Please Enter End Date For Bid");
        Quantity.focus();
        return false;
    }
$datenow = date("Y-m-d");

if(sdate<datenow)
{
      window.alert("wrong date format");
        Quantity.focus();
        return false;
}
if(edate<datenow||edate<sdate)
{
      window.alert("wrong date format");
        Quantity.focus();
        return false;
}

  
    return true;
}


</script>

</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
  

//image


$folder = "uploads/";
$image_file=$_FILES['image']['name'];
 $file = $_FILES['image']['tmp_name'];
 $path = $folder . $image_file;  
 $target_file=$folder.basename($image_file);
 $imageFileType=pathinfo($target_file,PATHINFO_EXTENSION);
//Allow only JPG, JPEG, PNG & GIF etc formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
 $error[] = 'Sorry, only JPG, JPEG, PNG & GIF files are allowed';   
}
//Set image upload size 
    if ($_FILES["image"]["size"] > 1048576) {
   $error[] = 'Sorry, your image is too large. Upload less than 1 MB in size.';
}
if(!isset($error))
{
	//move image to the folder 
move_uploaded_file($file,$target_file); 
$result=mysqli_query($connection,"INSERT INTO listings(image) VALUES('$image_file')"); 
if($result)
{
	$image_success=1;  
}
else 
{
	echo 'Something went wrong'; 
}
}
		
if(isset($error)){ 

foreach ($error as $error) { 
	echo '<div class="message">'.$error.'</div><br>'; 	
}
}

	
     //$uname=$_SESSION['uname'];
     //echo $uname;
     $name=htmlspecialchars(mysqli_real_escape_string($connection, $_POST['name']));
     //echo $name;
     $Category = $_POST['category'];
    // echo $catagory;
     $price=$_POST['price'];
     //echo $price;
     $description=htmlspecialchars(mysqli_real_escape_string($connection, $_POST['description']));
    // echo $description;
     $reserve_price=$_POST['reserve_price'];
     

date_default_timezone_set('Europe/London');
$current = date('Y-m-d H:i', time());
$posttime = $current;
     $edate=$_POST['edate'];
    
     $user_id = $_SESSION['user_id'];
     $Catquery = "(SELECT catID FROM categories WHERE name = '$Category')";


     $query="insert listings (item_title, posttime, user_id, itemdescription, category, startprice, reserveprice, endtime,image) VALUES ('$name', '$posttime', $user_id, '$description', $Catquery, $price, $reserve_price, '$edate', '$image_file')";
     $exe= mysqli_query($connection,$query);
     if (!$exe) {
        echo '<script language="javascript">';
        echo 'alert("insertion Problem")';
        echo '</script>';
        echo "Error creating database: " . mysqli_error($connection);
      
     }
     else
     {
        echo '<script language="javascript">';
        echo 'alert("successful")';
        echo '</script>';
     }
     
    }

?>

<div class="container">
    <div class="login-email">
      <div class="panel panel-primary">
        <div class="panel-body">
      <form class=""action="" method="POST" enctype="multipart/form-data" role="form" name="BidForm">
            <div class="form-group">
              <h2>Add New Product</h2>
            </div>
             <div class="form-group">
              <label class="control-label" for="signupName">Product Name</label>
              <input type="text" name="name" maxlength="50" class="form-control" required>
            </div>

              <div class="form-group">
              <label for=""class="control-label" for="signupName">Product Catagory</label>
              <select name="category" class="form-control" id="Catagory">
              <?php $cat_query = "SELECT name FROM categories";
		$cat_result = mysqli_query($connection, $cat_query)
			or die('Error making select cat query');
	
		while ($cat_row = mysqli_fetch_array($cat_result)) {
			echo ('<option value='. $cat_row[0]. '>'. $cat_row[0] .'</option>');
		}
		?>
    </select>
            </div>
          
            <div class="form-group">
              <label class="control-label" for="signupEmail">Product Price</label>
              <input  type="text" name="price" maxlength="50" class="form-control" required>
            </div>
          <div class="form-group">
              <label class="control-label" for="signupEmail">Reserve Price</label>
              <input  type="text" name="reserve_price" maxlength="50" class="form-control" required>
            </div>
              <div class="form-group">
              <label class="control-label">Product Description</label>
              <textarea rows="2" cols="62" name="description"> </textarea>
            </div>
            
              <div class="form-group">
              <label class="control-label">End Date</label>
              <input  type="date" name="edate" maxlength="50" class="form-control" required>
            </div>
            <br>
             <div class="form-group">
             <label class="control-label">Product Picture</label>
              <input type="file" name="image" class="form-control" required/>
            </div>
            
            <div class="form-group">

              <button id="signupSubmit" type="submit" class="btn btn-info btn-block"  onclick ="return ValidateBidForm();">Add Now</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

</body>
</html>


     
































