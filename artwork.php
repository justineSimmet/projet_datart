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

// if (isset($_GET['id'])) {
// 	$targetArtwork = new Artwork($_GET['id']);
// }

// require_once('lang.php');

include('header.php');
if (isset($_GET['exhibit']) && isset($_GET['artist'])) {


?>

<section id="pic_artwork">
	<?php 

	 ?>
	<div class="container_pic">
		<img src="<?= $lang[$_SESSION['lang_user']]['artwork.pic'] ?>" alt="">
	</div>
	<?php 

	 ?>
</section>

<section>
	<div>
	<?php 
		$nameArtist = $lang[$_SESSION['lang_user']]['artwork.artist.name'];
		$nameArtist = new Artist($_GET['id']);
		$lang[$_SESSION['lang_user']]['artwork.artist.name'] = $nameArtist->getIdentity();
	 ?>
		<h3>"<?= $lang[$_SESSION['lang_user']]['artwork.name'] ?>		"(<?= $lang[$_SESSION['lang_user']]['artwork.nature'] ?>) --  <?= $lang[$_SESSION['lang_user']]['artwork.artist.name'] ?></h3>

	</div>
</section>

<section id="text">
	<div>
		<p>
			<?= $lang[$_SESSION['lang_user']]['artwork.main'] ?>
		</p>

	</div>

	<div>
		<img src="<?= $lang[$_SESSION['lang_user']]['artwork.pic.two'] ?>" alt="">
	</div>

</section>
<?php 
}
else{
	?>

	<?php
	$currentExhibit = Exhibit::currentExhibit();
	foreach ($currentExhibit as $targetExhibit) {
		$listArtwork = $targetExhibit->getArtworkDisplayed();
		?>
		<ul>
			
		<?php 

		foreach ($listArtwork as $artwork) {
		?>
			<li><a href="<?= URL_ROOT?>artwork.php?exhibit=<?= $targetExhibit->getId()?>&artwork=<?= $artwork->getId()?>" class="list"><?= $artwork->getTitle() ?></a></li>
		<?php 	
		}
		?>
	</ul>
	<?php
	}
}

?>




<?php

include('footer.php');
 ?>
