<?php

session_start();

require_once('config.php');
require_once('functions.php');


$lang_default = 'fr';
$lang_dispo = ['fr', 'en', 'ge', 'ru', 'cn'];

if(!isset($_SESSION['lang_user'])) {
  if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $lang_split = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
      $_SESSION['lang_user'] = explode('_',$lang_split)[0];
      if (!in_array($_SESSION['lang_user'], $lang_dispo)) {
        $_SESSION['lang_user'] = $lang_default;
      }
  }
  else{
      $_SESSION['lang_user'] = $lang_default;
  }
}


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