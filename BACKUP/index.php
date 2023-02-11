
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include_once 'common_bootstrap.php';
$title = 'Home';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>eNotice Ninja</title>
        <link rel="stylesheet" href="font/stylesheet.css">
        <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css">
        <link rel="stylesheet" href="assets/custom.css">
        <link rel="stylesheet" href="assets/responsive.css">
        <link rel="icon" href="images/favicon.png">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-35MF5LYQ8N"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-35MF5LYQ8N');
        </script>
    </head>
	<style>
	.footer-small-links a {
		color: white;
		margin-left: 20px;
	}

	.g-recaptcha {
		display: inline-block;
	}
</style>
    <body>
        <!-- navigation -->
        <div class="navigation">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container">
                    <a href="" class="navbar-brand desk"><img src="images/logo.png" class="logo" alt="logo"></a>
                    <a href="" class="navbar-brand tab"><img src="images/mob-logo.png" class="logo" alt="logo"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <ul class="navbar-nav">
                        <li class="nav-item">
                          <a class="nav-link" href="#about">ABOUT</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#feature">FEATURES</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#solution">SOLUTION</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#plan">PLAN</a>
                        </li>
                   <!--     <li class="nav-item">
                            <a class="nav-link" href="http://nanostuffs.com/enotice/login.php">SUBSCRIBE FOR NOTIFICATION</a>
                        </li>-->
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">CONTACT</a>
                        </li>
                      </ul>
                    </div>
                    <div id="mySidenav" class="sidenav">
                        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                        <a href="#about">ABOUT</a>
                        <a href="#feature">FEATURES</a>
                        <a href="#solution">SOLUTION</a>
                        <a href="#plan">PLAN</a>
                       <!-- <a href="http://nanostuffs.com/enotice/login.php">SUBSCRIBE FOR NOTIFICATION</a>-->
                        <a href="#contact">CONTACT</a>
                      </div>

                    <span onclick="openNav()"><img src="images/mob-icon.png" class="menu-icon tab" alt="icon"></span>
                </div>
            </nav>
        </div>
        <!-- banner -->
        <div class="banner">
            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                      <div class="row">
                          <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <img class="w-100 d-none d-sm-block" src="images/banner.png" alt="First slide">
                            <img class="w-100 d-block d-sm-none" src="images/mob-banner.jpg" alt="First slide">
                          </div>
                          <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="banner-caption d-none d-sm-block">
                                <h1>A revolutionary<br>way to track,<br>search and save<br>notices</h1>
                                <div class="mobile-app">
                                    <img src="images/app-icon.svg" class="app-icon" alt="mobile app">
                                    <p><span class="bold">Never miss a notice.</span><br>Get notifications for your<br>property.</p>
                                </div>
                                <a href="#download"><button class="get-the-app">Get the App</button></a>
                            </div>
                            <div class="banner-caption d-block d-sm-none">
                                <h1>A revolutionary way to track, search and save notices</h1>
                                <div class="mobile-app">
                                    <img src="images/app-icon.svg" class="app-icon" alt="mobile app">
                                    <p><span class="bold">Never miss a notice.</span><br>Get notifications for your<br>property.</p>
                                </div>
                                <a href="#download"><button class="get-the-app">Get the App</button></a>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- about -->
        <div id="about" class="about">
            <div class="container">
                <div class="about-caption">
                    <h2>Property Notices In Leading Newspapers, On One App, It’s That Simple</h2>
                    <div class="about-1">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="about-caption-1">
                                    <h3>1000<span class="plus-sign">&plus;</span></h3>
                                    <h4>Daily Notices</h4>
                                    <p>Get property notices on one platform.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="about-caption-1">
                                    <h3>400<span class="plus-sign">&plus;</span></h3>
                                    <h4>News Paper Editions</h4>
                                    <p>Data from all leading newspapers, so that you never miss a notice.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="about-caption-1 last-brder">
                                    <h3>34 <span class="plus-sign">&plus;</span></h3>
                                    <h4>Districts</h4>
                                    <p>Get property notices from all 35 districts of Maharashtra. <br> More states will be added soon.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- feature -->
        <div id="feature" class="feature">
            <div class="feature-1 d-none d-sm-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="feature-caption">
                                <h5>WHY eNotice Ninja pluss?</h5>
                                <h2>Simple. Fast. Reliable.</h2>
                            </div>
                            <div class="feature-caption-1">
                                <h5>Save Time, Money and Effort.</h5>
                                <p>No need to buy and stock multiple newspapers when searching for notices or saving them.</p>
                                <h5>Easily find your property notice.</h5>
                                <p>Global and parameterized search options for finding property notices in significantly less time
                                    and money.</p>
                                <h5>Never lose a property notice.</h5>
                                <p>All historical and current data for all property notices is securely stored on the cloud, easily accessible whenever needed.</p>
                                <h5>Get timely alert.</h5>
                                <p>Prevent fraud on your property by getting timely alerts.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <img src="images/feature-image.jpg" class="img-feature" alt="feature image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-1 d-block d-sm-none">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="feature-caption">
                                <h5>WHY eNotice Ninja ?</h5>
                                <h2>Simple. Fast. Reliable.</h2>
                                <img src="images/feature-image.jpg" class="img-feature" alt="feature image">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="feature-caption-1">
                                <h5>Save Time, Money, and Effort.</h5>
                                <p>No need to buy and stock multiple newspapers when searching for notices or saving them.</p>
                                <h5>Never lose a property notice.</h5>
                                <p>All historical and current data for all property notices is securely stored on the cloud, easily accessible whenever needed.</p>
                                <h5>Easily find your property notice.</h5>
                                <p>Global and parameterized search options for finding property notices in significantly less time
                                    and money.</p>
                                <h5>Get timely alert.</h5>
                                <p>Prevent fraud on your property by getting timely alerts.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- solution -->
        <div id="solution" class="solution">
            <div class="container">
                <h3>OUR SOLUTION</h3>
                <p>We have digitized the process of searching property notices & storing them when needed. This centralization of information makes cumbersome process very simple. No need to buy multiple newspapers to search for notices. With eNoticeNinja App, you can find all these notices in one go! Access these notices while buying new properties or monitor existing properties.</p>
                <div class="solution-details">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="solution-caption repository">
                                <img src="images/repository.png" class="img-solution" alt="solution image">
                                <h5>Online Repository</h5>
                                <p>Digitalizing the data will make all the notices, which are presently only published in the newspapers,
                                    readily available to the right people via this app.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="solution-caption visibility">
                                <img src="images/visibility.png" class="img-solution" alt="solution image">
                                <h5>Full Visibility at Low <br>Cost</h5>
                                <p>Buyers will have complete visibility of their properties. The cost of getting information will reduce drastically.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="solution-caption">
                                <img src="images/target.png" class="img-solution" alt="solution image">
                                <h5>Properties Safeguarded</h5>
                                <p>Getting information will be much easier and quicker. Moreover, the information on properties will be stored for a lifetime. Thus, historical data will be readily accessible.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- services -->
        <div id="services" class="services">
            <div class="container">
                <!-- <h5>YOU’LL FIND US HERE.</h5> -->
                <h4>Services available in multiple districts</h4>
                <div class="current-location">
                <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="services-details">
                                <h3>Current Cities:</h3>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/mumbai.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/mumbai-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Mumbai</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/subburban.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/subburban-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Mumbai Suburbs</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/thane.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/thane-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Thane</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/palghar.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/palghar-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Palghar</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="services-details">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/raigad.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/raigad-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Raigad</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/pune.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/pune-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Pune</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/pcmc.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/pcmc-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>PCMC</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/satara.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/satara-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Satara</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/sangali.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/sangali-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Sangli</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/kolhapur.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/kolhapur-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Kolhapur</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/solapur.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/solapur-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Solapur</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/aurangabad.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/aurangabad-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Aurangabad</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="services-details services-details-2">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/ahamednagar.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/ahamednagar-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Ahmednagar </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/nashik.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/nashik-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Nashik</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-6">
                                        <div class="locations">
                                            <img src="images/nagpur.png" class="img-service d-none d-sm-block" alt="services image">
                                            <img src="images/nagpur-1.png" class="img-service d-block d-sm-none" alt="services image">
                                            <p>Nagpur</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="services-details">
                                <h3>Current locations:</h3>
                                <ul>
                                    <li>Mumbai</li>
                                    <li>Mumbai Suburbs</li>
                                    <li>Thane</li>
                                    <li>Palghar</li>
                                    <li>Raigad</li>
                                    <li>Pune</li>
                                    <li>PCMC</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="services-details">
                                <h3>Upcoming locations:</h3>
                                <ul>
                                    <li>Satara</li>
                                    <li>Sangali</li>
                                    <li>Kolhapur</li>
                                    <li>Solapur</li>
                                    <li>Aurangabad</li>
                                    <li>Ahmednagar</li>
                                    <li>Nashik</li>
                                    <li>Nagpur</li>
                                </ul>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- plan -->
        <div id="plan" class="plan">
            <div class="container">
                <h5>PRICING</h5>
                <h4>Find the Right Plan for Yourself.</h4>
                <!-- search -->
                <div class="plan-details">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="search-plus">
                                <div class="search-image">
                                    <img src="images/search.svg" class="img-search" alt="search">
                                </div>
                                <div class="search-caption">
                                    <h4>Search Plus</h4>
                                    <hr>
                                    <h6>Save time and gain an<br>advantage with a quick search.</h6>
                                </div>
                            </div>
                            <div class="search-plus-1">
                                <div class="arrow-image">
                                    <img src="images/bullet.png" class="img-arrow" alt="arrow">
                                </div>
                                <div class="arrow-caption">
                                    <p>With one click, view all daily public property notices published in leading newspapers region in one go.</p>
                                </div>
                            </div>
                            <div class="search-plus-1">
                                <div class="arrow-image">
                                    <img src="images/bullet.png" class="img-arrow" alt="arrow">
                                </div>
                                <div class="arrow-caption">
                                    <p>With a convenient pulldown menu, view previous notices on properties of your interest.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="quarterly-plan">
                                <h3><span class="rupee">&#8377;.</span> 549</h3>
			    <!--    <h6>Quarterly • Per Property</h6>  -->
				    <h6> • Quarterly • </h6>
                                <p>Daily View + Unlimited<br>Property Search</p>
                            </div>
                            <div class="subscribe">
                                <!--<a href="http://nanostuffs.com/enotice/login.php" target="_blank">
								<a href="#download"><button class="btn-subscribe">SUBSCRIBE NOW</button></a>-->
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-12">
                            <div class="quarterly-plan">
                                <h3><span class="rupee">&#8377;.</span> 1599</h3>
                                <h6>• Yearly • </h6>
                                <p>Daily View + Unlimited<br>Property Search</p>
                            </div>
                            <div class="subscribe">
                                <!--<a href="http://nanostuffs.com/enotice/login.php" target="_blank">
								<a href="#download"><button class="btn-subscribe">SUBSCRIBE NOW</button></a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="plan-details-1">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="search-plus">
                                <div class="search-image">
                                    <img src="images/alert.svg" class="img-search" alt="search">
                                </div>
                                <div class="search-caption">
                                    <h4>Alerts</h4>
                                    <hr>
                                    <h6>Prevent frauds and stay<br>secured.</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="search-plus-1">
                                <div class="arrow-image">
                                    <img src="images/bullet.png" class="img-arrow" alt="arrow">
                                </div>
                                <div class="arrow-caption">
                                    <p>With ease, secure your properties with alerts on phone and email for any future notices.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- alert -->
                    <div class="plan-details-2">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="alert-plan">
                                    <div class="quarterly-plan">
                                        <h3><span class="rupee">&#8377;.</span> 549</h3>
                                        <h6>Yearly • Per Property</h6>
                                        <p>Set Alerts for up to 10<br>Properties</p>
                                    </div>
                                    <div class="subscribe">
                                        <!--<a href="http://nanostuffs.com/enotice/login.php" target="_blank">
								<a href="#download"><button class="btn-subscribe">SUBSCRIBE NOW</button></a>-->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="alert-plan">
                                    <div class="quarterly-plan">
                                        <h3><span class="rupee">&#8377;.</span> 499</h3>
                                        <h6>Yearly • Per Property</h6>
                                        <p>Set Alerts for 10+<br>Properties</p>
                                    </div>
                                    <div class="subscribe">
                                        <!--<a href="http://nanostuffs.com/enotice/login.php" target="_blank">
								<a href="#download"><button class="btn-subscribe">SUBSCRIBE NOW</button></a>-->
                                    </div>
                                </div>
			    </div>
