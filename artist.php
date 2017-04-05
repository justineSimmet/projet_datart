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

// require_once('lang.php');

include('header.php');
if (isset($_GET['exhibit']) && isset($_GET['artist'])) {

?>

<section id="pic_artist">
	<?php 
		if (isset($targetArtist)) {

	 ?>
	<div class="container_pic">
		<img src="<?= $lang[$_SESSION['lang_user']]['artist.pic'] ?>" alt="">
	</div>
	<?php 
		}

	 ?>
</section>

<section id="artist_presentation">
	<div class="artist">
		<h3><?= $lang[$_SESSION['lang_user']]['artist.name'] ?></h3>
		<p><?= $lang[$_SESSION['lang_user']]['artist.biography'] ?></p>
	</div>
</section>

<section id="artist_listArtwork">
	<div class="listArtwork">
		<h3><?= $lang[$_SESSION['lang_user']]['artist.liste.oeuvres'] ?></h3>

		<?php 
			$list = $lang[$_SESSION['lang_user']]['artist.oeuvres.exposees']
		 ?>

	</div>
</section>


<?php
	
}
else{
	$currentExhibit = Exhibit::currentExhibit();
	foreach ($currentExhibit as $targetExhibit) {
		$listArtist = $targetExhibit->getArtistExposed();
		?>
		<ul>
			
		<?php 

		foreach ($listArtist as $artist) {
		?>
			<li><a href="<?= URL_ROOT?>artist.php?exhibit=<?= $targetExhibit->getId()?>&artist=<?= $artist->getId()?>" class="list"><?= $artist->getIdentity() ?></a></li>
		<?php 	
		}
		?>
	</ul>
	<?php
	}
}
include('footer.php');
 ?>
