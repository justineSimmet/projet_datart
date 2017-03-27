<?php

session_start();

require_once('config.php');
require_once('functions.php');

if (isset($_SESSION['id']) && strpos($_SERVER['PHP_SELF'], 'login.php')) {
    header('Location:index.php');
}
elseif (empty($_SESSION['id']) && !strpos($_SERVER['PHP_SELF'], 'admin_datart/') ) {
    return TRUE;
}
elseif (empty($_SESSION['id']) && !strpos($_SERVER['PHP_SELF'], 'login.php') ) {
    header('Location:login.php');
}

if (isset($_SESSION['id']) && strpos($_SERVER['PHP_SELF'], 'admin_datart/') ) {
    $currentUser = new User($_SESSION['id']);
}


?>