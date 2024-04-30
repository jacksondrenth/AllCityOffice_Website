<?php
require_once('db.php');

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

$results = $message = $emailScript = "";

$states = array(
  'AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
  'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD',
  'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ',
  'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC',
  'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'
);


// Checks if the form has been submitted
if (isset($_POST["submit"])) {
  $customerName = $_POST["customer_name"];
  $street = $_POST["street"];
  $city = $_POST["city"];
  $state = $_POST["state"];
  $zipCode = $_POST["zip_code"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  //Checking if a customer with the same name and zipcode exist
  $query = "SELECT * 
  FROM credential 
  WHERE email = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$email]);


  if($stmt->rowCount() == 0) {
	$query = 
	  "INSERT INTO credential (email,password)
	 VALUES (?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->execute([$_POST["email"], $_POST["password"] ]);


	$CredID = $conn->lastInsertId();
	$query = 
	  "INSERT INTO customer (customer_name, street, city, state, zip_code, credential_id)
	  VALUES (?, ?, ?, ?, ?, ?)";

	$stmt = $conn->prepare($query);
	$stmt->execute([$customerName, $street, $city, $state, $zipCode, $CredID]);

	$message = "<div class='alert alert-success'>Successfully Registered <b>{$_POST["customer_name"]}</b>.</div>";

	$email_message =
	  "<div><p>To new customer:</p><p>Thanks for registering for the website. I hope you find everything that you are looking for. If you have any questions, feel free to email us at <a href=\"mailto:TonyDrenth@AllCityOffice.com\">TonyDrenth@AllCityOffice.com</a>.</p><p>Sincerely,<br>All City Office</p></div>";

	// create the email script
	// vllqxmnhpqzfuwap <--- password
	$emailScript =
	  "<script>
        var data = new FormData($('form')[0]);
        data.append('fname', 'Jackson');
        data.append('lname', 'Drenth');
        data.append('from', 'jacksonnashdrenth@gmail.com');
        data.append('password', 'vllqxmnhpqzfuwap');
        data.append('to', " . json_encode($email) . ");
	    data.append('subject', 'Registration');
        data.append('message', '$email_message');
        data.append('external', true);
		
		// call the email php page using an AJAX request and the form data as part of the POST request
        $.ajax({
            url: '/features/jquery-ajax/email.php',
            method: 'POST',
            data: data,
            cache: false,
            contentType: false,
            processData: false,

            // return what PHP echoes in the email message div when a message is sent successfully
            success: function (output) {
                $('#email-message').html(output);
            },

            // capture errors with the AJAX request
            error: function (xhr, textStatus, errorThrown) {
                displayAJAXError(xhr, textStatus, errorThrown);
            }
        })
    </script>";


  } else {
	$message = "<div class='alert alert-danger'>The email <b>{$email}</b> is already registered.</div>";
  }

}

// Close the connection
$conn = null;
?>


<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> Registration Page  </title>
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
	<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  </head>
  <body id="top">
	<div class="about-bg-banner-img">
	  <div class="overlay-all ">
		<!-- Header_Area -->
		<!-- header
   ================================================== -->
		<header class="s-header">
		  <div class="header-logo">
			<a class="navbar-brand logo-biss" href="home.php"> <img src="assets/images/Allcity.png"width="500" height="300"></a>
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
				<h5 class="wow fadeInUp main-h font_30" data-wow-delay="0.2s" >All City Office: New Customer Sign Up
				  <br><span class="about_text "> Register Now!  </span> 
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






	<div class="jumbotron text-center">
	  <h1>Registration For New Users!</h1>
	</div>
	<div>
	  <form method="post" class="mb-3">
		<div class="form-group">
		  <label>Company Name:</label>
		  <input class="form-control" type="text" name="customer_name" placeholder="Enter your Company Name...">
		</div>



		<div class="form-group">
		  <label>Street Address:</label>
		  <input class="form-control" type="text" name="street" placeholder="Enter Street Address...">
		</div>
		<div class="form-group">
		  <label>City:</label>
		  <input class="form-control" type="text" name="city" placeholder="Enter City Name...">
		</div>

		<div class="form-group">
		  <label>State:</label>
		  <select class="form-control" name="state">
			<option value="" disabled selected>Select a state...</option>
			<?php foreach ($states as $stateCode) { ?>
			<option value="<?php echo $stateCode; ?>"><?php echo $stateCode; ?></option>
			<?php } ?>
		  </select>
		</div>

		<div class="form-group">
		  <label>Zip Code:</label>
		  <input class="form-control" type="text" name="zip_code" placeholder="Enter Your Zip Code...">
		</div>

		<div class="form-group">
		  <label>Email:</label>
		  <input class="form-control" type="text" name="email" placeholder="Enter your Email...">
		</div>

		<div class="form-group">
		  <label>Password:</label>
		  <input class="form-control" type="text" name="password" placeholder="Enter your Password...">
		</div>


		<button class="btn btn-primary" name="submit">Submit</button>
		<?php echo $message; ?>
		<div id="email-message"></div>
	  </form>
	</div>











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
	<?php echo $emailScript; ?>
  </body>
</html>