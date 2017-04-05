<?php

// require_once('lang.php');
require_once('admin_datart/includes/include.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');
require_once('admin_datart/classes/event.php');

if (isset($_GET['id'])) {
	$targetArtist = new Artist($_GET['id']);
}

include('header.php');
header('Location:exhibit.php');
?>

<section id="pic_artwork">
	<div class="container_pic">
		<img src="<?= URL_ASSETS_FRONT ?>images/cat.jpg" alt="">
	</div>
</section>



<?php

include('footer.php');
 ?>
