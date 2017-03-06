<?php 

/* ---------------------------
  DatArt
  Creation Date : 05/03/2017
  classes/artwork.php
  author : ApplePie Cie
---------------------------- */

class Artwork{
	private $id;
	private $artist_id;
	private $artwork_title;
	private $dimensions;
	private $disponibility;
	private $reference_number;
	private $artist_request;
	private $qrcode;
	private $creation_date;
	private $visible;

	function __construct($id=''){
		if ($id != 0) {
			
			$res = requete_sql("SELECT * FROM artwork WHERE id = '".$id."' ");
			$artwork = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $artwork['id'];
			$this->artist_id = $artwork['artist_id'];
			$this->dimensions = $artwork['dimensions'];
			$this->disponibility = $artwork['disponibility'];
			$this->reference_number = $artwork['reference_number'];
			$this->artist_request = $artwork['artist_request'];
			$this->qrcode = $artwork['qrcode'];
			$this->creation_date = $artwork['creation_date'];
			$this->visible = $artwork['visible'];
		}
	}

	function getId(){
		return $this->id;
	}

	function setArtistId($artist_id){
		$this->artist_id = $artist_id;
		return TRUE;
	}

	function getArtistId(){
		return $this->artist_id;
	}

	function setDimensions($dimensions){
		$this->dimensions = $dimensions;
		return TRUE;
	}

	function getDimensions(){
		return $this->dimensions;
	}

	function setDisponibility($disponibility){
		$this->disponibility = $disponibility;
		return TRUE;
	}

	function getDisponibility(){
		return $this->disponibility;
	}

	function setReferenceNumber($reference_number){
		$this->reference_number = $reference_number;
		return TRUE;
	}

	function getReferenceNumer(){
		return $this->reference_number;
	}

	function setArtistRequest($artist_request){
		$this->artist_request = $artist_request;
		return TRUE;
	}

	function getArtistRequest(){
		return $this->artist_request;
	}

	function setQrCode($qrcode){
		$this->qrcode = $qrcode;
		return TRUE;
	}

	function getQrCode(){
		return $this->qrcode;
	}

	function setCreationDate($creation_date){
		$this->creation_date = $creation_date;
		return TRUE;
	}

	function getCreationDate(){
		return $this->creation_date;
	}

	function setVisible($visible){
		$this->visible = $visible;
		return TRUE;
	}

	function getVisible(){
		return $this->visible;
	}

}