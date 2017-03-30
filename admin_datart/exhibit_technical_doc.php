<?php 

require_once('fpdf/fpdf.php');
require_once('includes/config.php');
require_once('includes/functions.php');
require_once('classes/artist.php');
require_once('classes/artist_textual_content.php');
require_once('classes/artwork.php');
require_once('classes/artwork_textual_content.php');
require_once('classes/artwork_visual.php');
require_once('classes/artwork_additional.php');
require_once('classes/event.php');
require_once('classes/exhibit.php');
require_once('classes/exhibit_textual_content.php');


$targetExhibit = new Exhibit($_GET['id']);
$titleExhibit = $targetExhibit->getTitle();
$durationExhibit = dateFormat($targetExhibit->getBeginDate()).' - '.dateFormat($targetExhibit->getEndDate());
$summaryExhibit = $targetExhibit->getFrenchSummary()->getContent();
$summary = strip_tags(html_entity_decode($summaryExhibit));

$list = $targetExhibit->listAvailableArtwork();
$imageZoning = 'http://localhost/projet_datart/admin_datart/assets/images/exhibit/exhibit'.$_GET['id'].'.jpg';



class PDF extends FPDF{
	//en tête
	function Header(){
		//logo
		$this->Image('assets/images/grand-angle-rvb.png',13,10,40);
		$this->SetFont('Times','',14);
	    // Saut de ligne
	    $this->Ln(40);
		// Décalage à droite
	    $this->Cell(70);
	    //Insertion du titre de l'exposition
	    global $title;

	}


	function TabHead($header,$list){

		$actualY = 35;

		foreach ($list as $artist=>$value) {
		    $this->SetTextColor(213,49,58);
			$this->SetY($actualY);
		    $this->SetX(12); // position de la première colonne
			$this->Write(10,strtoupper($artist));
			$this->SetTextColor(0,0,0);
			$this->Ln(10); // Retour à la ligne
			$this->SetDrawColor(119); // Couleur du fond
		    $this->SetFillColor(221); // Couleur des filets
		    $this->SetTextColor(0); // Couleur du texte
		    $actualY = $this->GetY();
		    $this->SetY($actualY);
		    $this->SetX(8); // position de la première colonne
		    $this->Cell(40,8,'N° de référence',1,0,'C',1);
		    $this->SetX(48); 
		    $this->Cell(70,8,'Infos générales',1,0,'C',1);
		    $this->SetX(118); 
		    $this->Cell(85,8,'Requêtes de l\'artiste',1,0,'C',1);
		    $this->Ln(); // Retour à la ligne
			$actualY = $this->GetY();
			// $this->Text(10,$artistExhibit);
			foreach ($value as $oeuvre) {
	    		// while ($list) {
				    $this->Ln(0);
					$this->SetY($actualY);
				    $this->SetX(8);
				    $this->Cell(40,8,$oeuvre->getReferenceNumber(),1,'C');
				    $this->SetX(48);
				    $this->Cell(70,8,strip_tags(html_entity_decode($oeuvre->getTitle().' --- '.$oeuvre->getDimensions().'cm')),1,'C');
				    //a rajouter le type de l'oeuvre
				    $this->SetX(118);
				    $this->MultiCell(85,8,strip_tags(html_entity_decode($oeuvre->getArtistRequest())),1,'J');
					$actualY = $this->GetY();
				// }
			}	
			$actualY = $this->GetY() + 10;
		}    
	}


	function Footer(){
	$this->SetTextColor(0,0,0);
	// Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    // Police Times italique 8
    $this->SetFont('Times','I',8);
    // Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    $this->SetY(-45);
    // Police Times italique 8
    $this->SetFont('Times','I',8);
	}

}

// Instanciation de la classe dérivée
$pdf = new PDF('P','mm','A4');
$title = $titleExhibit;
$date = $durationExhibit;
$pdf->SetTitle($titleExhibit,[TRUE]);
$pdf->SetAutoPageBreak(TRUE);

$pdf->AddPage();
// Titre
$pdf->Cell(50,10,'Dossier technique',1,0,'C');
 // Saut de ligne
$pdf->Ln(35);
$pdf->SetTextColor(213,49,58);
$pdf->Cell(0,0,strtoupper($title),0,0,'C');
 // Saut de ligne
$pdf->Ln(5);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Times','',8);
$pdf->Cell(0,0,$date,0,0,'C');
$pdf->Ln(40);
$pdf->SetFont('Times','',12);

$pdf->Write(10,$summary);



//deuxieme page
$pdf->AddPage('P','A4');
$header = array('Numéro de référence', 'Infos générales', 'Requêtes de l\'artiste');
// $pdf->Text(8,38,$row['reference_number']);
$pdf->TabHead($header, $list);

//Page d'affichage du plan de positionnement
$pdf->AddPage("L");
$pdf->Ln(0);
$pdf->Image($imageZoning,1,40);

//fin de page+numerotation
$pdf->AliasNbPages();
$nom = 'Dossier technique -'.$title.'.pdf';
$pdf->Output($nom,'I');


?>


