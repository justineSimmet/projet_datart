<?php
/* ---------------------------
	DatArt
	Creation Date : 20/02/2017
	classes/exhibit.php
	author : ApplePie Cie
---------------------------- */

class Exhibit{
	private $id;
	private $title;
	private $begin_date;
	private $end_date;
	private $public_opening;
	private $visible;
	private $creation_date;
	private $textual_content;

	function __construct($id=''){
		if($id != 0){
			$res= requete_sql("SELECT * FROM exhibit WHERE id = '".$id."' ");
			$exhibit = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $exhibit['id'];	
			$this->title = $exhibit['exhibit_title'];	
			$this->begin_date = $exhibit['begin_date'];	
			$this->end_date = $exhibit['end_date'];	
			$this->public_opening = $exhibit['public_opening'];	
			$this->visible = $exhibit['visible'];	
			$this->creation_date = $exhibit['creation_date'];
			$this->textual_content =  array();
			$res = requete_sql("SELECT content, language, subject FROM textual_content_exhibit WHERE exhibit_id = '".$id."' ");
			while ( $t = $res->fetch(PDO::FETCH_ASSOC)) {
				array_push($this->textual_content, new ExhibitText($t['id']));
			}
		}
		else {
        	$this->textual_content = array();
        }
	}

	function getId(){
		return $this->id;
	}

	function setTitle($title){
		$this->title = $title;
		return TRUE;
	}

	function getTitle(){
		return $this->title;
	}

	function setBeginDate($begin_date){
		$this->begin_date = $begin_date;
		return TRUE;
	}

	function getBeginDate(){
		return $this->begin_date;
	}

	function setEndDate($end_date){
		$this->end_date = $end_date;
		return TRUE;
	}

	function getEndDate(){
		return $this->end_date;
	}

	function setPublicOpening($public_opening){
		$this->public_opening = $public_opening;
		return TRUE;
	}

	function getPublicOpening(){
		return $this->public_opening;
	}

	function setVisible($visible){
		$this->visible = $visible;
		return TRUE;
	}

	function getVisible(){
		return $this->visible;
	}

	function setCreationDate($creation_date){
		$this->creation_date = $creation_date;
		return TRUE;
	}

	function getCreationDate(){
		return $this->creation_date;
	}


/*********************************
**
** SYNCHRO AVEC LA BASE DE DONNEES
** Si pas d'Id -> INSERT
** Sinon -> UPDATE
**
*********************************/
	function synchroDb(){
		if (empty($this->id)) {
			$create = requete_sql("
				INSERT INTO exhibit 
				VALUES(
					NULL,
					'".addslashes($this->title)."',
					'".addslashes(dateFormat($this->begin_date))."',
					'".addslashes(dateFormat($this->end_date))."',
					'".addslashes($this->public_opening)."',
					TRUE,
					now()
				)");
			if ($create) {
				$this->id = $create;
				return $this->id;
			}
			else{
				return FALSE;
			}
		}
		else{
			$update = requete_sql("
				UPDATE exhibit SET
				exhibit_title = '".addslashes($this->title)."',
				begin_date =  '".addslashes(dateFormat($this->begin_date))."',
				end_date = '".addslashes(dateFormat($this->end_date))."',
				public_opening = '".addslashes($this->public_opening)."'
				WHERE id = '".$this->id."'
				");
			if ($update) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
	}

	function formInfos($target, $action){
		?>
		<form method="POST" action="<?= $target ?>">
			<h2>Informations Générales</h2>
				<div class="form-group">
					<label for="title">Titre de l'exposition :</label>
					<input type="text" name="title" value="<?= $this->title ?>" required />
				</div>
				<div class="form-group">
					<label for="begin_date">Début de l'exposition :</label>
					<input type="date" name="begin_date" value="<?= dateFormat($this->begin_date) ?>" placeholder="jj/mm/aaaa" required />
				</div>
				<div class="form-group">
					<label for="end_date">Fin de l'exposition :</label>
					<input type="date" name="end_date" value="<?= dateFormat($this->end_date) ?>" placeholder="jj/mm/aaaa"  required />
				</div>
				<div class="form-group">
					<label for="public_opening">Horaires d'ouverture :</label>
					<input type="text" name="public_opening" value="<?= $this->public_opening ?>" placeholder="Ex. : Ouvert du lundi au vendredi de 9h à 12h30 et de..." required />
				</div>
				<input type="hidden" name="id" value="<?= $this->id ?>">
				<input type="submit" value="<?= $action; ?>" />
		</form>
		<?php
	}

	static function currentExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE begin_date <= now() AND end_date >= now() ");
		$exhibit = $res->fetch(PDO::FETCH_ASSOC);
		$currentExhibit = new Exhibit($exhibit['id']);
		return $currentExhibit;
	}

	static function listNextExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE begin_date > now() ORDER BY begin_date DESC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $exhibit) {
			$exhibit = new Exhibit($exhibit['id']);
			array_push($list, $exhibit);
		}
		return $list;
	}

	static function listPassedExhibit(){
		$res = requete_sql("SELECT id FROM exhibit WHERE end_date < now() ORDER BY end_date DESC");
		$res = $res->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($res as $exhibit) {
			$exhibit = new Exhibit($exhibit['id']);
			array_push($list, $exhibit);
		}
		return $list;
	}

}