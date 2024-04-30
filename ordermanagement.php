<?php

// checks for an existing session ands starts one if there isn't a current session
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}
// set the default message variable
$order_management = $results = $customers = "";

//QUESTION: ARCANEGATE showing multiple customer ids, more on the data insert than anything, how to address?


$conn = null;
?>


<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Order Management </title>
	<!-- Favicon -->
	<link rel="icon" href="assets/images/favicon.png" type="image/x-icon" />
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<!-- Animate CSS -->
	<link href="assets/vendors/animate/animate.css" rel="stylesheet">
	<!-- Icon CSS-->
	<link rel="stylesheet" href="assets/vendors/font-awesome/css/font-awesome.min.css">
	<!-- Camera Slider -->
	<link rel="stylesheet" href="assets/vendors/camera-slider/camera.css">
	<!-- Owlcarousel CSS-->
	<link rel="stylesheet" type="text/css" href="assets/vendors/owl_carousel/owl.carousel.css" media="all">
	<!--Template Styles CSS-->
	<link rel="stylesheet" type="text/css" href="assets/css/style.css" media="all" />
	<!-- other link from ormond -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i,800,800i&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
  </head>
  <body id="top">
	<div class="about-bg-banner-img">
	  <div class="overlay-all ">
		<!-- Header_Area -->
		<!-- header
			   ================================================== -->
		<header class="s-header">
		  <div class="header-logo">
			<a class="navbar-brand logo-biss" href="home.php"> <img src="assets/images/AllCity.png" width="500" height="300"></a>
		  </div>
		  <!-- end header-logo -->
		  <nav class="header-nav">
			<a href="#0" class="header-nav__close" title="close"><span>Close</span></a>
			<div class="header-nav__content">
			  <h3>All City Office </h3>
			  <ul class="header-nav__list">
				<li class="current"><a class=""  href="home.php" >Home</a></li>
				<li><a class=""  href="about.php" >About</a></li>
				<?php 
				if (isset($_SESSION['customer'])) {
				  $line = '<li><a class="" href="logout.php" >Log Out</a></li>';
				  echo $line;
				} else if (isset($_SESSION['employee'])) {
				  $line = '<li><a class=""  href="ordermanagement.php" >Order Management</a></li>';
				  echo $line;
				  $line = '<li><a class="" href="logout.php" >Log Out</a></li>';
				  echo $line;
				} else {
				  $line = '<li><a class=""  href="registration.php">Registration</a></li>';
				  echo $line;
				  $line = '<li><a class=""  href="login.php" >Login</a></li>';
				  echo $line;
				}
				?>
				
			  </ul>
			  <ul class="header-nav__social">
				<li>
				  <a href="" target="_blank"><i  class="fa fa-facebook-square fa-3x social"></i></a>
				</li>
				<li>
				  <a href="" target="_blank"><i  class="fa fa-twitter-square fa-3x social"></i></a>
				</li>
				<li>
				  <a href="" target="_blank"><i  class="fa fa-instagram fa-3x social"></i></a>
				</li>
				<li>
				  <a href="#0"><i class="fa fa-facebook-square fa-3x social"></i></a>
				</li>

			  </ul>
			</div>
			<!-- end header-nav__content -->
		  </nav>
		  <!-- end header-nav -->
		  <a class="header-menu-toggle" href="#0">
			<span class="header-menu-icon"></span>
		  </a>
		</header>
		<!-- end s-header -->
		<!-- End Header_Area -->
		<!-- #banner start -->
		<section id="banner" class="py_120">
		  <div class="container ">
			<div class="row">
			  <!-- #banner-text start -->            
			  <div id="banner-text" class="col-md-7 text-c text-left ">
				<h5 class="wow fadeInUp main-h font_30" data-wow-delay="0.2s" >All City Office: Employees
				  <br><span class="about_text "> Order Management  </span> 
				</h5>

			  </div>
			  <!-- /#banner-text End -->
			</div>
		  </div>
		</section>
		<!--#Our banner-shap- Area -->
		<div class="container-fluid p0 banner-shap-img">
		</div>
		<!--#EndOur banner-shap- Area -->
	  </div>
	</div>
	<!-- /#banner end -->

	
	
	
	
	
	
	
<?php 
	require_once("db.php");
