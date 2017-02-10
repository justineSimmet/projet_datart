<!-- TOP NAV Menu, présent uniquement sur grand écran -->
<nav class="navbar-fixed-top hidden-sm hidden-xs">
    <div class="container-fluid" id="top-nav">

        <h1 class="col-lg-5 col-lg-offset-3 col-md-5 col-md-offset-3 "><?= isset($location)?$location:'Bienvenue '.$currentUser->getName().' '.$currentUser->getSurname(); ?></h1>

        <ul class="col-lg-4 col-md-4">
            <li><a href="user_account.php"><span class="fa fa-user"></span>Mon compte</a></li><!--
            --><li><a href="logout.php"><span class="fa fa-sign-out"></span>Déconnexion</a></li>
        </ul>

    </div>
</nav>

<!-- MAIN NAV -->
<nav class="col-lg-3 col-md-3" id="main-nav">
    <div class="row">
        <div class="nav-logo col-lg-12 col-md-12">
            <img src="assets/images/datartGA_vecto-blanc.png"> <!-- Logo écran large -->
        </div>

        <ul id="main-menu">
            <li><a href="dashboard.php"><span class="fa fa-dashboard"></span>Tableau de bord</a></li>
            <?=
                $currentUser->getStatus() == 0?'<li><a href="users_management.php"><span class="fa fa-users"></span>Gestion des utilisateurs</a></li>':'';
            ?>
            <li><a href="planning.php"><span class="fa fa-calendar"></span>Agenda</a></li>
            <li><a href="exhibit_management.php"><span class="fa fa-archive"></span>Les expositions
                <ul>
                    <a href="exhibit_zoom.php"><span> >> </span>Ajouter une expo.</a></li>
                </ul>
            </a></li>
            <li><a href="artist_management.php"><span class="fa fa-paint-brush"></span>Les artistes
                <ul>
                    <a href="artist_zoom.php"><span> >> </span>Ajouter un artiste</a></li>
                </ul>
            </a></li>
            <li><a href="artwork_management.php"><span class="fa fa-eye"></span>Les oeuvres
                <ul>
                    <a href="artwork_zoom.php"><span> >> </span>Ajouter une oeuvre</a></li>
                </ul>
            </a></li>
            <li><a href="statistics.php"><span class="fa fa-bar-chart"></span>Statistiques</a></li>
        </ul>

    </div>
</nav>
