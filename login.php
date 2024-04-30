<?php


// checks for an existing session ands starts one if there isn't a current session
if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}

// checks if the session variable is set
if (isset($_SESSION["user"])) {
  // the code in the if logic is seen only by those with a session (Note: all HTML code may need to be echoed to ensure this works)
}

// set the default message variable
$message = "";

// check if the user has clicked the login button
if (isset($_POST["login"])) {

    // connect to the database
    require_once("db.php");

    // set the username variable from the form and hash the password before comparing against the password in the database
    $email = $_POST["email"];
  $password = hash("sha512", $_POST["password"]);
  
  // prepare the query to find users with the entered username and password
  $query1 = "
Select cr.*, c.customer_id
From Credential cr
inner join customer c using(credential_id)
where email = ? and password = ?";
    $stmt1 = $conn->prepare($query1);
    $stmt1->execute([$email, $password]);
    $query2 = "
Select cr.*, e.employee_id
From Credential cr
inner join employee e using(credential_id)
where email = ? and password = ?";
    $stmt2 = $conn->prepare($query2);
    // execute the query and store the results
    $stmt2->execute([$email, $password]);
  
  
    // check to see if a match is found for a specific username/password combination
    if($stmt1->rowCount() > 0) {

        // destroy any existing sessions and starts a new session
        session_destroy();
        session_start();

        // set the username session variable, see the session tab to see how it is used!!!
        $_SESSION["customer"] = "Customer";

		$message = "<div class='alert alert-success'>You are logged in!</div>";

    // set the error message if there is no matching username/password combination
    } else if ($stmt2->rowCount() > 0){
	// destroy any existing sessions and starts a new session
        session_destroy();
        session_start();

        // set the username session variable, see the session tab to see how it is used!!!
        $_SESSION["employee"] = "Employee";

		$message = "<div class='alert alert-success'>You are logged in as an employee!</div>";
	} else {
        $message = "<div class='alert alert-danger'>Your login credentials are incorrect!</div>";
    }
}

$conn = null;
?>


<!DOCTYPE html>
<html lang="en">
   <head>
	 <title>Login Page</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
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
            <!-- header================================================== -->
            <header class="s-header">
               <div class="header-logo">
                  <a class="navbar-brand logo-biss" href="home.php"> <img src="assets/images/Allcity.png" width="500" height="300"></a>
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
                        <h5 class="wow fadeInUp main-h font_30" data-wow-delay="0.2s" >WELCOME TO All City Office
                           <br><span class="about_text "> Login Page  </span> 
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
	 
	 
	 
	 
	 
	 
	 
	 <div>
	   <form method="post" class="mb-3"> 
		 <div class="form-group">
		  <label>Email:</label>
		  <input required autofocus class="form-control form-control-sm" type="text" name="email" placeholder="Enter an email..."> 
		</div>
		 <div class="form-group">
		  <label>Password:</label>
		  <input required autofocus class="form-control form-control-sm" type="text" name="password" placeholder="Enter a password..."> 
		</div>
		<div>
		  <button class="btn btn-primary" name="login">
			Submit!
		  </button>
		</div>
	  </form>
	   <?php echo $message; ?>
	   	   <?php if (isset($_SESSION["user"])) {
  echo $_SESSION["user"];
} ?>
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