 <!DOCTYPE html>
 <html lang="fr">
 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
 	<title><?= isset($location)?$location:'Application DATART / Grand Angle'; ?></title>
 	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.structure.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.theme.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css" />
 	<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
 </head>
 <body>
<div class="container-fluid">

	<div class="row">
<?php
	include('nav.php');
?>
		<div class="col-lg-10 col-lg-offset-2 col-md-10 col-md-offset-2 col-sm-12 col-xs-12" id="main-container">
