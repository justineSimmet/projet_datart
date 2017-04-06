<div class="container">
  <div class="row">
  <div class="col-xs-12">                  
  <div class="dropdown clearfix">
    <button class="btn dropdown-toggle pull-right" type="button" data-toggle="dropdown"><img src="assets/images/lang.svg" alt=""></button>
    <ul class="dropdown-menu dropdown-menu-right" id="changeLang">
      <li data-langue="fr"><a href="#"><?= $lang[$_SESSION['lang_user']]['nav.lang.fr'] ?></a></li>
      <li data-langue="en"><a href="#"><?= $lang[$_SESSION['lang_user']]['nav.lang.en'] ?></a></li>
      <li data-langue="ge"><a href="#"><?= $lang[$_SESSION['lang_user']]['nav.lang.ge'] ?></a></li>
      <li data-langue="ru"><a href="#"><?= $lang[$_SESSION['lang_user']]['nav.lang.ru'] ?></a></li>
      <li data-langue="cn"><a href="#"><?= $lang[$_SESSION['lang_user']]['nav.lang.cn'] ?></a></li>
    </ul>
  </div>
  </div></div> 
</div>



<nav class="navbar navbar-default navbar-fixed-bottom">
	<div class="navbar_container col-xs-12 col-sm-12 col-md-12">
		<div class="row">
      <ul class="nav navbar-nav col-xs-12 col-sm-12 col-md-12 ">

        <li class="text-center col-xs-3 col-sm-3 col-md-3"><a href="<?= URL_ROOT ?>artwork.php" ><span class="fa fa-eye"></span><span class="hidden-xs"><br /><?= $lang[$_SESSION['lang_user']]['nav.lien.oeuvres'] ?></span></a></li>

        <li class="text-center col-xs-3 col-sm-3 col-md-3"><a href="<?= URL_ROOT ?>artist.php" ><span class="fa fa-paint-brush"></span><span class="hidden-xs"><br /><?= $lang[$_SESSION['lang_user']]['nav.lien.artistes'] ?></span></a></li>

        <li class="text-center col-xs-3 col-sm-3 col-md-3"><a href="<?= URL_ROOT ?>exhibit.php"><span class="fa fa-cubes"></span><span class="hidden-xs"><br /><?= $lang[$_SESSION['lang_user']]['nav.lien.expo'] ?></span></a></li>

        <li class="text-center col-xs-3 col-sm-3 col-md-3"><a href="<?= URL_ROOT ?>about.php"><span class="fa fa-info-circle"></span><span class="hidden-xs"><br /><?= $lang[$_SESSION['lang_user']]['nav.lien.infos'] ?></span></a></li>

			 </ul>
		</div>
	</div>
</nav>

