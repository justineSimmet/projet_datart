<?php
	require_once('classes/user.php');
	require_once('classes/artwork.php');
	require_once('classes/artwork_visual.php');
	require_once('includes/include.php');

    $data['file'] = $_FILES;
    $data['text'] = $_POST;

/**********************************************************************
**
**DELETE D'UN VISUEL
**
**********************************************************************/
if (isset($_POST['action']) && $_POST['action'] == 'deletePicture') {
   $newVisual  = new Visual($_POST['pictureId']);
   $filename = 'art'.$newVisual->getArtworkId().'_img'.$newVisual->getDisplayOrder().'.';
   $path = __DIR__."\assets\images\artwork\\".$newVisual->getArtworkId().'\\';
   $clear = '';
   foreach (glob($path . $filename . '*') as $file) {
       $clear = unlink($file);
   }
   if ($clear) {
        $delete = $newVisual->delete();
        if ($delete) {
            $res = array ('response'=>'success');
            echo json_encode($res); 
        }
        else{
            $res = array ('response'=>'error');
            echo json_encode($res); 
        }
   }
   else{
        $res = array ('response'=>'error');
        echo json_encode($res); 
   }
}
/**********************************************************************
**
** UPLOAD D'UN VISUEL ANNEXE
**
**********************************************************************/
elseif(isset($_POST['action']) && $_POST['action'] == 'uploadPictures'){
    $maxSize = 2097152;
    $size = filesize($_FILES['image']['tmp_name']);
    $uploadFormat = array('.jpg','.jpeg');
    $extension = strrchr($_FILES['image']['name'], '.');

    $targetArtwork = new Artwork($_POST['artworkId']);
    $path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';
    if(!file_exists($path)){
        $newFolder = mkdir($path, 0755, TRUE);
    }
    if (!empty($_FILES['image'])){
        $uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();
        $uploadFile = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';

        if(!in_array($extension, $uploadFormat)){
            $res = array ('error'=>'Votre visuel doit être au format jpeg.');
            echo json_encode($res);
        }
        elseif ($size>$maxSize){
            $res = array ('error'=>'Votre fichier est trop lourd. Max : 2Mo.');
            echo json_encode($res);
        }
        else{
            $newVisual = new Visual();
            $artworkId = $targetArtwork->getId();
            $newVisual->setArtworkId($artworkId);
            //Je détermine si des visuels principaux ont été déjà créé
            $countMain = 0;
            if (!empty($targetArtwork->getPictureOne())) {
                $countMain++;
            }
            if (!empty($targetArtwork->getPictureTwo())) {
                $countMain++;
            }
            if (!empty($targetArtwork->getPictureThree())) {
                $countMain++;
            }
            $orderNumber =$newVisual->defineDisplayOrderAnnexe($countMain);
            $namePicture = 'art'.$targetArtwork->getId().'_img'.$orderNumber.$extension;
            $newVisual->setTarget($uploadTarget.'/'.$namePicture);
            $newVisual->setDisplayOrder($orderNumber);
            $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
            if ($move) {
                $synch = $newVisual->synchroDb();
                if ($synch) {
                    $data['text']['pictureId'] = $synch;
                    echo json_encode($data);
                }
                else{
                    $res = array ('error'=>'Une erreur s\'est produite lors de l\'enregistrement du fichier.');
                    echo json_encode($res);
                }
            }
            else{
                $res = array ('error'=>'Une erreur s\'est produite lors du transfert de fichier.');
                echo json_encode($res);

            }
        }
    }
}
/**********************************************************************
**
** INSERTION DES VISUELS PRINCIPAUX
**
**********************************************************************/
else{
    $maxSize = 2097152;
    $size = filesize($_FILES['file']['tmp_name']);
    $uploadFormat = array('.jpg','.jpeg');
    $extension = strrchr($_FILES['file']['name'], '.');
    if(isset($_POST['action']) && isset($_FILES)){
    	$targetArtwork = new Artwork($_POST['artworkId']);
    	$path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';
    	if(!file_exists($path)){
    		$newFolder = mkdir($path, 0755, TRUE);
    	}
    	if (!empty($_FILES['file'])){
    		$uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();

    		if(!in_array($extension, $uploadFormat)){
    			$data['file']['file']['error'] = 'Votre visuel doit être au format jpeg.';
    			echo json_encode($data);
    		}
    		elseif ($size>$maxSize){
    			$data['file']['image']['error'] = 'Votre visuel ne doit pas dépasser 2 Mo.';
    			echo json_encode($data);
    		}
    		else{
                /****************************************************
                  MAIN PICTURE 1
                ****************************************************/
                if($_POST['action'] == 'add-picture-one'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img1'.$extension;
            			$newVisual = new Visual();
            			$artworkId = $targetArtwork->getId();
            			$newVisual->setArtworkId($artworkId);
            			$newVisual->setTarget($uploadTarget.'/'.$namePicture);
            			$newVisual->setLegend($_POST['legend']);
            			$newVisual->setDisplayOrder('1');
                        $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                        $namePicture = 'art'.$targetArtwork->getId().'_img1'.$extension;
                        $newVisual = new Visual($_POST['pictureId']);
                        $newVisual->setLegend($_POST['legend']);
                        $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                       }
                    }
                }
                /****************************************************
                  MAIN PICTURE 2
                ****************************************************/
                elseif($_POST['action'] == 'add-picture-two'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img2'.$extension;
                        $newVisual = new Visual();
                        $artworkId = $targetArtwork->getId();
                        $newVisual->setArtworkId($artworkId);
                        $newVisual->setTarget($uploadTarget.'/'.$namePicture);
                        $newVisual->setLegend($_POST['legend']);
                        $newVisual->setDisplayOrder('2');
                        $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                        $namePicture = 'art'.$targetArtwork->getId().'_img2'.$extension;
                        $newVisual = new Visual($_POST['pictureId']);
                        $newVisual->setLegend($_POST['legend']);
                        $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                }
                /****************************************************
                  MAIN PICTURE 3
                ****************************************************/
                elseif($_POST['action'] == 'add-picture-three'){
                    if (empty($_POST['pictureId'])) {
                        print_r($size);
                        $namePicture = 'art'.$targetArtwork->getId().'_img3'.$extension;
                        $newVisual = new Visual();
                        $artworkId = $targetArtwork->getId();
                        $newVisual->setArtworkId($artworkId);
                        $newVisual->setTarget($uploadTarget.'/'.$namePicture);
                        $newVisual->setLegend($_POST['legend']);
                        $newVisual->setDisplayOrder('3');
                        $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                        $namePicture = 'art'.$targetArtwork->getId().'_img3'.$extension;
                        $newVisual = new Visual($_POST['pictureId']);
                        $newVisual->setLegend($_POST['legend']);
                        $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                        if ($move) {
                            $synch = $newVisual->synchroDb();
                            if ($synch) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                            echo json_encode($data);
                        }
                    }
                }
    		}
    	}
    }
}

    
?>