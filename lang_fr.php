<?php 
$lang = [
	'fr' =>[
		"nav.lien.oeuvres" => "Oeuvres",
		"nav.lien.artistes" => "Artistes",
		"nav.lien.expo" => "Expos",
		"nav.lien.infos" => "Infos",
		"nav.lang.fr" => "Francais",
		"nav.lang.en" => "English",
		"nav.lang.ge" => "Deutsche",
		"nav.lang.ru" => "Pусский",
		"nav.lang.cn" => "中文",
		
		"artist.biography"=> !isset($targetArtist)?'':$targetArtist->getFrenchBiography()->getContent(),
		"artist.name"=>!isset($targetArtist)?'':$targetArtist->getIdentity(),
		"artist.liste.oeuvres" => "Liste des oeuvres exposées",
		"artist.pic"=> !isset($targetArtist)?'':$targetArtist->getPhotographicPortrait(),
		"artist.list.artistes" => "Artistes exposés",

		"expo.titre"=> isset($targetExhibit)?$targetExhibit->getTitle():'',
		"expo.resume"=> !isset($targetExhibit)?'':$targetExhibit->getFrenchSummary()->getContent(),
		"expo.duree" => isset($targetExhibit)?'Du '.dateFormat($targetExhibit->getBeginDate()).' - '.dateFormat($targetExhibit->getEndDate()):'',
		"expo.horaires" => !isset($targetExhibit)?'':$targetExhibit->getPublicOpening(),
		"expo.titre.artistes.oeuvres" => "Artistes et oeuvres proposés",

		"artwork.titre.galerie" => "Galerie",
		"artwork.titre.oeuvre" => "Au delà de l'oeuvre",
		"artwork.nature"=> !isset($targetArtwork)?'':$targetArtwork->getFrenchCharacteristic()->getContent(),
		"artwork.main"=> !isset($targetArtwork)?'':$targetArtwork->getFrenchMain()->getContent(),

		"about.titre.presentation" => "Présentation de l'association ",
		"about.presentation" => "L’association Grand Angle gère depuis 1996 un espace d’exposition dédié à la culture sur la ville de Tours. Elle est très active sur le plan du développement culturel de la région. Grand Angle propose régulièrement à des artistes l’utilisation de ses locaux.",
		"about.titre.mentions" => "Mentions légales ",
		"about.titre.credits" => "Crédits "
	],	
];


?>

