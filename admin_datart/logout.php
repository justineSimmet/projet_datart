<?php

require_once('classes/user.php');
require_once('includes/include.php');

if (isset($currentUser)) {
	$deco = $currentUser->disconnect();
	header('Location:login.php');
}

