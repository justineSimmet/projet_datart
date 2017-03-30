<?php 
require_once('admin_datart/includes/include.php');


if(isset($_POST['language'])) {

  $_SESSION['lang_user'] = $_POST['language'];
  $_SESSION['users_changeLang'] = true;
}


 ?>