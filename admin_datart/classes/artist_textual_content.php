<?php 

/* ---------------------------
	DatArt
	Creation Date : 03/03/2017
	classes/exhibit_textual_content.php
	author : ApplePie Cie
---------------------------- */


	class ArtistText{
		protected $id;
		protected $artist_id;
		protected $language;
		protected $subject;
		protected $content;

		protected function __construct($id, $language, $subject){
			$res = requete_sql("SELECT * FROM textual_content_artist WHERE id = '".$id."' ");
			$artist = $res->fetch(PDO::FETCH_ASSOC);
			$this->id = $artist['id'];
			$this->artist_id = $artist['artist_id'];
			$this->language = $language;
			$this->subject = $subject;
			$this->content = $artist['content'];
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

		function setContent($content){
			$this->content = $content;
			return TRUE;
		}

		function getContent(){
			return $this->content;
		}

		function synchroDb(){
			if (empty($this->id)) {
				$create = requete_sql("INSERT INTO textual_content_artist 
					VALUES(
					NULL,
					'".addslashes($this->artist_id)."',
					'".addslashes($this->language)."',
					'".addslashes($this->subject)."',
					'".addslashes($this->content)."'
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
					UPDATE textual_content_artist SET
					content = '".addslashes($this->content)."'
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
	}

	class ArtistFrenchBiography extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'french', 'category');
		}
	}


	class ArtistFrenchNote extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'french', 'note');
		}
	}


	class ArtistEnglishBiography extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'english', 'category');
		}
	}


	class ArtistEnglishNote extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'english', 'note');
		}
	}


	class ArtistGermanBiography extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'german', 'category');
		}
	}


	class ArtistGermanNote extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'german', 'note');
		}
	}


	class ArtistRussianBiography extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'russian', 'category');
		}
	}


	class ArtistRussianNote extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'russian', 'note');
		}
	}


	class ArtistChineseBiography extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'chinese', 'category');
		}
	}


	class ArtistChineseNote extends ArtistText{
		
		function __construct($id = 0){
			parent::__construct($id, 'chinese', 'note');
		}
	}




 ?>