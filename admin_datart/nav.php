<!-- TOP NAV Menu, présent uniquement sur grand écran -->
<nav class="navbar-fixed-top hidden-sm hidden-xs">
    <div class="container-fluid" id="top-nav">
        <div class="row">
            <div class="col-lg-6 col-lg-offset-2 col-md-6 col-md-offset-2 ">
                <h1><?= isset($locationTitle)? $locationTitle : 'Bienvenue '.$currentUser->getPublicName().' '.$currentUser->getPublicSurname(); ?></h1>
            </div>
            <div class="col-lg-4 col-md-4" id="top-nav-ul">
                <ul>
                    <li><a href="user_account.php"><span class="fa fa-user"></span>Mon compte</a></li><!--
                    --><li><a href="logout.php"><span class="fa fa-sign-out"></span>Déconnexion</a></li>
                </ul>
            </div>
        <div>
    </div>
</nav>

<!-- MAIN NAV -->
<nav class="col-lg-2 col-md-2 col-sm-12 col-xs-12" id="main-nav">
    <div class="row">
        <!-- LOGO VERSION GRAND ECRAN-->
        <div class="col-lg-12 col-md-12 col-sm-4 hidden-sm col-xs-4 hidden-xs nav-logo">
            <a href="index.php"><img src="assets/images/datartGA_vecto-blanc.png"></a>
        </div>

        <!-- LOGO VERSION PETIT ECRAN-->
        <div class="col-lg-12 col-lg-push-0 hidden-lg col-md-12 col-md-push-0 hidden-md col-sm-4 col-sm-push-8 col-xs-4 col-xs-push-8 nav-logo">
            <a href="index.php"><img src="assets/images/DAgrand-angle_vecto-blanc.png"></a>
        </div>
        <div class="col-lg-12 hidden-lg col-md-12 hidden-md col-sm-8 col-sm-pull-4 col-xs-8 col-xs-pull-4">
            <div class="row">
                <p class="btn-nav col-sm-2 col-xs-2" id="open-nav"><span class="fa fa-bars"></span><br/>Menu</p>
                <p class="btn-nav col-sm-2 col-xs-2" id="close-nav"><span class="fa fa-close"></span><br/>Fermer</p>
                <h1 class="col-sm-10 col-xs-10"><?= isset($locationTitle)? $locationTitle :'Bienvenue '.$currentUser->getPublicName().' '.$currentUser->getPublicSurname(); ?></h1>
            </div>
        </div>
        <ul id="main-nav-menu">

            <li><a href="index.php"><span class="fa fa-dashboard"></span>Tableau de bord</a></li>
            <?=
                $currentUser->getStatus() == 0?'<li><a href="users_management.php"><span class="fa fa-users"></span>Gestion des utilisateurs</a></li>':'';
            ?>
            <li class="hidden-lg hidden-md"><a href="user_account.php"><span class="fa fa-user"></span>Mon compte</a></li>
            <li><a href="planning.php"><span class="fa fa-calendar"></span>Agenda</a></li>
            <li><a href="exhibit_management.php"><span class="fa fa-archive"></span>Les expositions <span class="fa fa-plus-circle link-plus"></span></a>
                <ul class="nav-submenu">
                    <li><a href="exhibit_zoom.php"><span class="fa fa-plus-circle"></span>Ajouter une exposition</a></li>
                </ul>
            </li>
            <li><a href="artist_management.php"><span class="fa fa-paint-brush"></span>Les artistes <span class="fa fa-plus-circle link-plus"></span></a>
                <ul class="nav-submenu">
                    <li><a href="artist_zoom.php"><span class="fa fa-plus-circle"></span>Ajouter un artiste</a></li>
                </ul>
            </li>
            <li><a href="artwork_management.php"><span class="fa fa-eye"></span>Les oeuvres <span class="fa fa-plus-circle link-plus"></span></a>
                <ul class="nav-submenu">
                    <li><a href="artwork_zoom.php"><span class="fa fa-plus-circle"></span>Ajouter une oeuvre</a></li>
                </ul>
            </li>
            <li><a href="statistics.php"><span class="fa fa-bar-chart"></span>Statistiques</a></li>
        </ul>
        <div id="background-nav"></div>
    </div>
</nav>
