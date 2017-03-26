<?php
    require_once('classes/qrlib.php');
    require_once('classes/artwork.php');
    require_once('classes/user.php');
    require_once('classes/artwork_visual.php');
    require_once('classes/artwork_textual_content.php');
    require_once('includes/include.php');


/**********************************************************************
**
** GENERATION QR Code
**
**********************************************************************/
if (isset($_POST['action']) && $_POST['action'] == 'generateCode') {

    $dir =  __DIR__."\assets\images\artwork\\".$_POST['artworkId'].'\\';; 
    if(!file_exists($dir)){
        $newFolder = mkdir($dir, 0755, TRUE);
    }

    $dataText   = $_POST['target']; 
    $svgTagId   = 'qr-artwork-'.$_POST['artworkId']; 

    $targetArtwork = new Artwork($_POST['artworkId']);
    $svgName = $targetArtwork->getTitle();

    $svgName = preg_filter("/[^a-z][^0-9]/", "" , strtolower($svgName));
    $svgCode = QRcode::svg($dataText, $dir.$svgName.'-qrcode.svg', "H", 4, 4, false);
     
    if ($svgCode != NULL) {
    	$res = array ('response'=>'error-qr');
        echo json_encode($res); 
    }
    else{
    	$targetArtwork->setQrCode($svgName.'-qrcode.svg');
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