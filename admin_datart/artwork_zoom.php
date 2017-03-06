<?php

require_once('classes/user.php');
require_once('classes/artwork.php');
require_once('classes/artist.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');
require_once('classes/artist.php');
require_once('classes/event.php');
require_once('includes/include.php');

$locationTitle = isset($targetExhibit)?$targetExhibit->getTitle():'Ajouter une oeuvre';
include('header.php');
?>


<?php
include('footer.php');
