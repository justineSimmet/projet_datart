<?php
/* ---------------------------
	DatArt
	Creation Date : 27/02/2017
	classes/event.php
	author : ApplePie Cie
---------------------------- */

class Event{
	private $id;
	private $exhibit_id;
	private $name;
	private $description;
	private $event_date;
	private $event_start_time;

	function __construct($id=''){
		if($id != 0){
			$res= requete_sql("SELECT * FROM event WHERE id = '".$id."' ");
			$event = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $event['id'];
			$this->exhibit_id = $event['exhibit_id'];
			$this->name = $event['name'];
			$this->description = $event['description'];
			$this->event_date = $event['event_date'];
			$this->event_start_time = $event['event_start_time'];
		}
	}

	function getId(){
		return $this->id;
	}

	function setExhibitId($exhibitId){
		$this->exhibit_id = $exhibitId;
		return $this->exhibit_id;
	}

	function getExhibitId(){
		return $this->exhibit_id;
	}

	function setName($name){
		$this->name = $name;
		return $this->name;
	}

	function getName(){
		return $this->name;
	}

	function setDescription($description){
		$this->description = $description;
		return $this->description;
	}

	function getDescription(){
		return $this->description;
	}

	function setEventDate($eventDate){
		$this->event_date= $eventDate;
		return $this->event_date;
	}

	function getEventDate(){
		return $this->event_date;
	}

	function setEventStartTime($eventStartTime){
		$this->event_start_time= $eventStartTime;
		return $this->event_start_time;
	}

	function getEventStartTime(){
		return $this->event_start_time;
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
				INSERT INTO event 
				VALUES(
					NULL,
					'".addslashes($this->exhibit_id)."',
					'".addslashes($this->name)."',
					'".addslashes($this->description)."',
					'".addslashes($this->event_date)."',
					'".addslashes($this->event_start_time)."'
				)");
			if ($create) {
				return TRUE;
			}
			else{
				return FALSE;
			}
		}
		else{
			$update = requete_sql("
				UPDATE event SET
				name =  '".addslashes($this->name)."',
				description = '".addslashes($this->description)."',
				event_date = '".addslashes($this->event_date)."',
				event_start_time = '".addslashes($this->event_start_time)."'
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

/******************************************************
**
** SUPPRIMER DEFINITIVEMENT UN EVENEMENT
**
******************************************************/

	function deleteEvent(){
		$delete = requete_sql("DELETE FROM event WHERE id ='".$this->id."' ");
		if ($delete) {
			return TRUE;
		}
		else{
			return FALSE;
		}
	}

/******************************************************
**
** FORMULAIRE DE GESTION DES EVENEMENTS
**
******************************************************/
	function formEvent($target, $action, $exhibitId){
		if ($exhibitId != '') {
			$targetExhibit = new Exhibit($exhibitId);
		}
		else{
			$targetExhibit = new Exhibit();
		}
		?>
			<form method="POST" action="<?= $target; ?>" class="form-horizontal">
				<h3><?= $action; ?> un événement</h3>
				<fieldset <?= isset($targetExhibit) && ( empty($targetExhibit->getId()) || $targetExhibit->getEndDate() < date('Y-m-d') || $targetExhibit->getVisible() == FALSE)?'disabled':''; ?> >
					<div class="form-group form-group-lg">
						<label for="name" class="control-label col-lg-5 col-md-5 col-sm-5">Titre :</label>
						<div class="col-lg-7 col-md-7 col-sm-7">
						<input type="text" name="name" class="form-control" value="<?= !empty($this->getId())?$this->getName():''; ?>" <?= $this->getName() == 'Début' || $this->getName() == 'Fin' ?'disabled':''; ?> required />
						</div>
					</div>
					<div class="form-group form-group-lg">
						<label for="description" class="control-label col-lg-5 col-md-5 col-sm-5">Description :</label>
						<div class="col-lg-7 col-md-7 col-sm-7">
						<input type="text" name="description" class="form-control" value="<?= !empty($this->getId())?$this->getDescription():''; ?>" />
						</div>
					</div>
					<div class="form-group form-group-lg">
						<label for="date" class="control-label col-lg-5 col-md-5 col-sm-5">Date de l'évènement :</label>
						<div class="col-lg-7 col-md-7 col-sm-7">
						<input type="date" name="date" class="datepicker form-control" value="<?= !empty($this->getId())?dateFormat($this->getEventDate()):''; ?>" required placeholder="ex. : 24/02/2017" />
						</div>
					</div>
					<div class="form-group form-group-lg">
						<label for="start-time" class="control-label col-lg-5 col-md-5 col-sm-5">Horaire de l'évènement :</label>
						<div class="col-lg-7 col-md-7 col-sm-7">
						<input type="time" name="start-time" class="form-control" value="<?= !empty($this->getId())?timeFormat($this->getEventStartTime()):''; ?>" placeholder="ex. : 14h30" />
						</div>
					</div>

					<input type="hidden" name="id" value="<?= !empty($this->getId())?$this->getId():''; ?>">
					<input type="hidden" name="id-exhibit" value="<?= !empty($this->getId())?$this->getExhibitId():$targetExhibit->getId(); ?>">
					<input type="submit" role="button" class="btn btn-default" value="<?= $action; ?>">
				</fieldset>

			</form>
		<?php
	}


}