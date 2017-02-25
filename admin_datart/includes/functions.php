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
function dateFormat($str){
	// YYYY-MM-DD -> DD/MM/YYYY
	$dbToNormalize ='#[2][0][0-9][0-9]-[0-1][0-9]-[0-3][0-9]#';
	$normalizeToDb = '#[0-3][0-9]/[0-1][0-9]/[2][0][0-9][0-9]#';
	$datetimeToNormalize = '#[2][0][0-9][0-9]-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]#';

	if(preg_match($datetimeToNormalize, $str)){
		list($date, $time) = split(' ', $str);
		list($year, $month, $day) = split('[-]', $date);
		list($hour, $minute, $seconds) = split('[:]', $time);
		$formatedDate = $day.'/'.$month.'/'.$year.' à '.$hour.'h'.$minute;
		return $formatedDate;	
	}
	elseif (preg_match($dbToNormalize, $str)) {
		list($year, $month, $day) = split('[-]', $str);
		$formatedDate = $day.'/'.$month.'/'.$year;
		return $formatedDate;
	}
	// DD/MM/YYYY -> YYYY-MM-DD 
	elseif (preg_match($normalizeToDb, $str)) {
		list($day, $month, $year) = split('[/]', $str);
		$formatedDate = $year.'-'.$month.'-'.$day;
		return $formatedDate;
	}
	else{
		return false;
	}

};
