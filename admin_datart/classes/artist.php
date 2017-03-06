<?php 

/* ---------------------------
  DatArt
  Creation Date : 18/02/2017
  classes/artist.php
  author : ApplePie Cie
---------------------------- */

// creation de ma class artist avec les attributs qu'on lui a crée en bdd
class Artist{  
  private $id;
  private $surname;
  private $name;
  private $alias;
  private $photographic_portrait;
  private $creation_date;
  private $visible;


// Le constructeur est la pour initialiser les attributs lors de la création d'un objet(artist)
  function __construct($id = ''){  
    
    if($id != 0){
      $res = requete_sql("SELECT * FROM artist WHERE id='".$id."'");
        $artist = $res->fetch(PDO::FETCH_ASSOC);
        $this->id = $artist['id'];
        $this->surname = $artist['surname'];
        $this->name = $artist['name'];
        $this->alias = $artist['alias'];
        $this->photographic_portrait = $artist['photographic_portrait'];
        $this->creation_date = $artist['creation_date'];
        $this->visible = $artist['visible'];
        $this->textual_content = array();
        $text = requete_sql("SELECT id, language, subject FROM textual_content_artist WHERE artist_id = '".$id."' ");

        if (count($text) !== 0) {

          while ($t = $text->fetch(PDO::FETCH_ASSOC)) {

            if ($t['language'] == 'french') {
              
                if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistFrenchBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistFrenchNote($t['id']));
              }
            }
            elseif ($t['language'] == 'english') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistEnglishBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistEnglishNote($t['id']));
              }   
            }
              elseif ($t['language'] == 'german') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistGermanBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistGermanNote($t['id']));
              }   
            }
              elseif ($t['language'] == 'russian') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistRussianBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistRussianNote($t['id']));
              }   
            }
            elseif ($t['language'] == 'chinese') {
              
              if ($t['subject'] == 'biography') {
                array_push($this->textual_content, new ArtistChineseBiography($t['id']));
              }
              else{
                array_push($this->textual_content, new ArtistChineseNote($t['id']));
              }   
            }               
          }
        }
        else{
          $this->textual_content = array();
        }

    }
    else{
      $this->textual_content = array();
    }
  }

  function getId(){
      return $this->id;
  }
  
  function setSurname($surname){
    $this->surname = $surname;
    return TRUE;
  }

  function getSurname(){
      return $this->surname;
  }
  
  function setName($name){
    $this->name = $name;
    return TRUE;
  }

  function getName(){
      return $this->name;
  }
  
  function setAlias($alias){
    $this->alias = $alias;
    return TRUE;
  }

  function getAlias(){
      return $this->alias;
  }
  
  function setPhotographicPortrait($photographic_portrait){
    $this->photographic_portrait = $photographic_portrait;
    return TRUE;
  }

  function getPhotographicPortrait(){
      return $this->photographic_portrait;
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

  function getTextualContent(){
    return $this->textual_content;
  }

  function getFrenchBiography(){
    return $this->textual_content[0];
  }

  function getFrenchNote(){
    return $this->textual_content[1];
  }

  function getEnglishBiography(){
    return $this->textual_content[2];
  }

  function getEnglishNote(){
    return $this->textual_content[3];
  }

  function getGermanBiography(){
    return $this->textual_content[4];
  }

  function getGermanNote(){
    return $this->textual_content[5];
  }

  function getRussianBiography(){
    return $this->textual_content[6];
  }

  function getRussianNote(){
    return $this->textual_content[7];
  }

  function getChineseBiography(){
    return $this->textual_content[8];
  }

  function getChineseNote(){
    return $this->textual_content[9];
  }

  function getIdentity(){
    if (!empty($this->name) && !empty($this->surname)) {
      if (!empty($this->alias)) {
        $retour = $this->alias.' ('.$this->surname.' '.$this->name.')';
        return $retour; 
      }
      else{
        $retour = $this->surname.' '.$this->name;
        return $retour;
      }
    }
    else{
      $retour = $this->alias;
      return $retour;
    }
  }


/***************************************************************************


              Synchro avec la BDD


* comme tu me l'as expliqué, cela sert:
soit à inserer une nouvelle entrée en bdd si on ne recoit pas d'id
soit à faire un update des données de l'id reçue
***************************************************************************/

  function synchroDb(){
    if (empty($this->id)) { //  si l'id est vide alors on crée une variable create avec une requete sql pour inserer une nouvelle entrée
      $create = requete_sql(" 
        INSERT INTO artist 
        VALUES(
          NULL,
          '".addslashes($this->surname)."',
          '".addslashes($this->name)."',
          '".addslashes($this->alias)."',
          '".addslashes($this->photographic_portrait)."',
          now(),
          TRUE 
        )"); // le true est pour le $visible qui retourne un booléen
      if ($create) { // si $create est true
        $this->id = $create;
        return $this->id; // alors tu retournes l'id que l'on vient de créer
      }
      else{
        return FALSE;
      }
    }
    else{ // si on un id alors on fait une MAJ/update
      $update = requete_sql("
        UPDATE artist SET 
        surname = '".addslashes($this->surname)."',
        name = '".addslashes($this->name)."',
        alias = '".addslashes($this->alias)."',
        photographic_portrait = '".addslashes($this->photographic_portrait)."'
        WHERE id = '".$this->id."'
        ");
      // je ne fais pas d'update sur creation_date car on a pas besoin de la changer et la même sur le visible
      if ($update) { // si l'update est true et dc a fonctionné
        return TRUE; // alors je retourne true;
      }
      else{ // sinon c'est faux
        return FALSE;
      }
    }
  }


/***************************************************************************


              Liste Artiste

* retourne la liste de tout les artistes
***************************************************************************/



  static function listArtist(){
    $listArtist = requete_sql("SELECT id FROM artist WHERE visible = TRUE ORDER BY surname, alias ASC");
    $listArtist = $listArtist->fetchAll(PDO::FETCH_ASSOC);
    $tabList = array();
    foreach ($listArtist as $artist){
      $artist = new Artist($artist['id']);
      array_push($tabList, $artist);
    }
    return $tabList;
  }


/***************************************************************************


              Form créa artiste


***************************************************************************/  


  function formInfos($target, $action=''){
  ?>
    <form action=<?= $target ?> method="POST">
      <fieldset <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> >
      
        <div class="form-group">
          <label for="surname">Nom :</label>
          <input type="text" name="surname" id="surname" value="<?= $this->surname; ?>" />
        </div>

        <div class="form-group">
          <label for="name">Prénom :</label>
          <input type="text" name="name" id="name" value="<?= $this->name; ?>" />
        </div>

        <div class="form-group">
          <label for="alias">Pseudonyme :</label>
          <input type="text" name="alias" id="alias" value="<?= $this->alias; ?>" />
        </div>
      </fieldset> 

          <input type="hidden" name="id" value="<?= $this->id; ?>" />
          <input type="submit" value="<?= $action; ?>" <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> />
    </form>
    <?php 

  }


  function formText($target, $action=''){
  ?>
    <form method="POST" action="<?= $target ?>" class="form-horizontal clearfix">
        
      <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#french">Français</a></li>
        <li><a data-toggle="tab" href="#english">Anglais</a></li>
          <li><a data-toggle="tab" href="#german">Allemand</a></li>
        <li><a data-toggle="tab" href="#russian">Russe</a></li>
        <li><a data-toggle="tab" href="#chinese">Chinois</a></li>
      </ul>
      <div class="tab-content">
        <div id="french" class="tab-pane fade in active">
        <fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>  >
            <div class="form-group form-group-lg">
              <label for="biographyfrench" class="control-label col-lg-3 col-md-4 col-sm-4">Biographie :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="biographyFrench" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getFrenchBiography()->getContent():'' ?>"> 
              </textarea>
              </div>
            </div>
            <div class="form-group form-group-lg">
              <label for="notefrench" class="control-label col-lg-3 col-md-4 col-sm-4">Mot de l'artiste :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="noteFrench" class="form-control"><?= !empty($this->getTextualContent())?$this->getFrenchNote()->getContent():'' ?></textarea>
              </div>
            </div>
        </fieldset>
        </div>

        <div id="english" class="tab-pane fade">
          <fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
            <div class="form-group form-group-lg">
              <label for="biographyEnglish" class="control-label col-lg-3 col-md-4 col-sm-4">Biographie :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="categoryEnglish" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getEnglishBiography()->getContent():'' ?>" >
              </textarea>
              </div>
            </div>
            <div class="form-group form-group-lg">
              <label for="noteEnglish" class="control-label col-lg-3 col-md-4 col-sm-4">Mot de l'artiste :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="noteEnglish" class="form-control"><?= !empty($this->getTextualContent())?$this->getEnglishNote()->getContent():'' ?></textarea>
              </div>
            </div>
          </fieldset>
        </div>

        <div id="german" class="tab-pane fade">
          <fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
            <div class="form-group form-group-lg">
              <label for="biographyGerman" class="control-label col-lg-3 col-md-4 col-sm-4">Biographie :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="biographyGerman" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getGermanCategory()->getContent():'' ?>" >
              </textarea>
              </div>
            </div>
            <div class="form-group form-group-lg">
              <label for="noteGerman" class="control-label col-lg-3 col-md-4 col-sm-4">Mot de l'artiste :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="noteGerman" class="form-control"><?= !empty($this->getTextualContent())?$this->getGermanNote()->getContent():'' ?>
              </textarea>
              </div>
            </div>
          </fieldset>
        </div>

        <div id="russian" class="tab-pane fade">
          <fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?>>
            <div class="form-group form-group-lg">
              <label for="biographyRussian" class="control-label col-lg-3 col-md-4 col-sm-4">Biographie :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="biographyRussian" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getRussianBiography()->getContent():'' ?>" >
              </textarea>
              </div>
            </div>
            <div class="form-group form-group-lg">
              <label for="noteRussian" class="control-label col-lg-3 col-md-4 col-sm-4">Mot de l'artiste :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="noteRussian" class="form-control"><?= !empty($this->getTextualContent())?$this->getRussianNote()->getContent():'' ?></textarea>
              </div>
            </div>
          </fieldset>
        </div>

        <div id="chinese" class="tab-pane fade">
          <fieldset <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> >
            <div class="form-group form-group-lg">
              <label for="biographyChinese" class="control-label col-lg-3 col-md-4 col-sm-4">Biographie :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="biographyChinese" class="form-control" value="<?= !empty($this->getTextualContent())?$this->getChineseBiography()->getContent():'' ?>" >
              </textarea>
              </div>
            </div>
            <div class="form-group form-group-lg">
              <label for="noteChinese" class="control-label col-lg-3 col-md-4 col-sm-4">Mot de l'artiste :</label>
              <div class="col-lg-9 col-md-7 col-sm-7">
              <textarea name="noteChinese" class="form-control"><?= !empty($this->getTextualContent())?$this->getChineseNote()->getContent():'' ?></textarea>
              </div>
            </div>
          </fieldset>       
        </div>
        <input type="hidden" name="id" value="<?= isset($this)?$this->getId():'' ?>">
        <input type="submit" value="<?= $action; ?>" class="btn btn-default pull-right" <?= !empty($this->getId()) &&  $this->getVisible() == FALSE?'disabled':''; ?> />
      </div>
    </form> 

  <?php

  }

  function formPhoto($target, $action=''){
  ?>
    <form action=<?= $target ?> method="POST" enctype="multipart/form-data">
      <fieldset <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?> >
        <label for="titre">Titre de la photo (max. 50 caractères) :</label><br />
          <input type="text" name="titre" value="" id="titre" /><br />
        <label for="file">Fichier (JPG | max. 2 Mo) :</label><br>
        <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
        <input type="file" name="photo" <?= empty($this->getId()) || $this->getVisible() == FALSE?'disabled':''; ?> />
      </fieldset>
      <input type="submit" name="upload" <?= !empty($this->getId()) && $this->getVisible() == FALSE?'disabled':''; ?>/>
    </form> 
  <?php


  }


/***************************************************************************

            Controle sur les traductions de textes

****************************************************************************/

function checkTrad($language){
    if (!empty($this->getTextualContent())) {
      switch ($language) {
        case 'english':
          if (!empty($this->getEnglishBiography()->getContent()) && !empty($this->getEnglishNote()->getContent())) {
          return TRUE;
          }
          else{
            return FALSE;
          }
          break;
        
        case 'german':
          if (!empty($this->getGermanBiography()->getContent()) && !empty($this->getGermanNote()->getContent())) {
          return TRUE;
          }
          else{
            return FALSE;
          }
          break;
        
        case 'russian':
          if (!empty($this->getRussianBiography()->getContent()) && !empty($this->getRussianNote()->getContent())) {
          return TRUE;
          }
          else{
            return FALSE;
          }
          break;
        
        case 'chinese':
          if (!empty($this->getChineseBiography()->getContent()) && !empty($this->getChineseNote()->getContent())) {
          return TRUE;
          }
          else{
            return FALSE;
          }
          break;
        
        default:
          return FALSE;
          break;
      }
    }
    else{
      return FALSE;
    }   
  }





/***************************************************************************


              Visibilité fiche artiste


***************************************************************************/  

  function publishArtist(){
    $this->setVisible('1');
    $res = requete_sql("UPDATE artist set visible ='".$this->visible."' WHERE id='".$this->id."'");
    if ($res) {
      return TRUE;
    }
    else{
      return FALSE;
    }
  }


/***************************************************************************


              Invisibilité d'une fiche artiste


***************************************************************************/  

  function hideArtist(){
    $this->setVisible('0');
    $res = requete_sql("UPDATE artist set visible ='".$this->visible."' WHERE id='".$this->id."'");
    if ($res) {
      return TRUE;
    }
    else{
      return FALSE;
    }

  }

/***************************************************************************


              Supprimer définitivement un artiste


***************************************************************************/

  function deleteArtist(){
    $delete = requete_sql("DELETE FROM artist WHERE id='".$this->id."'");
    // $deleteText = requete_sql("DELETE * FROM textual_content_artist WHERE id='".$this->id."'");
    if ($delete) {
      return TRUE;
    }
    else{
      return FALSE;
    }

  }


/***************************************************************************


        Liste des aristes cachés (en cours de suppression)


***************************************************************************/

  static function listHidenArtist(){
    $res = requete_sql("SELECT id FROM artist WHERE visible = FALSE ORDER BY name DESC");
    $res = $res->fetchAll(PDO::FETCH_ASSOC);
    $list = array();
    foreach ($res as $artist) {
      $artist = new Artist($artist['id']);
      array_push($list, $artist);
    }
    return $list;
  }


/***************************************************************************


              recuperation des photos


***************************************************************************/

/******************************
------- Comparaison de liste
*******************************/

static function compareList($listA, $listB)
{
  $clone = array();
  $count = count($listB);
  foreach ($listA as $list) {
    for ($i=0; $i < $count ; $i++) { 
      if ($list->getId() == $listB[$i]->getId()) {
        array_push($clone, $list->getId());
      }
    }
  }

  return $clone;
}
 


}

