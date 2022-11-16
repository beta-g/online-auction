<?php include('opendb.php');?>
<?php include_once("header.php")?>

<style>
/*
body {
	background-image: url(Silver-Blur-Background-Wallpaper.jpg);
	background-repeat: no-repeat;
	background-size: cover;
	background-attachment: fixed;
}*/

.bg-gray {
    background-color: rgba(24, 44, 97, .3);
}
.container_flex {
	background-color: gray;
	display: flex;
	flex-direction: row-reverse;
}

.bg-nav {
    background-color: rgb(24, 44, 97) !important;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    z-index: 5;
}

.bg-darkblue {
    background-color: rgb(24, 44, 97) !important;
}

.item {
	margin: 0;
}

.product_img {
	background-size: cover;
}

/*.card {
	width: 30%;
}*/

.row {
	display: flex;
	align-items: stretch;

}

.max-width {
	max-width: 30%;
}

.bg-card {
	background-color: rgba(189, 195, 199, .7);/*
	background-color: rgba(112, 111, 211, .7);*/
	color: #6c757d !important;
}


.bg-card-footer {
	background-color: rgba(236, 240, 241, .5);/*
	background-color: rgba(39, 60, 117, .7);*/
}

.text {
  color: #6c757d !important;
}

a.text:hover,
a.text:focus {
  color: #57606f !important;
}
</style>

<body>

<div id="searchSpecs">
<!-- When this form is submitted, this PHP page is what processes it.
     Search/sort specs are passed to this page through parameters in the URL
     (GET method of passing data to a page). -->
<form method="get" action="browse.php">
  <div class="row">
    <div class="col-md-5 pr-0">
      <div class="form-group">
        <label for="keyword" class="sr-only">Search keyword:</label>
	    <div class="input-group">
          <div class="input-group-prepend">
			</div>

          </div>
          <input type="text" class="form-control border-left-0" name="keyword" id="keyword" placeholder="Search for anything">
        </div>
    </div>
    <div class="col-md-2 pr-0">
      <div class="form-group">
        <label for="cat" class="sr-only">Search within:</label>
        <select class="form-control" id="cat" name="cat" >
		<option selected value="all">All categories</option>
		
<!-- Loop to pull categories from table into the drop down menu -->

		<?php $cat_query = "SELECT name FROM categories ORDER BY name";
		$cat_result = mysqli_query($connection, $cat_query)
			or die('Error making select cat query');
	
		while ($cat_row = mysqli_fetch_array($cat_result)) {
			echo ('<option value='. $cat_row[0]. '>'. $cat_row[0] .'</option>');
		}
		?>
        </select>
      </div>
    </div>
    <div class="col-md-3 pr-0">
      <div class="form-inline">
        <label class="mx-2" for="order_by">Sort by:</label>
        <select class="form-control" id="order_by" name="order_by">
          <option selected value="pricelow">Price (low to high)</option>
          <option value="pricehigh">Price (high to low)</option>
          <option value="date">Soonest expiry</option>
        </select>
      </div>
    </div>
    <div class="col-md-1 px-0">
      <button type="submit" class="btn btn-primary">Search</button>
    </div>
  </div>
</form>
</div> <!-- end search specs bar -->
</div>


<?php
// Can choose the number of results per page you would like
	$results_per_page = 2;
	
// Checks if Keyword exists 
	if (!isset($_GET['keyword']))
	{
		 $query = "SELECT listings.listing_id,listings.image, listings.item_title, listings.itemdescription, MAX(bids.bidprice), listings.startprice, listings.endtime
FROM listings LEFT JOIN bids ON listings.listing_id=bids.listing_id WHERE item_title IS NOT NULL";
	}

	else
	{
		// Sets keyword Variable - prevents injection attack
		$keyword = htmlspecialchars(mysqli_real_escape_string($connection,$_GET['keyword']));
		
		// Checks if Keyword is blank
		if ($keyword == '')
		{
			 // If blank set query to any item title 
			 $query = "SELECT listings.listing_id,listings.image, listings.item_title, listings.itemdescription, MAX(bids.bidprice), listings.startprice, listings.endtime
FROM listings LEFT JOIN bids ON listings.listing_id=bids.listing_id WHERE item_title IS NOT NULL";
		}
		else
		{
			 // Otherwise check if item title is like keyword searched 
			 $query = "SELECT listings.listing_id,listings.image, listings.item_title, listings.itemdescription, MAX(bids.bidprice), listings.startprice, listings.endtime
FROM listings LEFT JOIN bids ON listings.listing_id=bids.listing_id WHERE item_title LIKE '%$keyword%'";
		}
	}
// Checks if category exists 
	if (!isset($_GET['cat']))
	{
		 $query .= " AND category IS NOT NULL";
	}
	
	else
	{
		$category = $_GET['cat'];
		
		if ($category == "all")
		{
			 $query .= " AND category IS NOT NULL";
		}
		else
		{
			// Converts string to catID which is used in DB
			 $catID_query = "SELECT catID FROM categories WHERE name = '$category'";
			 $catID_result = mysqli_query($connection, $catID_query) 
				or die('Error making listing title query');
			 $catID = mysqli_fetch_array($catID_result);
			 $category = $catID[0];
			 $query .= " AND category = '$category'";
		}
	}