<!--
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="alert-plan">
                                    <div class="quarterly-plan">
                                        <h3><span class="rupee">&#8377;.</span> 249</h3>
                                        <h6>Yearly • Per Property</h6>
                                        <p>Set Alerts for 10+ up to 50<br>Properties</p>
                                    </div>
                                    <div class="subscribe">
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer -->
        <div id="contact" class="connect">
            <div class="container">
               <div class="row">
                   <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <h6>CONNECT</h6>
                        <h3>Have any questions?<br>Drop us a message.</h3>
                          <!--<form action="lite_process.php" method="post" class="connect-form">-->
                         <div class="alert alert-danger d-none" role="alert" id="form-error">

						</div>

						<div class="alert alert-success d-none" role="alert" id="form-success">

						</div>
						 <form id="contact-form" onSubmit="return false;" class="connect-form">
						 <input type="hidden" id="action" name="action" class="form-control-cpg" required value="submit-contact-form" />
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padd-lft">
                                <input type="text" placeholder="NAME" id="name" name="en-fname" class="form-control" required />
								<div class="text-center" id="name_error" style="color:red;"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padd-lft">
                                <input type="email" placeholder="EMAIL ID" type="email" id="email" name="en-email" class="form-control form-control-1" required/>
								<div class="text-center" id="email_error" style="color:red;"></div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-lft">
                                <textarea placeholder="MESSAGE" id="message" name="en-message" cols="" rows="6" class="form-control" required></textarea>
							<div class="text-center" id="message_error" style="color:red;"></div>
                            </div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-lft">
								<div class="col-md-12 text-center">
									<div class="g-recaptcha  text-center" data-sitekey="6LcMOtsZAAAAADCDwC9RTO6JJw3KlaEDvXSLI8aj" data-callback="recaptchaCallback"></div>
									<div id="captcha_error" style="color:red;"></div>
								</div>
                            </div>

                            <div class="submit-btn">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 padd-lft">
                                   <button class="btn-smt" onClick="register()">SEND MESSAGE</button>
                                </div>
                            </div>
                        </form>

                   </div>
                   <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                   <div class="address">
                            <!-- <h6>ADDRESS</h6> -->
                            <h3>Locate Us</h3>
                            <div class="address-details">
                                <div class="location">
                                <a href="https://goo.gl/maps/a7T8kgKyuRmiRi4b9" target="_blank"><img src="images/location.svg" class="img-location" alt="location"></a>
                                </div>
                                <div class="location-details">
                                    <h6><strong>Registered Office:</strong></h6>
                                   <p>eNotice Ninja Softech Private Limited,<br>1st Floor, ‘Sonigra Chambers’, Near Petrol Pump, Market Yard, Pune-411037, Maharashtra, India</p>
                                </div>
                            </div>
                            <div class="address-details">
                                <div class="location">
                                <a href="https://goo.gl/maps/vAhWgNEhWqYNxRSD8" target="_blank"><img src="images/location.svg" class="img-location" alt="location"></a>
                                </div>
                                <div class="location-details">
                                    <h6><strong>Corporate Office:</strong></h6>
                                    <p>eNotice Ninja Softech Private Limited,<br>‘Poonam Plaza’, Above Bank of Maharashtra, Market Yard Road, Pune-411037, Maharashtra, India</p>
                                </div>
                            </div>
                            <div class="address-details">
                                <div class="location">
                                    <img src="images/mail.svg" class="img-location-1" alt="location">
                                </div>
                                <div class="location-details-1">
                                    <a href="mailto:support@enoticeninja.com" target="_blank"><p>support@enoticeninja.com</p></a>
									<a href="mailto:support@enoticeninja.com" target="_blank"><p>+91 8600100070</p></a>
								</div>

                            </div>
                            <!--
                            <div class="social-links">
                                <p>Follow us on:</p>
                                <p><a href="https://www.facebook.com/enoticeninja/" target="_blank"><img src="images/facebook.svg" class="social-icon" alt="facebook"></a>
                                <a href="https://twitter.com/eNotice_Ninja" target="_blank"><img src="images/twitter.svg" class="social-icon" alt="twitter"></a>
                                <a href="https://www.youtube.com/channel/UCnTwcHCqN8T9MLezr2a9lRQ?view_as=subscriber" target="_blank"><img src="images/youtube.svg" class="social-icon-1" alt="youtube"></a>
                                <a href="https://www.instagram.com/enotice_ninja/" target="_blank"><img src="images/instagram.svg" class="social-icon-2" alt="instagram"></a>
                                <a href="https://www.linkedin.com/company/73452189/admin/" target="_blank"><img src="images/linkedin.svg" class="social-icon-3" alt="linkedin"></a></p>
                            </div>
                            -->
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div id="download" class="download">
                                        <p>Download App</p>
                                        <p><a href="https://apps.apple.com/in/app/enotice-ninja-pluss/id1557810777" target="_blank"><img src="images/apple-app.svg" class="app" alt="application"></a>
                                           <a href="https://play.google.com/store/apps/details?id=com.enoticeninja.pluss" target="_blank"><img src="images/google-play.svg" class="app" alt="application"></a></p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div id="download" class="download">
                                        <p>Scan QR Code</p>
                                        <p><img src="images/eNoticeQR.svg" class="app" alt="application"></p>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
               </div>
            </div>
            <hr>
            <div class="sub-footer d-none d-sm-block">
                <div class="container">
                    <p>Copyright &copy; 2020 eNotice Ninja Softech Private Limited - All rights reserved &nbsp;&vert;&nbsp; <a href="terms.php">Terms and Conditions</a> &nbsp; <a href="refund.php" >Refund Policy</a> &nbsp; <a href="privacy.php">Privacy Policy
                    </a></p>
                    <!--<p>Designed by: <a href="https://www.optimist.co.in/" target="_blank">Optimist Brand Design LLP.</a></p>-->
                </div>
            </div>
            <div class="sub-footer d-block d-sm-none">
                <div class="container">
                    <p>Copyright &copy; 2020 eNotice Ninja Softech Private Limited<br>All rights reserved &nbsp;&vert;&nbsp; <a href="terms.php">Terms and Conditions</a><br><a href="refund.php" >Refund Policy</a> &nbsp;&vert;&nbsp; <a href="privacy.php">Privacy Policy
                    </a></p>
                  <!--  <p>Designed by: <a href="https://www.optimist.co.in/" target="_blank">Optimist Brand Design LLP.</a></p>-->
                </div>
            </div>
        </div>

        <script>
            function openNav() {
              document.getElementById("mySidenav").style.width = "250px";
            }

            function closeNav() {
              document.getElementById("mySidenav").style.width = "0";
            }
        </script>

        <script src="assets/bootstrap/jquery-3.2.1.slim.min.js"></script>
        <script src="assets/bootstrap/jquery-3.5.1.min.js"></script>
        <script src="assets/bootstrap/bootstrap.min.js"></script>
		<script src="https://www.google.com/recaptcha/api.js" async defer></script>
		<script>
	function isCaptchaChecked() {
		return grecaptcha && grecaptcha.getResponse().length !== 0;
	}

	function recaptchaCallback() {
		if (!isCaptchaChecked()) {
			document.getElementById('captcha_error').style.display = '';
			document.getElementById('captcha_error').innerHTML = 'Please Solve the Captcha';
			iserror = false;
			return false;

		} else {
			document.getElementById('captcha_error').style.display = 'none';
			document.getElementById('captcha_error').innerHTML = '';
			iserror = true;
		}
	}

	function register() {
		//alert("hello");
		var name = document.getElementById("name").value;
		var email = document.getElementById("email").value;
		var message = document.getElementById("message").value;

		iserror = true;



		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		if (name == '') {
			document.getElementById('name_error').style.display = '';
			document.getElementById('name_error').innerHTML = 'Please Enter Name';
			iserror = false;
			return false;

		} else {
			document.getElementById('name_error').style.display = 'none';
			document.getElementById('name_error').innerHTML = '';
			iserror = true;
		}
		if (email == '') {
			document.getElementById('email_error').style.display = '';
			document.getElementById('email_error').innerHTML = 'Please Enter a valid Email';
			iserror = false;
			return false;

		} else {
			document.getElementById('email_error').style.display = 'none';
			document.getElementById('email_error').innerHTML = '';
			iserror = true;
		}
		if ($.trim(message) == '') {
			document.getElementById('message_error').style.display = '';
			document.getElementById("message_error").innerHTML = 'Please Enter the Message';
			iserror = false;
			return false;
		} else {
			document.getElementById('message_error').style.display = 'none';
			document.getElementById('message_error').innerHTML = '';
			iserror = true;
		}
		if (reg.test(email) == false) {
			document.getElementById('email_error').style.display = '';
			document.getElementById('email_error').innerHTML = 'Please Enter a valid Email';
			iserror = false;
			return false;

		} else {
			document.getElementById('email_error').style.display = 'none';
			document.getElementById('email_error').innerHTML = '';
			iserror = true;
		}
		if (!isCaptchaChecked()) {
			document.getElementById('captcha_error').style.display = '';
			document.getElementById('captcha_error').innerHTML = 'Please Solve the Captcha';
			iserror = false;
			return false;

		} else {
			document.getElementById('captcha_error').style.display = 'none';
			document.getElementById('captcha_error').innerHTML = '';
			iserror = true;
		}

		if (!iserror) {

			alert("Enter Compolsary fields");
		} else {
			var formData = $('#contact-form').serializeArray();
			$.ajax({
				type: "POST",
				url: 'submit-contact-form.php',
				data: formData,
				success: function(dataJson) {
					var data = dataJson;
					if(data == null || data == 'null')
					{
						//alert("im inn");
						$('#form-error').html('');
						$('#form-error').addClass('d-none');
						//$('#form-success').html(dataObj.message);
						$('#form-success').html('Feedback Registered. Will get back to you soon');
						$('#form-success').removeClass('d-none');
						//$('#contact-form').addClass('d-none');
						document.getElementById("name").value='';
						document.getElementById("email").value='';
						document.getElementById("message").value='';
						grecaptcha.reset();
					}
					else{
						//alert("im not inn");
						$('#form-error').html('');
						$('#form-error').addClass('d-none');
						//$('#form-success').html(dataObj.message);
						$('#form-success').html('Feedback Registered. Will get back to you soon.');
						$('#form-success').removeClass('d-none');
						//$('#contact-form').addClass('d-none');
						document.getElementById("name").value='';
						document.getElementById("email").value='';
						document.getElementById("message").value='';
						grecaptcha.reset();
					}
					/*else{
						var dataObj = JSON.parse(dataJson);

					if (dataObj['result'] == 'true' || dataObj['result'] == 'null' || dataObj['result'] == null ) {
						$('#form-error').html('');
						$('#form-error').addClass('d-none');
						//$('#form-success').html(dataObj.message);
						$('#form-success').html('Feedback Registered');
						$('#form-success').removeClass('d-none');
						//$('#contact-form').addClass('d-none');
						document.getElementById("name").value='';
						document.getElementById("email").value='';
						document.getElementById("message").value='';
						grecaptcha.reset();
					} else {
						$('#form-success').html('');
						$('#form-success').addClass('d-none');
						$('#form-error').html(dataObj.message);
						$('#form-error').removeClass('d-none');
						document.getElementById("name").value='';
						document.getElementById("email").value='';
						document.getElementById("message").value='';
						grecaptcha.reset();
					}
					} */


				}
			});

		}

	}
</script>




    </body>
</html>
