<?php



if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login.php','_self')</script>";

}

else {

?>

<?php

if(isset($_GET['bid_delete'])){

$delete_id = $_GET['bid_delete'];

$delete_order = "delete from bids where bid_id='$delete_id'";

$run_delete = mysqli_query($con,$delete_order);

if($run_delete){

echo "<script>alert('Bids Has Been Deleted')</script>";

echo "<script>window.open('index.php?view_bid','_self')</script>";


}


}



?>



<?php }  ?>