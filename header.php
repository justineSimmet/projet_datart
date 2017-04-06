<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

 	<title><?= isset($location)?$location:' Grand Angle'; ?></title>

 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS_FRONT ?>styles/css/bootstrap.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS_FRONT ?>styles/css/font-awesome.min.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS_FRONT ?>styles/css/reset.css" />
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS_FRONT ?>styles/css/jquery.fancybox.min.css">
 	<link rel="stylesheet" type="text/css" href="<?= URL_ASSETS_FRONT ?>styles/css/stylefront.css" />
</head>
<body>

<div class="container-fluid">

	<div class="row">
<?php

	include('nav.php');