// Checks if 'order by' exists 
	if (!isset($_GET['order_by']))
	{
		// At this point we divide our SQL queries into two. $query will be used to count the number of listings for pagination
		// $query_ordered will be used to pull the actual listings in the correct order. $query_ordered is what is outputted to screen later
		
		//Default search is for soonest expiry. Pushes finished auctions to the back by increasing the absolute value of the time difference
		$query_ordered = $query . " GROUP BY listings.listing_id ORDER BY (CASE 
			WHEN (listings.endtime > CURRENT_TIMESTAMP) THEN TIMEDIFF(listings.endtime,CURRENT_TIMESTAMP) 
			ELSE ADDTIME((TIMEDIFF(CURRENT_TIMESTAMP, listings.endtime)),\"10000:0:0\") 
			END) LIMIT $results_per_page";
		

	}
	else
	{
		$order_by = $_GET['order_by'];
		if ($order_by == '')
		{
			
			$query_ordered = $query . " GROUP BY listings.listing_id ORDER BY (CASE 
			WHEN (listings.endtime > CURRENT_TIMESTAMP) THEN TIMEDIFF(listings.endtime,CURRENT_TIMESTAMP) 
			ELSE ADDTIME((TIMEDIFF(CURRENT_TIMESTAMP, listings.endtime)),\"10000:0:0\") 
			END) LIMIT $results_per_page";
		}
				
		if ($order_by == 'date')
		{
			$query_ordered = $query . " GROUP BY listings.listing_id ORDER BY (CASE 
			WHEN (listings.endtime > CURRENT_TIMESTAMP) THEN TIMEDIFF(listings.endtime,CURRENT_TIMESTAMP) 
			ELSE ADDTIME((TIMEDIFF(CURRENT_TIMESTAMP, listings.endtime)),\"10000:0:0\") 
			END) LIMIT $results_per_page";
	
		}
		
		if ($order_by == 'pricelow')
		{
			$query_ordered = $query . " GROUP BY listings.listing_id ORDER BY (CASE
			WHEN MAX(bids.bidprice) IS NULL THEN listings.startprice
			ELSE MAX(bids.bidprice)
			END) LIMIT $results_per_page";
		}
		if ($order_by == 'pricehigh')
		{
			$query_ordered = $query . " GROUP BY listings.listing_id ORDER BY (CASE
			WHEN MAX(bids.bidprice) IS NULL THEN listings.startprice
			ELSE MAX(bids.bidprice)
			END) DESC LIMIT $results_per_page";
		}

	}
	
	// Here we divide $query into an array so we can remove columns and return the COUNT of listings for pagination 
	$tmp = explode(" ",$query);
	$tmp[1] = "COUNT(DISTINCT listings.listing_id)";
	$tmp[2] = "";
	$tmp[3] = "";
	$tmp[4] = "";
	$tmp[5] = "";
	$tmp[6] = "FROM ";

	// $num_query introduced. Turn $tmp array into a string. Like $query but uses SQL 'COUNT'
	
	$num_query = implode(" ",$tmp);
	$num_result = mysqli_query($connection, $num_query)
			or die('Error making count query');
	$row = mysqli_fetch_array($num_result);

	$num_results = $row[0]; 
	
	if ($num_results < 1) {
		$max_page = 1;
	}
	else {
		$max_page = ceil($num_results / $results_per_page);
	}
	if (!isset($_GET['page']))
		{
		$curr_page = 1;
		}
	else
	{
		if ($_GET['page'] == 1) 
		{
			$curr_page = 1;
		}
		else
		{
			//This limits the number of answers per page to $results_per_page, and ensures not the same 'x' results are printed on each page using SQL 'offset'
			$curr_page = $_GET['page'];
			$offset = ($curr_page*$results_per_page)-$results_per_page;
			$query_ordered .= " OFFSET $offset"; 

		}
	}
?>

<div class="container mt-5">

<?php
if ($num_results < 1) {
		echo ("Sorry, your search didn't yield any results! Perhaps try again with a different keyword or category...");
	}
?> 