if (isset($_SESSION["employee"])) {

  // prepare the query to find users with the entered username and password
  $query = "
SELECT DISTINCT customer_name
FROM 354groupb1.customer 
GROUP BY customer_name
ORDER BY customer_name;";
  $stmt = $conn->prepare($query);

  // execute the query 
  $stmt->execute();

  // populate dropdown
  foreach ($stmt as $row) {
	$customers .= "<option>{$row["customer_name"]}</option>";
  }
  $order_management = '<div>
	  <div class="container">
		<form method="post" class="mb-3"> 
		  <label>Customer:</label>
		  <div class="form-group">
			<select class="form-control" name="customer-id">
			  <option value="" disabled selected>Select a customer...</option>
			  ' . $customers . '
			</select>
		  </div>
		  <div>
			<button class="btn btn-primary" name="submit">
			  Submit!
			</button>
		  </div>
		</form>
		'. $results . '
	  </div>';
  	echo $order_management;
} else {
 $message = "<div class='alert alert-danger'><h1>This is an employee only site, please log in with your employee credentials.</h1></div>";
  echo $message;
}
	
	
	  // check to see if the form has been submitted
  if (isset($_POST["submit"])) { 

	// queries customer orders
	$query = "
SELECT c.customer_id, customer_name, amount_owed, amount_paid, tracking_number, date_ordered, o.description, date_processed, employee_id
FROM customer c
INNER JOIN `order` o ON c.customer_id = o.customer_id
INNER JOIN bill b ON c.customer_id = b.customer_id
WHERE c.customer_name = ?
GROUP BY tracking_number
ORDER BY c.customer_id";
	$stmt = $conn->prepare($query);
	$stmt->execute([$_POST["customer-id"] ]);

	//loops through each row of the query
	foreach ($stmt as $row) {  

	  //stores each row in result variables as well as monetary formatting
	  $results .=
		"<tr>
	 <td>{$row["customer_id"]}</td>
	 <td>{$row["customer_name"]}</td>
	 <td>$" . number_format($row["amount_owed"], 2) . "</td>
	 	 <td>$" . number_format($row["amount_paid"], 2) . "</td>
	<td>{$row["tracking_number"]}</td>
	<td>{$row["date_ordered"]}</td>
	<td>{$row["description"]}</td>
	<td>{$row["date_processed"]}</td>
	<td>{$row["employee_id"]}</td>
	</tr>";
	}	 
	$results =
	  "<h3>Customer order information for '{$_POST["customer-id"]}':</h3>
   <table class ='table table-bordered table-striped'>
   <tr>
   <th>Customer ID</th>
   <th>Customer Name</th>
   <th>Amount Owed</th>
   <th>Amount Paid</th>
   <th>Tracking Number</th>
   <th>Date Ordered</th>
   <th>Order Description</th>
   <th>Date Processed</th>
   <th>Employee ID</th>
   </tr>
   {$results}
   </table>";
	echo $results;
  }
	$conn = null;
	?>
	
	
	










	  <!--#start Our footer Area -->
	   <div class="our_footer_area">
    <div class="book_now_aera">
        <div class="container wow fadeInUp">
            <div class="row book_now">
                <div class="col-md-4">
                    <!-- Logo -->
                    <div>
                        <a class="logo-biss" href="home.php">
                            <img src="assets/images/logo-5.png">
                        </a>
                    </div>
                    <!-- Cage Code and Duns -->
                    <div>
                        <p class="footer-h"><a href="">Cage Code: 842E8</a></p>
                        <p class="footer-h">Duns: 116557276</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Factory and Mailing Address -->
                    <div>
                        <ul class="location">
                            <li class="footer-left-h">
                                <i class="fa fa-map-marker"></i> Factory<br>6345 Industry Way, Suite B. Westminster,<br>CA 92649, USA
                            </li>
                            <li class="footer-left-h">
                                <i class="fa fa-map-marker"></i> Mailing<br>16458 Bolsa Chica Street #533, Huntington Beach,<br>CA 92649, USA
                            </li>
                            <li class="footer-left-h">
                                <i class="fa fa-phone"></i> Call Us <br>714-715-0023
                            </li>
                            <li class="footer-left-h">
                                <i class="fa fa-envelope-o"></i> Email<br>
                                <a href="">Liz@allcityoffice.com</a><br>
                                <a href="">Tony@allcityoffice.com</a>
                            </li>
                        </ul>
                    </div>
                    <!-- Design Credits -->
                    <div>
                        <p class="color-gray">Designed by <a href="https://www.navthemes.com">NavThemes</a> & distributed by <a href="https://themewagon.com/">ThemeWagon</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!--#End Our footer Area -->
  <!-- The following is only needed when the video is in the html
		 otherwise the who .hero__overlay html can be removed -->
  <div class="hero__overlay">
	<div class="hero__modal">
	  <a class="hero__close" href="#">Close</a>
	  <iframe allowscriptaccess="always" id="hero-video" class="hero__player" src="https://www.youtube.com/embed/1NSA8ycGfKg?enablejsapi=1&html5=1" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
	</div>
	<!-- /.hero__modal -->    
  </div>
  <!-- /.hero__overlay -->
  <!-- jQuery JS -->
  <script src="assets/js/jquery-1.12.0.min.js"></script>
  <script src="assets/vendors/popup/lightbox.min.js"></script>
  <script type="text/javascript">
	$(document).ready(function() {
	  $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
		e.preventDefault();
		$(this).siblings('a.active').removeClass("active");
		$(this).addClass("active");
		var index = $(this).index();
		$("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
		$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
	  });
	});
  </script> 
  <script type="text/javascript">
	$(document).ready(function(){
	  $(".currency_year").hide();
	  $("#radio1").click(function(){
		$(".currency_year").hide();
		$(".currency_month").show();
	  });
	  $("#radio2").click(function(){
		$(".currency_month").hide();
		$(".currency_year").show();
	  });
	});

	$('.tabs_label').click(function(){
	  $('.tabs_label').removeClass('active_t');
	  $(this).addClass('active_t');

	})

  </script>
  <script type="text/javascript">
	$(document).ready(function () {
	  $('#sidebarCollapse').on('click', function () {
		$('#sidebar').toggleClass('active');
		$(this).toggleClass('active');
	  });
	});
  </script>
  <!-- Bootstrap JS -->
  <script src="assets/js/bootstrap.min.js"></script>
  <!-- Animate JS -->
  <script src="assets/vendors/animate/wow.min.js"></script>
  <script src="assets/vendors/sidebar/main.js"></script>
  <!-- Owlcarousel JS -->
  <script src="assets/vendors/owl_carousel/owl.carousel.min.js"></script>
  <!-- Stellar JS-->
  <!-- Theme JS -->
  <script src="assets/js/theme.min.js"></script>
  </body>
</html>