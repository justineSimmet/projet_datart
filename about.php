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

include('header.php');


?>

<section id="logo_GA">
	<div class="container_logo">
		<img src="<?= URL_ASSETS_FRONT ?>images/datartGA_vecto-rvb.png" alt="">
	</div>
</section>

<section id="GA_presentation">
	<div class="presentation">
		<h3><?= $lang[$_SESSION['lang_user']]['about.titre.presentation'] ?></h3>
		<p><?= $lang[$_SESSION['lang_user']]['about.presentation'] ?></p>
	</div>
</section>


<section id="mention">
	<div class="mentions_legales">
		<h3><?= $lang[$_SESSION['lang_user']]['about.titre.mentions'] ?></h3>
	
	<p>
	Merci de lire avec attention les différentes modalités d’utilisation du présent site avant d’y parcourir ses pages. En vous connectant sur ce site, vous acceptez sans réserves les présentes modalités. Aussi, conformément à l’article n°6 de la Loi n°2004-575 du 21 Juin 2004 pour la confiance dans l’économie numérique, les responsables du présent site internet <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a> sont :

	<h5><b>Editeur du Site : </b></h5>
	Grand Angle
	Numéro de SIRET :  55214450300018
	Responsable editorial : Mr Fioret
	28, rue de la Galerie 37000 Tours
	Téléphone :0549373737 - Fax : 0549363636
	Email : monsieur.fioret@grand-angle-galerie.com
	Site Web : <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a>
	</br>
	<h5><b>Hébergement :</b></h5>
	Hébergeur : Localhost
	5, rue de l'hebergeur 75000 Paris
	Site Web :  <a href="http://www.hebergeur.localhost.com">www.hebergeur.localhost.com</a>
	</br>
	<h5><b>Développement : Datart</b></h5>
	Adresse : 9, avenue Simmet  Bat Huerta 86000 Poitiers
	Site Web : <a href="http://www.datart-rocks.com">www.datart-rocks.com</a>
	</br>
	<h5><b>Conditions d’utilisation : </b></h5>
	Ce site (<a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a>) est proposé en différents langages web (HTML, HTML5, Javascript, CSS, etc…) pour un meilleur confort d'utilisation et un graphisme plus agréable, nous vous recommandons de recourir à des navigateurs modernes comme Internet explorer, Safari, Firefox, Google Chrome, etc…
	Les mentions légales ont été générées sur le site <a title="générateur de mentions légales pour site internet gratuit" href="http://www.generateur-de-mentions-legales.com">Générateur de mentions légales</a>, offert par <a title="imprimerie paris, imprimeur paris" href="http://welye.com">Welye</a>.
	</p>
	<span style="color: #323333;">Grand Angle<b> </b></span>met en œuvre tous les moyens dont elle dispose, pour assurer une information fiable et une mise à jour fiable de ses sites internet. Toutefois, des erreurs ou omissions peuvent survenir. L'internaute devra donc s'assurer de l'exactitude des informations auprès de , et signaler toutes modifications du site qu'il jugerait utile. n'est en aucun cas responsable de l'utilisation faite de ces informations, et de tout préjudice direct ou indirect pouvant en découler.
	<br>
	<h5><b>Cookies :</b></h5>Le site <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a> peut-être amené à vous demander l’acceptation des cookies pour des besoins de statistiques et d'affichage. Un cookies est une information déposée sur votre disque dur par le serveur du site que vous visitez. Il contient plusieurs données qui sont stockées sur votre ordinateur dans un simple fichier texte auquel un serveur accède pour lire et enregistrer des informations . Certaines parties de ce site ne peuvent être fonctionnelles sans l’acceptation de cookies.
	<br>
	<h5><b>Liens hypertextes :</b></h5> Les sites internet de peuvent offrir des liens vers d’autres sites internet ou d’autres ressources disponibles sur Internet. Grand Angle ne dispose d'aucun moyen pour contrôler les sites en connexion avec ses sites internet. ne répond pas de la disponibilité de tels sites et sources externes, ni ne la garantit. Elle ne peut être tenue pour responsable de tout dommage, de quelque nature que ce soit, résultant du contenu de ces sites ou sources externes, et notamment des informations, produits ou services qu’ils proposent, ou de tout usage qui peut être fait de ces éléments. Les risques liés à cette utilisation incombent pleinement à l'internaute, qui doit se conformer à leurs conditions d'utilisation.

	Les utilisateurs, les abonnés et les visiteurs des sites internet de ne peuvent mettre en place un hyperlien en direction de ce site sans l'autorisation expresse et préalable de Grand Angle.

	Dans l'hypothèse où un utilisateur ou visiteur souhaiterait mettre en place un hyperlien en direction d’un des sites internet de Grand Angle, il lui appartiendra d'adresser un email accessible sur le site afin de formuler sa demande de mise en place d'un hyperlien. Grand Angle se réserve le droit d’accepter ou de refuser un hyperlien sans avoir à en justifier sa décision.
	</br>
	<h5><b>Services fournis : </b></h5>
	L'ensemble des activités de la société ainsi que ses informations sont présentés sur notre site <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a>.
	</br>
	Grand Angle s’efforce de fournir sur le site www.grand-angle-visite.fr des informations aussi précises que possible. les renseignements figurant sur le site <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a> ne sont pas exhaustifs et les photos non contractuelles. Ils sont donnés sous réserve de modifications ayant été apportées depuis leur mise en ligne. Par ailleurs, tous les informations indiquées sur le site www.grand-angle-visite.fr sont données à titre indicatif, et sont susceptibles de changer ou d’évoluer sans préavis.
	</br>
	<h5><b>Limitation contractuelles sur les données : </b></h5>
	Les informations contenues sur ce site sont aussi précises que possible et le site remis à jour à différentes périodes de l’année, mais peut toutefois contenir des inexactitudes ou des omissions. Si vous constatez une lacune, erreur ou ce qui parait être un dysfonctionnement, merci de bien vouloir le signaler par email, à l’adresse monsieur.fioret@grand-angle-galerie.com, en décrivant le problème de la manière la plus précise possible (page posant problème, type d’ordinateur et de navigateur utilisé, …).
	Tout contenu téléchargé se fait aux risques et périls de l'utilisateur et sous sa seule responsabilité. En conséquence, ne saurait être tenu responsable d'un quelconque dommage subi par l'ordinateur de l'utilisateur ou d'une quelconque perte de données consécutives au téléchargement. De plus, l’utilisateur du site s’engage à accéder au site en utilisant un matériel récent, ne contenant pas de virus et avec un navigateur de dernière génération mis-à-jour.

	Les liens hypertextes mis en place dans le cadre du présent site internet en direction d'autres ressources présentes sur le réseau Internet ne sauraient engager la responsabilité de Grand Angle.
	</br>
	<h5><b>Propriété intellectuelle :</b></h5>
	Tout le contenu du présent sur le site <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a>, incluant, de façon non limitative, les graphismes, images, textes, vidéos, animations, sons, logos, gifs et icônes ainsi que leur mise en forme sont la propriété exclusive de la société à l'exception des marques, logos ou contenus appartenant à d'autres sociétés partenaires ou auteurs.

	Toute reproduction, distribution, modification, adaptation, retransmission ou publication, même partielle, de ces différents éléments est strictement interdite sans l'accord exprès par écrit de Grand Angle. Cette représentation ou reproduction, par quelque procédé que ce soit, constitue une contrefaçon sanctionnée par les articles L.335-2 et suivants du Code de la propriété intellectuelle. Le non-respect de cette interdiction constitue une contrefaçon pouvant engager la responsabilité civile et pénale du contrefacteur. En outre, les propriétaires des Contenus copiés pourraient intenter une action en justice à votre encontre.
	</br>
	<h5><b>Déclaration à la CNIL : </b></h5>
	Conformément à la loi 78-17 du 6 janvier 1978 (modifiée par la loi 2004-801 du 6 août 2004 relative à la protection des personnes physiques à l'égard des traitements de données à caractère personnel) relative à l'informatique, aux fichiers et aux libertés, ce site n'a pas fait l'objet d'une déclaration  auprès de la Commission nationale de l'informatique et des libertés (<a href="http://www.cnil.fr/">www.cnil.fr</a>).
	</br>
	<h5><b>Litiges : </b></h5>
	Les présentes conditions du site <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a> sont régies par les lois françaises et toute contestation ou litiges qui pourraient naître de l'interprétation ou de l'exécution de celles-ci seront de la compétence exclusive des tribunaux dont dépend le siège social de la société. La langue de référence, pour le règlement de contentieux éventuels, est le français.
	</br>
	<h5><b>Données personnelles :</b></h5>
	De manière générale, vous n’êtes pas tenu de nous communiquer vos données personnelles lorsque vous visitez notre site Internet <a href="http://www.grand-angle-visite.fr">www.grand-angle-visite.fr</a>.

	Cependant, ce principe comporte certaines exceptions. En effet, pour certains services proposés par notre site, vous pouvez être amenés à nous communiquer certaines données telles que : votre nom, votre fonction, le nom de votre société, votre adresse électronique, et votre numéro de téléphone. Tel est le cas lorsque vous remplissez le formulaire qui vous est proposé en ligne, dans la rubrique « contact ». Dans tous les cas, vous pouvez refuser de fournir vos données personnelles. Dans ce cas, vous ne pourrez pas utiliser les services du site, notamment celui de solliciter des renseignements sur notre société, ou de recevoir les lettres d’information.

	Enfin, nous pouvons collecter de manière automatique certaines informations vous concernant lors d’une simple navigation sur notre site Internet, notamment : des informations concernant l’utilisation de notre site, comme les zones que vous visitez et les services auxquels vous accédez, votre adresse IP, le type de votre navigateur, vos temps d'accès. De telles informations sont utilisées exclusivement à des fins de statistiques internes, de manière à améliorer la qualité des services qui vous sont proposés. Les bases de données sont protégées par les dispositions de la loi du 1er juillet 1998 transposant la directive 96/9 du 11 mars 1996 relative à la protection juridique des bases de données.
</p>	

	</div>
</section>

<section id="credits">
	<div class="credits">
	<h4><?= $lang[$_SESSION['lang_user']]['about.titre.credits'] ?></h4>
	<p>Icons : <a href="http://www.freepik.com" title="Freepik">Freepik</a>

	<p>Use to edit and share gradient : <a href="http://colorzilla.com/gradient-editor">colorzilla.com</a></p>
	<p>Use for logos : <a href="http://fontawesome.io/3.2.1/">fontasewome.com</a></p>
	</div>
</section>


<?php

include('footer.php');
 ?>
