<?php


if(!isset($_SESSION['admin_email'])){

echo "<script>window.open('login.php','_self')</script>";

}

else {


?>

<div class="row"><!-- 1 row Starts -->

<div class="col-lg-12"><!-- col-lg-12 Starts -->

<ol class="breadcrumb"><!-- breadcrumb Starts  --->

<li class="active">

<i class="fa fa-dashboard"></i> Dashboard / View Bids

</li>

</ol><!-- breadcrumb Ends  --->

</div><!-- col-lg-12 Ends -->

</div><!-- 1 row Ends -->


<div class="row"><!-- 2 row Starts -->

<div class="col-lg-12"><!-- col-lg-12 Starts -->

<div class="panel panel-default"><!-- panel panel-default Starts -->

<div class="panel-heading"><!-- panel-heading Starts -->

<h3 class="panel-title"><!-- panel-title Starts -->

<i class="fa fa-money fa-fw"></i> View Bids

</h3><!-- panel-title Ends -->

</div><!-- panel-heading Ends -->

<div class="panel-body"><!-- panel-body Starts -->

<div class="table-responsive"><!-- table-responsive Starts -->

<table class="table table-bordered table-hover table-striped"><!-- table table-bordered table-hover table-striped Starts -->

<thead><!-- thead Starts -->

<tr>

<th>user No:</th>
<th>Customer Email:</th>
<th>Product Title:</th>
<th>Bid Date:</th>
<th>Bid Price:</th>
<th>Delete:</th>


</tr>

</thead><!-- thead Ends -->


<tbody><!-- tbody Starts -->

<?php

$i = 0;

$get_bid = "select * from bids";

$run_bid = mysqli_query($con,$get_bid);

while ($row_bid = mysqli_fetch_array($run_bid)) {

$bid_id = $row_bid['bid_id'];

$c_id = $row_bid['user_id'];



$product_id = $row_bid['listing_id'];

$get_products = "select * from listings where listing_id='$product_id'";

$run_products = mysqli_query($con,$get_products);

$row_products = mysqli_fetch_array($run_products);

$product_title = $row_products['item_title'];

$i++;

?>

<tr>

<td><?php echo $i; ?></td>

<td>
<?php 

$get_customer = "select * from users where id='$c_id'";

$run_customer = mysqli_query($con,$get_customer);

$row_customer = mysqli_fetch_array($run_customer);

$customer_email = $row_customer['email'];

echo $customer_email;

 ?>
 </td>



<td><?php echo $product_title; ?></td>

<td>
<?php

$get_customer_bid = "select * from bids where bid_id='$bid_id'";

$run_customer_bid = mysqli_query($con,$get_customer_bid);

$row_customer_bid = mysqli_fetch_array($run_customer_bid);

$bid_date = $row_customer_bid['bidtime'];

$due_amount = $row_customer_bid['bidprice'];

echo $bid_date;

?>
</td>

<td>Br. <?php echo $due_amount; ?></td>


<td>

<a href="index.php?bid_delete=<?php echo $bid_id; ?>" >

<i class="fa fa-trash-o" ></i> Delete

</a>

</td>


</tr>

<?php } ?>

</tbody><!-- tbody Ends -->

</table><!-- table table-bordered table-hover table-striped Ends -->

</div><!-- table-responsive Ends -->

</div><!-- panel-body Ends -->

</div><!-- panel panel-default Ends -->

</div><!-- col-lg-12 Ends -->

</div><!-- 2 row Ends -->


<?php } ?>