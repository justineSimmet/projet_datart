<?php
/* ---------------------------
	Datart
	Creation Date : 27/01/2017
	includes/functions.php
	author : ApplePie Cie
---------------------------- */

// Utilise PDO pour se connecter à la base de donnée avec les paramètres definit dans config.
// Le array permet de retourner les erreurs de connexion si elles existent.
// Récupère le résultat de la requête dans la variable $resultat et vérifie sa validité
// Si la requête est un INSERT, récupère l'ID créé
// Referme la connexion SQL avant de retourner le résultat.
function requete_sql($requete){
	try{
		$sql = new PDO('mysql:dbname='.sql_database.';host='.sql_server, sql_user, sql_pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

		$resultat = $sql->query($requete);

	} catch (PDOException $e) {
    	echo $e->getMessage();
	}
	
	if ($resultat) {
		if (preg_match("/INSERT/", $requete)) {
			$resultat = $sql->lastInsertId();
		}
		$sql=null;
		return $resultat;
	}
	else{
		$sql=null;
		return false;
	}
};

// Fonction de formatage des dates
function format_date($str){
	$mois=array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
	$tableau = explode(' ', $str);
	$date= explode('-', $tableau[0]);
	$heure = explode(':', $tableau[1]);

	return $date[2].' '.$mois[$date[1]-1].' '.$date[0].' à '.$heure[0].'h'.$heure[1];
};