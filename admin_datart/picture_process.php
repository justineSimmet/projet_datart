<?php
	require_once('classes/user.php');
	require_once('classes/artwork.php');
	require_once('classes/artwork_visual.php');
	require_once('includes/include.php');

if(isset($data['file'])){
    $data['file'] = $_FILES;
    $data['text'] = $_POST;
    $maxSize = 2097152;
    $size = filesize($_FILES['image']['tmp_name']);
    $uploadFormat = array('.jpg','.jpeg');
    $extension = strrchr($_FILES['image']['name'], '.');
 
    if(isset($_POST['action']) && isset($_FILES)){
    	$targetArtwork = new Artwork($_POST['artworkId']);
    	$path = __DIR__."\assets\images\artwork\\".$targetArtwork->getId();
    	if(!file_exists($path)){
    		$newFolder = mkdir($path, 0755, TRUE);
    	}
    	if (!empty($_FILES['image'])){
    		$uploadTarget = URL_IMAGES."artwork/".$targetArtwork->getId();
    		$uploadFile = __DIR__."\assets\images\artwork\\".$targetArtwork->getId().'\\';

    		if(!in_array($extension, $uploadFormat)){
    			$data['file']['image']['error'] = 'Votre visuel doit être au format jpeg.';
    			echo json_encode($data);
    		}
    		elseif ($size>$maxSize) {
    			$data['file']['image']['error'] = 'Votre visuel ne doit pas dépasser 2,5 Mo.';
    			echo json_encode($data);
    		}
    		else{
                if($_POST['action'] == 'add-picture-one'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img001'.$extension;
            			$newVisual = new Visual();
            			$artworkId = $targetArtwork->getId();
            			$newVisual->setArtworkId($artworkId);
            			$newVisual->setTarget($uploadTarget.'/'.$namePicture);
            			$newVisual->setLegend($_POST['legend']);
            			$newVisual->setDisplayOrder('1');
            			$synch = $newVisual->synchroDb();
            			if($synch){
        	    			$move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
        	    			if ($move) {
                                $data['text']['pictureId'] = $synch;
        	    				echo json_encode($data);
            				}
            				else{
            					$data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
            					echo json_encode($data);
            				}
            			}
            			else{
            				$data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
            				echo json_encode($data);
            			}
                    }
                    else{
                       $namePicture = 'art'.$targetArtwork->getId().'_img001'.$extension;
                       $newVisual = new Visual($_POST['pictureId']);
                       $newVisual->setLegend($_POST['legend']);
                       $synch = $newVisual->synchroDb();
                        if($synch){
                            $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                            if ($move) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                            echo json_encode($data);
                        }
                    }
                }
                elseif($_POST['action'] == 'add-picture-two'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img002'.$extension;
                        $newVisual = new Visual();
                        $artworkId = $targetArtwork->getId();
                        $newVisual->setArtworkId($artworkId);
                        $newVisual->setTarget($uploadTarget.'/'.$namePicture);
                        $newVisual->setLegend($_POST['legend']);
                        $newVisual->setDisplayOrder('2');
                        $synch = $newVisual->synchroDb();
                        if($synch){
                            $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                            if ($move) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                       $namePicture = 'art'.$targetArtwork->getId().'_img002'.$extension;
                       $newVisual = new Visual($_POST['pictureId']);
                       $newVisual->setLegend($_POST['legend']);
                       $synch = $newVisual->synchroDb();
                        if($synch){
                            $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                            if ($move) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                            echo json_encode($data);
                        }
                    }
                }
                elseif($_POST['action'] == 'add-picture-three'){
                    if (empty($_POST['pictureId'])) {
                        $namePicture = 'art'.$targetArtwork->getId().'_img003'.$extension;
                        $newVisual = new Visual();
                        $artworkId = $targetArtwork->getId();
                        $newVisual->setArtworkId($artworkId);
                        $newVisual->setTarget($uploadTarget.'/'.$namePicture);
                        $newVisual->setLegend($_POST['legend']);
                        $newVisual->setDisplayOrder('3');
                        $synch = $newVisual->synchroDb();
                        if($synch){
                            $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                            if ($move) {
                                $data['text']['pictureId'] = $synch;;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                            echo json_encode($data);
                        }
                    }
                    else{
                       $namePicture = 'art'.$targetArtwork->getId().'_img003'.$extension;
                       $newVisual = new Visual($_POST['pictureId']);
                       $newVisual->setLegend($_POST['legend']);
                       $synch = $newVisual->synchroDb();
                        if($synch){
                            $move = move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile.$namePicture);
                            if ($move) {
                                $data['text']['pictureId'] = $synch;
                                echo json_encode($data);
                            }
                            else{
                                $data['file']['image']['error'] = 'Une erreur s\'est produite lors du transfert de fichier.';
                                echo json_encode($data);
                            }
                        }
                        else{
                            $data['file']['image']['error'] = 'Une erreur s\'est produite lors de l\'enregistrement du fichier.';
                            echo json_encode($data);
                        }
                    }
                }
    		}
    	}
    }
}

if (isset($_POST['action']) && $_POST['action'] == 'deletePicture') {
   $newVisual  = new Visual($_POST['pictureId']);
   $clear = unlink($newVisual->getTarget());
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
    
?>