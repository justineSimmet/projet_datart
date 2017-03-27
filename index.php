<?php

require_once('admin_datart/classes/user.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');
require_once('admin_datart/classes/event.php');
require_once('admin_datart/includes/include.php');
include('header.php');


if (!isset($languages)) {
	$languages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
	 // chop Supprime les espaces de fin de chaîne
	$languages = strtolower(substr(chop($languages[0]),0,2));
}
else {
header("Location: /index.php?Langue=en");
ou alors 
include(le fichier de config qui contient les trad)
} 

?>

<section id="pic_artwork">
	<div class="container_pic">
		<img src="<?= URL_ASSETS_FRONT ?>images/cat.jpg" alt="">
	</div>
</section>



<?php

include('footer.php');
 ?>
