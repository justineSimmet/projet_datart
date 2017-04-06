<?php 
$lang = [
	
	'cn' =>[
		"nav.lien.oeuvres" => "艺术品",
		"nav.lien.artistes" => "艺术家",
		"nav.lien.expo" => "展示",
		"nav.lien.infos" => "关于",
		"nav.lang.fr" => "Francais",
		"nav.lang.en" => "English",
		"nav.lang.ge" => "Deutsche",
		"nav.lang.ru" => "Pусский",
		"nav.lang.cn" => "中文",

		"artist.biography"=> !isset($targetArtist)?'':$targetArtist->getChineseBiography()->getContent(),
		"artist.name"=>!isset($targetArtist)?'':$targetArtist->getIdentity(),
		"artist.liste.oeuvres" => "暴露的艺术品列表",
		"artist.pic"=> !isset($targetArtist)?'':$targetArtist->getPhotographicPortrait(),
		"artist.list.artistes" => "展出的艺术家",
		
		"expo.titre"=> !isset($targetExhibit)?'':$targetExhibit->getTitle(),
		"expo.resume"=> !isset($targetExhibit)?'':$targetExhibit->getChineseSummary()->getContent(),
		"expo.duree" => !isset($targetExhibit)?'':dateFormat($targetExhibit->getBeginDate()).' - '.dateFormat($targetExhibit->getEndDate()),
		"expo.horaires" => !isset($targetExhibit)?'':$targetExhibit->getPublicOpening(),
		"expo.titre.artistes.oeuvres" => "艺术家和作品提出",

		"artwork.titre.galerie" => "画廊",
		"artwork.titre.oeuvre" => "超越艺术品",
		"artwork.name" => !isset($targetArtwork)?'':$targetArtwork->getTitle(),
		"artwork.pic"=> !isset($targetArtwork)?'':$targetArtwork->getPictureOne()->getTarget(),
		"artwork.pic.two"=> isset($targetArtwork) && !empty($targetArtwork->getPictureTwo())?$targetArtwork->getPictureTwo()->getTarget():'',
		"artwork.nature"=> !isset($targetArtwork)?'':$targetArtwork->getChineseCharacteristic()->getContent(),
		"artwork.artist.name"=> !isset($targetArtwork)?'':$targetArtwork->getArtistId(),
		"artwork.main"=> !isset($targetArtwork)?'':$targetArtwork->getChineseMain()->getContent(),

		"about.titre.presentation" => "协会介绍",
		"about.presentation" => "自1996年以来，“大角度”协会一直在图书馆管理一个致力于文化的展览空间。 对该地区的文化发展非常活跃。 大角度定期为艺术家提供其使用场所。",
		"about.titre.mentions" => "版本说明",
		"about.mentions" => " Lorem ipsum dolor sit amet, consectetur adipisicing elit. Doloribus ipsam earum ipsum eum excepturi iure et sequi commodi sunt facere sed adipisci asperiores quas, quia ullam, animi repellat minus ea!"
	],
];


?>

