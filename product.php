<?php

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}
// declare default variables
$option = "";

// connect to the database
require_once("db.php");



?>


<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Product Page  </title>
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
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i,800,800i&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">


	<!--   for the ajax  -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.css">
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="ajax.js"></script>


  </head>
  <body id="top">
	<div class="about-bg-banner-img">
	  <div class="overlay-all ">
		<!-- Header_Area -->
		<!-- header
	  ================================================== -->
		<header class="s-header">
		  <div class="header-logo">
			<a class="navbar-brand logo-biss" href="home.php"> <img src="assets/images/Allcity.png" width="500" height="300"></a>
		  </div>
		  <!-- end header-logo -->
		  <nav class="header-nav">
			<a href="#0" class="header-nav__close" title="close"><span>Close</span></a>
			<div class="header-nav__content">
			  <h3>intechnic </h3>
			  <ul class="header-nav__list">
				<li class="current"><a class=""  href="home.php" >Home</a></li>
				<li><a class=""  href="about.php" >About</a></li>
				<?php 
				if (isset($_SESSION['customer'])) {
				  $line = '<li><a class="" href="logout.php" >Log Out</a></li>';
				  echo $line;
				  $line = '<li><a class="" href="product.php" >Products</a></li>';
				  echo $line;
				} else if (isset($_SESSION['employee'])) {
				  $line = '<li><a class=""  href="ordermanagement.php" >Order Management</a></li>';
				  echo $line;
				  $line = '<li><a class="" href="product.php" >Products</a></li>';
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
				<br>
				<h5 class="wow fadeInUp main-h font_30" data-wow-delay="0.2s" >WELCOME TO All City Office
				  <br><span class="about_text "> ABOUT US?</span>  
				</h5>

			  </div>
			  <!-- /#banner-text End -->
			</div>
		  </div>
		</section>
		<div class="container-fluid p0 banner-shap-img">
		</div>
	  </div>
	</div>
	<!-- /#banner end -->
	<!--#Our banner-shap- Area -->
	<!--#EndOur banner-shap- Area -->
	<!-- #About Us Area start -->



<?php 
	require_once("db.php");
	

	
if (isset($_SESSION["employee"]) || isset($_SESSION["customer"])) {
  
    	// get the drop-down options
$query =
  "SELECT part_number, description
FROM product
ORDER BY description";
//   execute 
  $stmt = $conn->prepare($query);
$stmt->execute();
  
  // populate the options into the datalist variable
foreach ($stmt as $row) {
  $option .= "<option name='{$row["part_number"]}'>{$row["description"]}</option>";
  
  $stuff='
	<h3>Select a product to see more:</h3>
	<select id="productID" name="productID" required class="form-control form-controm-sm"><option value="" disabled selected>Select a product...</option>' . $option . '</select>
	<div id="ajax-content"></div>

	<br>
	<br>';

}
    echo $stuff;
} else {
 $message = "<div class='alert alert-danger'><h1>This is a logged in customer site, please log in with your customer credentials.</h1></div>";
  echo $message;
}
	
?>



	<!-- #About Us Area End -->













	<!--#start Our footer Area -->
	<div class="our_footer_area">
	  <div class="book_now_aera">
		<div class="container wow fadeInUp">
		  <div class="row book_now">
			<div class="col-md-4">
			  <!-- Logo -->
			  <div>
				<a class="logo-biss" href="home.php">
				  <img src="assets/images/logo-5.png
							">
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