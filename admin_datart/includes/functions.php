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
		$sql=NULL;
		return $resultat;
	}
	else{
		$sql=NULL;
		return FALSE;
	}
};

// Fonction de formatage des dates
function dateFormat($str){
	// YYYY-MM-DD -> DD/MM/YYYY
	$dbToNormalize ='#[2][0][0-9][0-9]-[0-1][0-9]-[0-3][0-9]#';
	$normalizeToDb = '#[0-3][0-9]/[0-1][0-9]/[2][0][0-9][0-9]#';
	$datetimeToNormalize = '#[2][0][0-9][0-9]-[0-1][0-9]-[0-3][0-9] [0-2][0-9]:[0-5][0-9]:[0-5][0-9]#';

	if(preg_match($datetimeToNormalize, $str)){
		$date = DateTime::createFromFormat('Y-m-d H:i:s', $str);
		return $formatedDate =  $date->format('d/m/Y à H:s');	
	}
	elseif (preg_match($dbToNormalize, $str)) {
		$date = DateTime::createFromFormat('Y-m-d', $str);
		return $formatedDate =  $date->format('d/m/Y');
	}
	// DD/MM/YYYY -> YYYY-MM-DD 
	elseif (preg_match($normalizeToDb, $str)) {
		$date = DateTime::createFromFormat('d/m/Y', $str);
		return $formatedDate =  $date->format('Y-m-d');
	}
	else{
		return FALSE;
	}
};


function timeFormat($str){
	$dbToNormalize = '#[0-2][0-9]:[0-5][0-9]:[0-5][0-9]#';
	$normalizeToDb = '#[0-2][0-9]h[0-5][0-9]#';
	$errorToDb = '#[0-2][0-9]h#';

	if (preg_match($dbToNormalize, $str)) {
		$time = DateTime::createFromFormat('H:i:s', $str);
		return $formatedTime =  $time->format('H\hi');
	}
	elseif (preg_match($normalizeToDb, $str)) {
		$time = DateTime::createFromFormat('H\hi', $str);
		return $formatedTime =  $time->format('H:i:s');
	}
	elseif (preg_match($errorToDb, $str)) {
		$time = DateTime::createFromFormat('H\h', $str);
		return $formatedTime =  $time->format('H:i:s');
	}
	else{
		return FALSE;
	}
};

function lastCreateElement(){
	$res = requete_sql("SELECT id,creation_date,visible, 'artist' AS source FROM artist
		UNION
		SELECT id,creation_date,visible, 'artwork' AS source FROM artwork
		UNION
		SELECT id,creation_date,visible, 'exhibit' AS source FROM exhibit
		WHERE visible = TRUE
		ORDER BY creation_date DESC
		LIMIT 0,10");
	$res = $res->fetchAll(PDO::FETCH_ASSOC);
	$last = array();
	foreach ($res as $l) {
		switch ($l['source']) {
			case 'artist':
				array_push($last, new Artist($l['id']));
				break;
			
			case 'artwork':
				array_push($last, new Artwork($l['id']));
				break;

			case 'exhibit':
				array_push($last, new Exhibit($l['id']));
				break;	
		}
	}
	return $last;
}
