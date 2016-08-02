<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head lang="es">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="<?php echo isset($description) ? $description : '' ?>" />
	<meta name="keywords" content="<?php echo isset($keywords) ? $keywords : '' ?>" />
	<title><?php echo isset($title) ? $title : '' ?></title>

	<link href="<?php echo BASE_URL ?>/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL ?>/css/default.css" />

	<!-- Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Lato:400,700,900,300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,600,700,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
	<div class="wrapper">
<!-- header section -->
<header>
  <div class="jumbotron jumbotron-fluid" id="banner">
    <div class="parallax text-center" style="background-image: url(img/cover.jpg);">
      <div class="parallax-pattern-overlay">
        <div class="container text-center" style="height:580px;padding-top:170px;">
          <a href="#"<img id="site-title" src="img/logo.png" alt="logo"></a>
          <h2 class="display-2">Springtime Framework 1.5</h2>
          <h3 class="learn">Installation successful</h3>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- About us section -->
<section class="aboutus" id="aboutus">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
			</div>
    </div>
    <div class="row">
      <div class="col-md-3">
      </div>
      <div class="col-md-3">
      </div>
      <div class="col-md-3">
      </div>
      <div class="col-md-3">
      </div>
    </div>
  </div>
</section>

<!-- Features section -->
<section class="features" id="features">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
					<ul class="learn">
						<li>Full fledged MVC implementation</li>
						<li>Convention over configuration design solution</li>
						<li>Cache</li>
						<li>Templating</li>
						<li>ACL</li>
					</ul>
      </div>
    </div>
  </div>
</section>

<!-- Contact section -->
<section class="contact" id="section">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<section class="footer" id="footer">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul>
          <li><a href="#"><i class="fa fa-twitter"></i></a></li>
          <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
          <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
        </ul>
        <p>&copy; 2016 - Springtime Framework</p>
      </div>
    </div>
  </div>
</section>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo BASE_URL ?>/js/jquery-3.1.0.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo BASE_URL ?>/js/bootstrap.min.js"></script>
</body>
</html>
