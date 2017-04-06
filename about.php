<?php

require_once('admin_datart/includes/include.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/artwork_additional.php');
require_once('admin_datart/classes/artwork_textual_content.php');
require_once('admin_datart/classes/artwork_visual.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');
require_once('admin_datart/classes/event.php');


include('header.php');


?>

<section id="logo_GA">
	<div class="container_logo">
		<img src="<?= URL_ASSETS_FRONT ?>images/DAgrand-angle_vecto-blanc.png" alt="">
	</div>
</section>

<section id="GA_presentation">
	<div class="presentation">
		<h4><?= $lang[$_SESSION['lang_user']]['about.titre.presentation'] ?></h4>
		<p><?= $lang[$_SESSION['lang_user']]['about.presentation'] ?></p>
	</div>
</section>


<section id="mention">
	<div class="mentions_legales">
		<h4><?= $lang[$_SESSION['lang_user']]['about.titre.mentions'] ?></h4>
		<p><?= $lang[$_SESSION['lang_user']]['about.mentions'] ?></p>
	</div>

	<div class="mentions_legales">
	Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
	</div>
</section>

<?php

include('footer.php');
 ?>
