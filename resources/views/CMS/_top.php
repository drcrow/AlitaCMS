<?php
/*
session_start();

//LOGOUT
if(isset($_GET['logout'])){
	logout();
}

//LOGIN
if(isset($_POST['loginBtn'])){
	if(login($_POST['inputUsername'], $_POST['inputPassword'])){

	}else{
		$loginError = true;
	}
}
*/
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="<?php echo env('SITE_URL'); ?>/IMG/icon.png" />

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<link rel="stylesheet" href="<?php echo env('SITE_URL'); ?>/CSS/style.css">


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- WYSIWYG -->

	<script src="<?php echo env('SITE_URL'); ?>/trumbowyg/dist/trumbowyg.min.js"></script>
	<link rel="stylesheet" href="<?php echo env('SITE_URL'); ?>/trumbowyg/dist/ui/trumbowyg.min.css">


    <title><?php echo env('PAGE_TITLE'); ?></title>
</head>
<body>