</div>
</div>


		
<br><br><br>

    <?php
    $query1 = "select * from listings ORDER BY listing_id DESC;";
	$run_q1 = $connection->query($query1);
	$showing_products = $run_q1->num_rows;
    ?>
    <form>
		  <div class="container mt-5 mb-5">
				<?php

				?>
				<div class="row">
				<?php
				
				while ($row_q1 = $run_q1->fetch_object()) {
					
        			$bid_e_time = $row_q1->posttime;
        			$product_id = $row_q1->listing_id;

        			


        			//$nt = new DateTime($bid_e_time);
        			//$bid_e_time = $nt->getTimestamp();

        			//$date = time();

					$pro_id = $row_q1->listing_id;
					$query5 = "select * from bids where listing_id = $pro_id;";
					$run_q5 = $connection->query($query5);
					$total_bids = $run_q5->num_rows;
						?>
							<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">	
								<div class="card mt-3 mb-3">
									<?php  
									$query6 = "select image from listings where listing_id = $pro_id LIMIT 1";
									$run_q6 = $connection->query($query6);
									$row_q6 = $run_q6->fetch_object();
									$image_name = $row_q6->image;
									$image_destination = "uploads/".$image_name;
									?>
									
									<img class="product_img card-img-top" src="<?php echo $image_destination; ?>"  height="200vh" width="100%" alt="Product Image">
									<div class="card-body bg-gray">
										<a class="card-title text-dark" href="listing.php?listing_id=<?php echo $pro_id; ?>"><h5><?php echo $row_q1->item_title; ?></h5></a>
										
										<h4 class="font-weight-light">Birr <?php echo $row_q1->startprice; ?></h4>
										
										

									</div>
									<div class="card-footer bg-card-footer text-muted">
										<?php

										echo $total_bids." ";
										($total_bids >1 ? $printing = "bids on this product" : $printing = "bid on this product");
										echo $printing;
										?>  
									</div>
								</div>
							</div>
						<?php
				}
				?>
				</div>
			</div>
	</form>

	<!-- Pagination for results listings -->
<nav aria-label="Search results pages" class="mt-5">
  <ul class="pagination justify-content-center">
  <script type="text/javascript"
            src="//code.jquery.com/jquery-1.9.1.js">
  </script>
    <link rel="stylesheet" 
          type="text/css" 
          href="/css/result-light.css">
    
    <script type="text/javascript" 
            src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js">
  </script>
    <link rel="stylesheet" 
          type="text/css" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <link rel="stylesheet"
          type="text/css" 
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- JavaScript for adding 
     slider for multiple images
     shown at once-->
    <script type="text/javascript">
        $(window).load(function() {
            $(".carousel .item").each(function() {
                var i = $(this).next();
                i.length || (i = $(this).siblings(":first")),
                  i.children(":first-child").clone().appendTo($(this));
                
                for (var n = 0; n < 4; n++)(i = i.next()).length ||
                  (i = $(this).siblings(":first")),
                  i.children(":first-child").clone().appendTo($(this))
            })
        });
    </script>
  
</head>
  
<body>
    <!-- container class for bootstrap card-->
    <div class="container">
        <!-- bootstrap card with row name myCarousel as row 1-->
        <div class="carousel slide" id="myCarousel">
            <div class="carousel-inner">
                <div class="item active">
                    <div class="col-xs-2">
                        <a href="#">
                          <img src="uploads/photo_2022-07-07_21-11-39.jpg" 
                               class="img-responsive">
                      </a>
                    </div>
                </div>
                <div class="item">
                    <div class="col-xs-2">
                        <a href="#">
                          <img src="uploads/download (2).jpg" 
                               class="img-responsive">
                      </a>
                    </div>
                </div>

                <div class="item">
                    <div class="col-xs-2">
					<a href="uploads/download (3).jpg" class="image-popup">
                          <img src="uploads/download (3).jpg" 
                               class="img-responsive">
                      </a>
                    </div>
                </div>
            </div>
			
			 <a class="left carousel-control"
                      href="#myCarousel"
                      data-slide="prev">
          <i class="glyphicon glyphicon-chevron-left">
          </i>
          </a>
            <a class="right carousel-control" 
               href="#myCarousel" 
               data-slide="next">
              <i class="glyphicon glyphicon-chevron-right">
              </i>
          </a>
  
        </div>
    </div>
<?php

  // Copy any currently-set GET variables to the URL.
  $querystring = "";
  foreach ($_GET as $key => $value) {
    if ($key != "page") {
      $querystring .= "$key=$value&amp;";
    }
  }

  $high_page_boost = max(3 - $curr_page, 0);
  $low_page_boost = max(2 - ($max_page - $curr_page), 0);
  $low_page = max(1, $curr_page - 2 - $low_page_boost);
  $high_page = min($max_page, $curr_page + 2 + $high_page_boost);

  if ($curr_page != 1) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page - 1) . '" aria-label="Previous">
        <span aria-hidden="true"><i class="fa fa-arrow-left"></i></span>
        <span class="sr-only">Previous</span>
      </a>
    </li>');
  }

  for ($i = $low_page; $i <= $high_page; $i++) {
    if ($i == $curr_page) {
      // Highlight the link
      echo('
    <li class="page-item active">');
    }
    else {
      // Non-highlighted link
      echo('
    <li class="page-item">');
    }

    // Do this in any case
    echo('
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . $i . '">' . $i . '</a>
    </li>');
  }

  if ($curr_page != $max_page) {
    echo('
    <li class="page-item">
      <a class="page-link" href="browse.php?' . $querystring . 'page=' . ($curr_page + 1) . '" aria-label="Next">
        <span aria-hidden="true"><i class="fa fa-arrow-right"></i></span>
        <span class="sr-only">Next</span>
      </a>
    </li>');
  }
?>

  </ul>
</nav>

</body>
</html>
<?php include_once"footer.php"?>