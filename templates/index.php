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
		<?php echo $content ?>
	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	    <script src="<?php echo BASE_URL ?>/js/jquery-3.1.0.min.js"></script>
	    <!-- Include all compiled plugins (below), or include individual files as needed -->
	    <script src="<?php echo BASE_URL ?>/js/bootstrap.min.js"></script>
</body>
</html>
