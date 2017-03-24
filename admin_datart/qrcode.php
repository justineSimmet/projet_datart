<?php
    require_once('classes/qrlib.php');
    require_once('classes/artwork.php');
    require_once('classes/user.php');
    require_once('classes/artwork_visual.php');
    require_once('includes/include.php');


/**********************************************************************
**
** GENERATION QR Code
**
**********************************************************************/
if (isset($_POST['action']) && $_POST['action'] == 'generateCode') {

    $dir =  __DIR__."\assets\images\artwork\\".$_POST['artworkId'].'\\';; 

    $dataText   = $_POST['target']; 
    $svgTagId   = 'qr-artwork-'.$_POST['artworkId']; 
    $saveToFile = 'artwork-'.$_POST['artworkId'].'.svg'; 
    
    $svgCode = QRcode::svg($dataText, $dir.$saveToFile, "H", 4, 4, false);
     
    if ($svgCode != NULL) {
    	$res = array ('response'=>'error-qr');
        echo json_encode($res); 
    }
    else{
    	$targetArtwork = new Artwork($_POST['artworkId']);
    	$targetArtwork->setQrCode($saveToFile);
    	$synch = $targetArtwork->synchroDb();
    	if ($synch) {
    		$res = array ('response'=>'success', 'target'=>$targetArtwork->getQrCode());
        	echo json_encode($res);
    	}
    	else{
    		$res = array ('response'=>'error-db');
        	echo json_encode($res); 
    	}
    }
}