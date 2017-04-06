<?php

require_once('admin_datart/includes/include.php');
require_once('admin_datart/classes/artwork.php');
require_once('admin_datart/classes/artwork_additional.php');
require_once('admin_datart/classes/artwork_textual_content.php');
require_once('admin_datart/classes/artwork_visual.php');
require_once('admin_datart/classes/artist.php');
require_once('admin_datart/classes/artist_textual_content.php');
require_once('admin_datart/classes/exhibit.php');
require_once('admin_datart/classes/exhibit_textual_content.php');

if (isset($_GET['exhibit'])){
	$targetExhibit = new Exhibit($_GET['exhibit']);

if (isset($_GET['id'])){
	$targetArtwork = new Artwork($_GET['id']);
	$parentArtist = new Artist($targetArtwork->getArtistId());

	if (isset($_SESSION['lang_user']) ) {
	  if ($_SESSION['lang_user'] == 'fr') {
	    require_once('lang_fr.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'en') {
	    require_once('lang_en.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ge') {
	    require_once('lang_ge.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ru') {
	    require_once('lang_ru.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'cn') {
	    require_once('lang_cn.php');
	  }
	}

	include_once('header.php');
?>

<section id="pic_artwork">
	<?php
		if(!empty($targetArtwork->getPictureOne()->getTarget())){
	?>
	<div class="container_pic">
		<img src="<?= URL_IMAGES.$targetArtwork->getPictureOne()->getTarget() ?>" alt="<?= $targetArtwork->getPictureOne()->getLegend() ?>">
	</div>
	<?php 
		}

	 ?>
</section>

<section id="artwork_presentation">
	<div class="artwork">
		<h3><?= $targetArtwork->getTitle() ?></h3>
		<p><?= $lang[$_SESSION['lang_user']]['artwork.nature'] ?></p>
		<p><a href="<?= URL_ROOT ?>artist.php?exhibit=<?= $targetExhibit->getId() ?>&id=<?= $parentArtist->getId() ?> "><?= $parentArtist->getIdentity() ?></a></p>
	</div>
	<p>
		<?= $lang[$_SESSION['lang_user']]['artwork.main'] ?>
		<?php
			if(!empty($targetArtwork->getPictureTwo()->getTarget())){
		?>
			<div class="container_pic">
				<img src="<?= URL_IMAGES.$targetArtwork->getPictureTwo()->getTarget() ?>" alt="<?= $targetArtwork->getPictureTwo()->getLegend() ?>">
			</div>
		<?php
			}
		?>
	</p>
		<?php
			if(!empty($targetArtwork->getPictureThree())){
		?>
			<div class="container_pic">
				<img src="<?= URL_IMAGES.$targetArtwork->getPictureThree()->getTarget() ?>" alt="<?= $targetArtwork->getPictureThree()->getLegend() ?>">
			</div>
		<?php
			}
		?>
</section>

<section id="artwork-galerie">
	<h4><?= $lang[$_SESSION['lang_user']]['artwork.titre.galerie'] ?></h4>
		<div class="gallery">
		<?php 
			// var_dump($targetArtwork->getVisual());
			$listVisuals = $targetArtwork->getVisual();
			$listData = array();
			foreach ($listVisuals as $visual) {
				?>
				<a href="<?= URL_IMAGES.$visual->getTarget() ?>" data-fancybox data-caption="<?= $visual->getLegend() ?>">
					<img src="<?= URL_IMAGES.$visual->getTarget() ?>" alt="<?= $visual->getLegend() ?>" />
				</a>
				<?php
			}
		?>
		</div>

</section>
	
<section id="artwork-more">
	<h4><?= $lang[$_SESSION['lang_user']]['artwork.titre.oeuvre'] ?></h4>
	<ul>
	<?php
		if (!empty($targetArtwork->getAdditional())) {
			$additionalList = $targetArtwork->getAdditional();
			foreach ($additionalList as $add) {
				if ($add->getFormat() == 'pdf') {
					echo '<li><a href="'.URL_IMAGES.$add->getTarget().'" target="_blank" </a><span class="fa fa-file-o"></span>'.$add->getName().'</li>';
				}
				if ($add->getFormat() != 'pdf' && $add->getFormat() != 'link') {
					echo '<li><a href="'.URL_IMAGES.$add->getTarget().'" target="_blank" </a><span class="fa fa-play-circle-o"></span>'.$add->getName().'</li>';
				}
				elseif($add->getFormat() == 'link'){
					echo '<li><a href="'.$add->getTarget().'" target="_blank" </a><span class="fa fa-external-link"></span>'.$add->getName().'</li>';
				}
	?>

	<?php
			}
		}
	?>
	</ul>
</section>

<?php
}
}
else{
$currentExhibit = Exhibit::currentExhibit();
foreach ($currentExhibit as $targetExhibit) {

	if (isset($_SESSION['lang_user']) ) {
	  if ($_SESSION['lang_user'] == 'fr') {
	    require_once('lang_fr.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'en') {
	    require_once('lang_en.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ge') {
	    require_once('lang_ge.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'ru') {
	    require_once('lang_ru.php');
	  }
	  elseif ($_SESSION['lang_user'] == 'cn') {
	    require_once('lang_cn.php');
	  }
	}

	include_once('header.php');
	?>

	<h2><?= $lang[$_SESSION['lang_user']]['expo.titre'] ?></h2>
	<section id="list_artwork">
	<h3><?= $lang[$_SESSION['lang_user']]['expo.titre.artistes.oeuvres'] ?></h3>
		<div>
			<ul>
		<?php 
			$listArtist = $targetExhibit->getArtistExposed();
			$listArtwork = $targetExhibit->getArtworkDisplayed();
			foreach ($listArtist as $artistId => $artistIdentity) {
					?>
					<li><a href="<?= URL_ROOT ?>artist.php?exhibit=<?= $targetExhibit->getId() ?>&id=<?= $artistId ?>"><span class="fa fa-paint-brush"></span> <?= $artistIdentity ?></a>
					<?php
					foreach ($listArtwork as $artworkId => $artwork) {
						if ($artwork['artist_id'] == $artistId) {
							?>
							<ul>
								<li><a href="<?= URL_ROOT ?>artwork.php?exhibit=<?= $targetExhibit->getId() ?>&id=<?= $artworkId ?>"><span class="fa fa-eye"></span> <?= $artwork['title'] ?></a></li>
							</ul>
							<?php
						}
					}
					echo'</li>';
				}	
		?>	
			</ul>
		</div>
	</section>
	<?php

}
}
include('footer.php');
 ?>
